<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use AppBundle\Form\Type\RoleType;
use AppBundle\Repository\GameRepository;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Helper;

class GameController extends Controller
{
    public function createGetAction(Request $request)
    {
        $response = new Response();
        $em = $this->getDoctrine()->getManager();

        $gameName = $request->get('gameName');

        /** @var UserRepository $userRepository */
        $userRepository = $em->getRepository('AppBundle\Entity\User');

        /** @var User $user */
        $user = $userRepository->loadUserByUsername($this->getUser()->getUsername());

        /** @var Game $game */
        $game = new Game();
        $game->addUser($user);
        $user->addGame($game);
        $em->persist($game);
        $gameName ? $game->setName($gameName) : $game->setName('Game by ' . $user->getUsername());
        $em->flush();

        return $response->setContent(json_encode(['message' => 'Game successfully created.']));
    }

    public function acceptAction($id)
    {
        $response = new Response();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var UserRepository $userRepository */
        $userRepository = $em->getRepository('AppBundle\Entity\User');

        /** @var GameRepository $gameRepository */
        $gameRepository = $em->getRepository('AppBundle\Entity\Game');

        /** @var User $user */
        $user = $userRepository->loadUserByUsername($this->getUser()->getUsername());

        /** @var Game $game */
        $game = $gameRepository->find($id);
        if (!$game) {
            return $response->setContent(json_encode(['errorMessage' => 'Game not found.']));
        }
        if ($game->getIsAccepted()) {
            return $response->setContent(json_encode(['errorMessage' => 'Game is already busy.']));
        }
        /** @var User[] $users */
        $users = $game->getUsers();
        $game->setJson(Helper\GameHelper::generate($em, $users[0]->getId(), $user->getId()));
        $game->addUser($user);
        $game->setIsAccepted(true);
        $user->addGame($game);
        $em->flush();

        return $response->setContent(json_encode(['message' => 'Game successfully accepted.']));
    }

    public function createPostAction(Request $request)
    {
        $response = new Response();
        $requestData = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();

        $gameName = $requestData['gameName'];

        /** @var UserRepository $userRepository */
        $userRepository = $em->getRepository('AppBundle\Entity\User');

        /** @var User $user */
        $user = $userRepository->loadUserByUsername($this->getUser()->getUsername());

        /** @var Game $game */
        $game = new Game();
        $game->addUser($user);
        $em->persist($game);
        $gameName ? $game->setName($gameName) : $game->setName('Game by ' . $user->getUsername());
        $user->addGame($game);
        $em->flush();

        return $response->setContent(json_encode(['message' => 'Game successfully created.']));
    }

    public function gameAction($id)
    {
        $response = new Response();
        $em = $this->getDoctrine()->getEntityManager();

        /** @var UserRepository $userRepository */
        $userRepository = $em->getRepository('AppBundle\Entity\User');

        /** @var Game[] $game */
        $games = $em
            ->getRepository('AppBundle:Game')
            ->createQueryBuilder('game')
            ->select('game, users')
            ->join('game.users', 'users')
            ->where("game.id = {$id} AND game.isEnded = 0")
            ->getQuery()
            ->getArrayResult();
        if (count($games) == 0) {
            return $response->setContent(json_encode(['errorMessage' => 'Game not exists.']));
        }
        /** @var User[] $users */
        $users = $games[0]['users'];
        if(count($users) < 2){
            return $response->setContent(json_encode(['errorMessage' => 'Game not ready.']));
        }
        foreach ($users as $user) {
            /** @var User $user */
            $userProfile = $userRepository->loadUserByUsername($this->getUser()->getUsername());
            if($user['id'] == $userProfile->getId()){
                return $response->setContent(json_encode(['game' => $games[0] ? $games[0] : []]));
            }
        }

        return $response->setContent(json_encode(['errorMessage' => 'You have not permissions to watch this game.']));
    }
}