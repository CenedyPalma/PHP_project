<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findBy([], ['createdAt' => 'DESC']);
        
        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/{id}/toggle-block', name: 'app_admin_toggle_block', methods: ['POST'])]
    public function toggleBlock(User $user, EntityManagerInterface $em): JsonResponse
    {
        // Prevent blocking yourself
        if ($user === $this->getUser()) {
            return new JsonResponse(['error' => 'Cannot block yourself'], 400);
        }

        $user->setIsBlocked(!$user->isBlocked());
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'blocked' => $user->isBlocked(),
        ]);
    }

    #[Route('/user/{id}/toggle-admin', name: 'app_admin_toggle_admin', methods: ['POST'])]
    public function toggleAdmin(User $user, EntityManagerInterface $em): JsonResponse
    {
        $roles = $user->getRoles();
        
        if (in_array('ROLE_ADMIN', $roles)) {
            // Remove admin role
            $roles = array_diff($roles, ['ROLE_ADMIN']);
        } else {
            // Add admin role
            $roles[] = 'ROLE_ADMIN';
        }
        
        $user->setRoles(array_values($roles));
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'isAdmin' => in_array('ROLE_ADMIN', $user->getRoles()),
        ]);
    }

    #[Route('/user/{id}/delete', name: 'app_admin_delete_user', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $em): JsonResponse
    {
        // Prevent deleting yourself
        if ($user === $this->getUser()) {
            return new JsonResponse(['error' => 'Cannot delete yourself'], 400);
        }

        $em->remove($user);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }
}
