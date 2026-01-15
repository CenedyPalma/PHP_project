<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class FacebookAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
{
    public function __construct(
        private ClientRegistry $clientRegistry,
        private EntityManagerInterface $entityManager,
        private RouterInterface $router
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_facebook_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('facebook');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
                /** @var \League\OAuth2\Client\Provider\FacebookUser $facebookUser */
                $facebookUser = $client->fetchUserFromToken($accessToken);

                $email = $facebookUser->getEmail();

                // Find existing user by facebook ID
                $user = $this->entityManager->getRepository(User::class)->findOneBy(['facebookId' => $facebookUser->getId()]);

                if (!$user && $email) {
                    // Find by email
                    $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
                }

                if (!$user) {
                    // Create new user
                    $user = new User();
                    $user->setEmail($email ?? $facebookUser->getId() . '@facebook.local');
                    $user->setName($facebookUser->getName() ?? 'Facebook User');
                    $user->setFacebookId($facebookUser->getId());
                    $user->setAvatarUrl($facebookUser->getPictureUrl());
                    $this->entityManager->persist($user);
                } else {
                    // Update facebook ID if not set
                    if (!$user->getFacebookId()) {
                        $user->setFacebookId($facebookUser->getId());
                    }
                    if (!$user->getAvatarUrl() && $facebookUser->getPictureUrl()) {
                        $user->setAvatarUrl($facebookUser->getPictureUrl());
                    }
                }

                $this->entityManager->flush();
                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->router->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());
        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse($this->router->generate('app_login'));
    }
}
