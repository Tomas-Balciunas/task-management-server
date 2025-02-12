<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class JWTAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly JWTHandler            $jwtHandler,
        private readonly UserProviderInterface $userProvider
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $header = $request->headers->get('Authorization');

        if (empty($header) || !preg_match('/^Bearer\s+(\S+)$/', $header, $matches)) {
            throw new AuthenticationException('Invalid authorization header.');
        }

        $jwt = $matches[1];
        $payload = $this->jwtHandler->decodeToken($jwt);

        if (empty($payload)) {
            throw new AuthenticationException('Invalid JWT token.');
        }

        return new SelfValidatingPassport(new UserBadge($payload['email'], function ($email) {
            return $this->userProvider->loadUserByIdentifier($email);
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
    }
}