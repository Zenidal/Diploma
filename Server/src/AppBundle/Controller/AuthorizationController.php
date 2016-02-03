<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Token;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use AppBundle\Helpers\ApiKeyGenerator;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class AuthorizationController extends Controller
{
	public function authorizePostAction(Request $request)
	{
		$response = new Response();
		$data = json_decode($request->getContent(), true);
		$username = null;
		$password = null;
		$username = $data['username'];
		$password = $data['password'];
		if(!isset($username) || !isset($password)) {
			throw new BadRequestHttpException("You must pass username and password fields");
		}
		$em = $this->getDoctrine()->getManager();
		/** @var UserRepository $repository */
		$repository = $em->getRepository('AppBundle\Entity\User');
		try {
			$user = $repository->loadUserByUsername($username);
		} catch(UsernameNotFoundException $exception) {
			return $response->setContent(json_encode(['errorMessage' => $exception->getMessage()]));
		}
		if(!$user instanceof User) {
			return $response->setContent(json_encode(['errorMessage' => "No matching user account found"]));
		}
		$encoderFactory = $this->get('security.encoder_factory');
		/** @var PasswordEncoderInterface $encoder */
		$encoder = $encoderFactory->getEncoder($user);
		$encodedPassword = $encoder->encodePassword($password, $user->getSalt());
		if($encodedPassword != $user->getPassword()) {
			return $response->setContent(json_encode(['errorMessage' => "Bad credentials."]));
		}
		$token = $user->getToken();
		if(!$token) {
			$token = new Token();
			$token->setValue(ApiKeyGenerator::generateApiKey());
			$token->setUser($user);
			$user->setToken($token);
			$em->persist($token);
			$em->flush();
		} else {
			$token->setValue(ApiKeyGenerator::generateApiKey());
			$token->setUser($user);
			$user->setToken($token);
			$em->flush();
		}

		return $response->setContent(
			json_encode(
				[
					'message'  => 'User successfully authorized.',
					'apiKey'   => $token->getValue(),
					'id'       => $user->getId(),
					'username' => $user->getUsername(),
					'roleName' => $user->getRole()->getName(),
				]
			)
		);
	}
}