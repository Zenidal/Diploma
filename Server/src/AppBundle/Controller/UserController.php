<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Helper\GameHelper;
use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\User as CoreUser;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator;

class UserController extends Controller
{
    public function profileAction($id)
    {
        $response = new Response();
        $em = $this->getDoctrine()->getEntityManager();
        /** @var UserRepository $userRepository */
        $userRepository = $em->getRepository('AppBundle\Entity\User');
        $userProfile = $userRepository->loadUserByUsername($this->getUser()->getUsername());
        if ($id == $userProfile->getId()) {
            $profile = $em->createQueryBuilder()
                ->select("games, user")
                ->from("AppBundle:User", "user")
                ->innerJoin("user.games", "games")
                ->where("games.isEnded = 1 AND user.id = {$id}")
                ->getQuery()
                ->getArrayResult()[0];
            $totalWins = 0;
            $totalLoses = 0;
            $totalDraws = 0;
            for ($i = 0; $i < count($profile['games']); $i++) {
                $gameField = json_decode($profile['games'][$i]['json'], true);
                if ($gameField[GameHelper::WINNER] == GameHelper::DRAW) {
                    $totalDraws++;
                } elseif ($gameField[$gameField[GameHelper::WINNER]][GameHelper::USER_ID] == $id) {
                    $totalWins++;
                } else {
                    $totalLoses++;
                }
            }
            return $response->setContent(json_encode([
                'totalGames' => count($profile['games']),
                'totalWins' => $totalWins,
                'totalLoses' => $totalLoses,
                'totalDraws' => $totalDraws,
            ]));
        }
        return $response->setContent(json_encode(['message' => "Profile {$id}"]));
    }

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
            $user->setPassword($request->get('password'));

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
                /** @var PasswordEncoderInterface $encoder */
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($request->get('password'), $user->getSalt());
                $user->setPassword($password);
                $em->persist($user);
                $em->flush();
                return $response->setContent(json_encode(['message' => 'User successfully has registered.']));
            }
        }
    }

    public function registerPostAction(Request $request)
    {
        $response = new Response();
        $data = json_decode($request->getContent(), true);
        if ($data['username'] === null || $data['password'] === null) {
            return $response->setContent(json_encode(['errorMessage' => 'You must pass username and password']));
        } else {
            /** @var EncoderFactory $factory */
            $factory = $this->get('security.encoder_factory');
            $em = $this->getDoctrine()->getManager();

            $user = new User();
            $user->setRole(new Role());
            $user->setIsActive(false);
            $user->setUsername($data['username']);
            $user->setPassword($data['password']);

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
                /** @var PasswordEncoderInterface $encoder */
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($data['password'], $user->getSalt());
                $user->setPassword($password);
                $em->persist($user);
                $em->flush();
                return $response->setContent(json_encode(['message' => 'User successfully has registered.']));
            }
        }
    }
}