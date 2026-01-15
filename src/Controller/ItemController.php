<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\ItemLike;
use App\Entity\Inventory;
use App\Repository\ItemLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/item')]
class ItemController extends AbstractController
{
    #[Route('/new/{inventoryId}', name: 'app_item_new', methods: ['GET', 'POST'])]
    public function new(int $inventoryId, Request $request, EntityManagerInterface $em): Response
    {
        $inventory = $em->getRepository(Inventory::class)->find($inventoryId);
        if (!$inventory) {
            throw $this->createNotFoundException('Inventory not found.');
        }

        if ($request->isMethod('POST')) {
            $item = new Item();
            $item->setInventory($inventory);
            $item->setCreatedBy($this->getUser());

            // Generate custom ID
            $customId = $inventory->generateNextCustomId();
            $item->setCustomId($customId);
            $inventory->incrementNextSequence();

            // Collect field values using fixed columns
            foreach ($inventory->getActiveFields() as $field) {
                $type = $field['type'];
                $index = $field['index'];
                $inputName = $type . $index;
                $rawValue = $request->request->get($inputName);

                // Type conversion
                switch ($type) {
                    case 'int':
                        $value = $rawValue !== '' && $rawValue !== null ? (int)$rawValue : null;
                        break;
                    case 'bool':
                        $value = $rawValue === 'on' || $rawValue === '1';
                        break;
                    default:
                        $value = $rawValue;
                }

                $item->setFieldValue($type, $index, $value);
            }

            $em->persist($item);
            $em->flush();

            $this->addFlash('success', 'Item created with ID: ' . $customId);
            return $this->redirectToRoute('app_inventory_show', ['id' => $inventoryId]);
        }

        return $this->render('item/new.html.twig', [
            'inventory' => $inventory,
        ]);
    }

    #[Route('/{id}', name: 'app_item_show', requirements: ['id' => '\d+'])]
    public function show(Item $item): Response
    {
        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Item $item, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $submittedVersion = (int)$request->request->get('version');

            if ($submittedVersion !== $item->getVersion()) {
                $this->addFlash('error', 'This item was modified by someone else. Please refresh and try again.');
                return $this->redirectToRoute('app_item_edit', ['id' => $item->getId()]);
            }

            try {
                $inventory = $item->getInventory();

                foreach ($inventory->getActiveFields() as $field) {
                    $type = $field['type'];
                    $index = $field['index'];
                    $inputName = $type . $index;
                    $rawValue = $request->request->get($inputName);

                    switch ($type) {
                        case 'int':
                            $value = $rawValue !== '' && $rawValue !== null ? (int)$rawValue : null;
                            break;
                        case 'bool':
                            $value = $rawValue === 'on' || $rawValue === '1';
                            break;
                        default:
                            $value = $rawValue;
                    }

                    $item->setFieldValue($type, $index, $value);
                }

                $em->flush();
                $this->addFlash('success', 'Item updated successfully!');
                return $this->redirectToRoute('app_item_show', ['id' => $item->getId()]);
            } catch (OptimisticLockException $e) {
                $this->addFlash('error', 'Conflict! This item was modified by another user. Please reload and try again.');
            }
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_item_delete', methods: ['POST'])]
    public function delete(Request $request, Item $item, EntityManagerInterface $em): Response
    {
        $inventoryId = $item->getInventory()->getId();

        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $em->remove($item);
            $em->flush();
            $this->addFlash('success', 'Item deleted.');
        }

        return $this->redirectToRoute('app_inventory_show', ['id' => $inventoryId]);
    }

    #[Route('/{id}/like', name: 'app_item_like', methods: ['POST'])]
    public function like(Item $item, EntityManagerInterface $em, ItemLikeRepository $likeRepo): Response
    {
        $user = $this->getUser();
        $existingLike = $likeRepo->findByItemAndUser($item->getId(), $user->getId());

        if ($existingLike) {
            $em->remove($existingLike);
            $action = 'unliked';
        } else {
            $like = new ItemLike();
            $like->setItem($item);
            $like->setUser($user);
            $em->persist($like);
            $action = 'liked';
        }

        $em->flush();

        return $this->json([
            'success' => true,
            'action' => $action,
            'likesCount' => $item->getLikesCount(),
        ]);
    }
}
