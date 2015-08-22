<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator;

class UserController extends Controller
{
    public function registerGetAction(Request $request)
    {
        $response = new Response();
        if ($request->get('username') === null || $request->get('password') === null) {
            return $response->setContent(json_encode(['errorMessage' => 'You must pass username and password']));
        } else {
            /** @var EncoderFactory $factory */
            $factory = $this->get('security.encoder_factory');
            $em = $this->getDoctrine()->getManager();

            $user = new User();
            $user->setRole(new Role());
            $user->setIsActive(false);
            $user->setUsername($request->get('username'));

            /** @var PasswordEncoderInterface $encoder */
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($request->get('password'), $user->getSalt());
            $user->setPassword($password);

            /** @var Validator $validator */
            $validator = $this->get('validator');
            /** @var ConstraintViolationList $errors */
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                $errorMessage = '';
                foreach ($errors as $error) {
                    $errorMessage .= $error->getMessage();
                }
                return $response->setContent(json_encode(['errorMessage' => $errorMessage]));
            } else {
                $em->persist($user);
                $em->flush();
                return $response->setContent(json_encode(['message' => 'User successfully has registered.']));
            }
        }
    }
}