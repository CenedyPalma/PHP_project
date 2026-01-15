<?php

namespace App\Controller;

use App\Entity\Inventory;
use App\Repository\InventoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/inventory')]
class InventoryController extends AbstractController
{
    #[Route('/', name: 'app_inventory_index')]
    public function index(InventoryRepository $repository): Response
    {
        $inventories = $repository->findBy(['createdBy' => $this->getUser()], ['createdAt' => 'DESC']);
        return $this->render('inventory/index.html.twig', [
            'inventories' => $inventories,
        ]);
    }

    #[Route('/new', name: 'app_inventory_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $inventory = new Inventory();
            $inventory->setName($request->request->get('name'));
            $inventory->setDescription($request->request->get('description'));
            $inventory->setTags(array_filter(array_map('trim', explode(',', $request->request->get('tags', '')))));
            $inventory->setCreatedBy($this->getUser());

            // Process fixed field definitions
            $this->processFieldDefinitions($inventory, $request);

            $em->persist($inventory);
            $em->flush();

            $this->addFlash('success', 'Inventory created successfully!');
            return $this->redirectToRoute('app_inventory_show', ['id' => $inventory->getId()]);
        }

        return $this->render('inventory/new.html.twig');
    }

    #[Route('/{id}', name: 'app_inventory_show', requirements: ['id' => '\d+'])]
    public function show(Inventory $inventory): Response
    {
        return $this->render('inventory/show.html.twig', [
            'inventory' => $inventory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_inventory_edit', requirements: ['id' => '\d+'])]
    public function edit(Inventory $inventory): Response
    {
        // Check ownership
        if ($inventory->getCreatedBy() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You cannot edit this inventory.');
        }

        return $this->render('inventory/edit.html.twig', [
            'inventory' => $inventory,
        ]);
    }

    #[Route('/{id}/settings', name: 'app_inventory_settings', methods: ['POST'])]
    public function saveSettings(Request $request, Inventory $inventory, EntityManagerInterface $em): JsonResponse
    {
        // Check ownership
        if ($inventory->getCreatedBy() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['success' => false, 'error' => 'Access denied'], 403);
        }

        try {
            $data = json_decode($request->getContent(), true);

            // Check version for optimistic locking
            $submittedVersion = (int)($data['version'] ?? 0);
            if ($submittedVersion !== $inventory->getVersion()) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'This inventory was modified by someone else. Please refresh.'
                ]);
            }

            // Update basic fields
            if (isset($data['name'])) {
                $inventory->setName($data['name']);
            }
            if (isset($data['description'])) {
                $inventory->setDescription($data['description']);
            }
            if (isset($data['tags'])) {
                $tags = array_filter(array_map('trim', explode(',', $data['tags'])));
                $inventory->setTags($tags);
            }

            // Update ID format components
            if (isset($data['idFormatComponents'])) {
                $inventory->setIdFormatComponents($data['idFormatComponents']);
            }

            // Update field definitions
            if (isset($data['fields'])) {
                $this->updateFieldsFromData($inventory, $data['fields']);
            }

            $em->flush();

            return new JsonResponse([
                'success' => true,
                'version' => $inventory->getVersion()
            ]);
        } catch (OptimisticLockException $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Conflict! This inventory was modified by another user.'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Save failed: ' . $e->getMessage()
            ]);
        }
    }

    #[Route('/{id}/delete', name: 'app_inventory_delete', methods: ['POST'])]
    public function delete(Request $request, Inventory $inventory, EntityManagerInterface $em): Response
    {
        if ($inventory->getCreatedBy() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You cannot delete this inventory.');
        }

        if ($this->isCsrfTokenValid('delete' . $inventory->getId(), $request->request->get('_token'))) {
            $em->remove($inventory);
            $em->flush();
            $this->addFlash('success', 'Inventory deleted.');
        }

        return $this->redirectToRoute('app_inventory_index');
    }

    /**
     * Process field definitions from HTML form request
     */
    private function processFieldDefinitions(Inventory $inventory, Request $request): void
    {
        $fieldTypes = ['string', 'text', 'int', 'bool', 'link'];

        foreach ($fieldTypes as $type) {
            for ($i = 1; $i <= 3; $i++) {
                $prefix = $type . $i;
                $activeMethod = 'set' . ucfirst($type) . $i . 'Active';
                $nameMethod = 'set' . ucfirst($type) . $i . 'Name';
                $descMethod = 'set' . ucfirst($type) . $i . 'Desc';
                $inTableMethod = 'set' . ucfirst($type) . $i . 'InTable';

                $isActive = $request->request->has($prefix . '_active');
                $name = $request->request->get($prefix . '_name');
                $desc = $request->request->get($prefix . '_desc');
                $inTable = $request->request->has($prefix . '_intable');

                $inventory->$activeMethod($isActive);
                $inventory->$nameMethod($name);
                $inventory->$descMethod($desc);
                $inventory->$inTableMethod($inTable);
            }
        }
    }

    /**
     * Update field definitions from JSON data (auto-save)
     */
    private function updateFieldsFromData(Inventory $inventory, array $fields): void
    {
        $fieldTypes = ['string', 'text', 'int', 'bool', 'link'];

        foreach ($fieldTypes as $type) {
            for ($i = 1; $i <= 3; $i++) {
                $activeKey = $type . $i . '_active';
                $nameKey = $type . $i . '_name';
                $descKey = $type . $i . '_desc';
                $inTableKey = $type . $i . '_inTable';

                $activeMethod = 'set' . ucfirst($type) . $i . 'Active';
                $nameMethod = 'set' . ucfirst($type) . $i . 'Name';
                $descMethod = 'set' . ucfirst($type) . $i . 'Desc';
                $inTableMethod = 'set' . ucfirst($type) . $i . 'InTable';

                if (isset($fields[$activeKey])) {
                    $inventory->$activeMethod((bool)$fields[$activeKey]);
                }
                if (isset($fields[$nameKey])) {
                    $inventory->$nameMethod($fields[$nameKey]);
                }
                if (isset($fields[$descKey])) {
                    $inventory->$descMethod($fields[$descKey]);
                }
                if (isset($fields[$inTableKey])) {
                    $inventory->$inTableMethod((bool)$fields[$inTableKey]);
                }
            }
        }
    }
}
