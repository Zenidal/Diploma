<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use AppBundle\Repository\GameRepository;
use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{
    public function createGetAction(Request $request)
    {
        $response = new Response();
        $em = $this->getDoctrine()->getManager();

        $gameName = $request->get('gameName');

        /** @var UserRepository $userRepository */
        $userRepository = $em->getRepository('AppBundle\Entity\User');

        /** @var GameRepository $gameRepository */
        $gameRepository = $em->getRepository('AppBundle\Entity\Game');

        /** @var User $user */
        $user = $userRepository->loadUserByUsername($this->getUser()->getUsername());

        /** @var Game $game */
        $game = $user->getCreatedGame() ? $user->getCreatedGame() : $user->getVisitedGame();
        if (!$game) {
            $game = new Game();
            $game->setCreator($user);
            $em->persist($game);
            $gameName ? $game->setName($gameName) : $game->setName('Game by '.$game->getCreator()->getUsername());
            $user->setCreatedGame($game);
            $em->flush();
            return $response->setContent(json_encode(['message' => 'Game succesfully created.']));
        }
        return $response->setContent(json_encode(['errorMessage' => 'Game already exists.']));
    }
}