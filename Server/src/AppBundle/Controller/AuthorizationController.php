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

class AuthorizationController extends Controller
{
    public function authorizeGetAction(Request $request)
    {
        $username = null;
        $password = null;
        $username = $request->get('username');
        $password = $request->get('password');
        if (!isset($username) || !isset($password)) {
            throw new BadRequestHttpException("You must pass username and password fields");
        }
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $repository */
        $repository = $em->getRepository('AppBundle\Entity\User');
        $user = $repository->loadUserByUsername($username);
        if (!$user instanceof User) {
            throw new AccessDeniedHttpException("No matching user account found");
        }
        $encoderFactory = $this->get('security.encoder_factory');
        /** @var PasswordEncoderInterface $encoder */
        $encoder = $encoderFactory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($password, $user->getSalt());
        if ($encodedPassword != $user->getPassword()) {
            throw new AccessDeniedHttpException("Bad credentials.");
        }
        $token = $user->getToken();
        if (!$token) {
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
        $response = new Response();
        return $response->setContent(json_encode([
            'apiKey' => $token->getValue(),
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'roleName' => $user->getRole()->getName(),
        ]));
    }
}