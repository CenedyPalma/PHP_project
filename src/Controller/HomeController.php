<?php

namespace App\Controller;

use App\Entity\Inventory;
use App\Entity\DiscussionPost;
use App\Repository\InventoryRepository;
use App\Repository\DiscussionPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(InventoryRepository $repository): Response
    {
        $latest = $repository->findLatest(10);
        $popular = $repository->findMostPopular(5);
        $tagCloud = $repository->getTagCloud();

        return $this->render('home/index.html.twig', [
            'latestInventories' => $latest,
            'popularInventories' => $popular,
            'tagCloud' => $tagCloud,
        ]);
    }

    #[Route('/search', name: 'app_search')]
    public function search(Request $request, InventoryRepository $repository): Response
    {
        $query = $request->query->get('q', '');
        $tag = $request->query->get('tag', '');

        $results = [];

        if ($tag) {
            $results = $repository->findByTag($tag);
        } elseif ($query) {
            $results = $repository->fullTextSearch($query);
        }

        return $this->render('home/search.html.twig', [
            'query' => $query,
            'tag' => $tag,
            'results' => $results,
        ]);
    }

    #[Route('/inventory/{id}/discussion', name: 'app_inventory_discussion', methods: ['GET', 'POST'])]
    public function discussion(
        Request $request,
        Inventory $inventory,
        EntityManagerInterface $em,
        DiscussionPostRepository $postRepo
    ): Response|JsonResponse {
        // Handle AJAX POST for new message
        if ($request->isMethod('POST') && $request->headers->has('X-Requested-With')) {
            $content = trim($request->request->get('content', ''));
            if (!empty($content) && $this->getUser()) {
                $post = new DiscussionPost();
                $post->setInventory($inventory);
                $post->setAuthor($this->getUser());
                $post->setContent($content);
                $em->persist($post);
                $em->flush();

                return new JsonResponse(['success' => true, 'id' => $post->getId()]);
            }
            return new JsonResponse(['success' => false]);
        }

        // Handle AJAX GET for new posts (polling)
        if ($request->query->has('after') && $request->headers->has('X-Requested-With')) {
            $afterId = (int)$request->query->get('after');
            $newPosts = $postRepo->findNewPosts($inventory->getId(), $afterId);

            $postsData = [];
            foreach ($newPosts as $post) {
                $postsData[] = [
                    'id' => $post->getId(),
                    'author' => $post->getAuthor()->getName(),
                    'authorId' => $post->getAuthor()->getId(),
                    'content' => nl2br(htmlspecialchars($post->getContent())),
                    'createdAt' => $post->getCreatedAt()->format('M d, Y H:i'),
                ];
            }

            return new JsonResponse(['posts' => $postsData]);
        }

        // Full page render
        return $this->render('inventory/discussion.html.twig', [
            'inventory' => $inventory,
        ]);
    }
}
