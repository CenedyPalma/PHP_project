<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\InventoryRepository;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'app_user_profile', requirements: ['id' => '\d+'])]
    public function profile(User $user, InventoryRepository $inventoryRepo, ItemRepository $itemRepo): Response
    {
        $inventories = $inventoryRepo->findBy(['createdBy' => $user], ['createdAt' => 'DESC']);
        $items = $itemRepo->findBy(['createdBy' => $user], ['createdAt' => 'DESC'], 10);

        return $this->render('user/profile.html.twig', [
            'profileUser' => $user,
            'inventories' => $inventories,
            'recentItems' => $items,
        ]);
    }
}
