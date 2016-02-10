<?php
namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
	public function createToken(Request $request, $providerKey)
	{
		$apiKey = $request->headers->get('user-token');
		if(!$apiKey) {
			$apiKey = $request->query->get('user-token');
		}
		if(!$apiKey) {
			throw new BadCredentialsException('No API key found');
		}

		return new PreAuthenticatedToken(
			'anon.',
			$apiKey,
			$providerKey
		);
	}

	public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
	{
		if(!$userProvider instanceof ApiKeyUserProvider) {
			throw new \InvalidArgumentException(
				sprintf(
					'The user provider must be an instance of ApiKeyUserProvider (%s was given).',
					get_class($userProvider)
				)
			);
		}
		$apiKey = $token->getCredentials();
		$username = $userProvider->getUsernameForApiKey($apiKey);
		if(!$username) {
			throw new AuthenticationException(
				sprintf('API Key "%s" does not exist.', $apiKey)
			);
		}
		$user = $userProvider->loadUserByUsername($username);

		return new PreAuthenticatedToken(
			$user,
			$apiKey,
			$providerKey,
			$user->getRoles()
		);
	}

	public function supportsToken(TokenInterface $token, $providerKey)
	{
		return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
	}

	/**
	 * This is called when an interactive authentication attempt fails. This is
	 * called by authentication listeners inheriting from
	 * AbstractAuthenticationListener.
	 *
	 * @param Request $request
	 * @param AuthenticationException $exception
	 *
	 * @return Response The response to return, never null
	 */
	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		$response = new Response();
		$response->setContent(json_encode(['errorMessage' => "Authentication Failed."]));
		$response->setStatusCode(403);

		return $response;
	}
}