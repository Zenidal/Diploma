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
            $user->setCreatedGame($game);
            $em->persist($game);
            $gameName ? $game->setName($gameName) : $game->setName('Game by ' . $game->getCreator()->getUsername());
            $em->flush();
            return $response->setContent(json_encode(['message' => 'Game successfully created.']));
        }
        return $response->setContent(json_encode(['errorMessage' => 'Game already exists.']));
    }

    public function acceptAction($id)
    {
        $response = new Response();
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
        } else {
            if ($user->getVisitedGame()) {
                return $response->setContent(json_encode(['errorMessage' => 'You can\'t visit this game.']));
            }
            if ($game->getCreator() && !$game->getVisitor()) {
                $game->setVisitor($user);
                $user->setVisitedGame($game);
                $em->flush();
                return $response->setContent(json_encode(['message' => 'Game successfully accepted.']));
            }
        }
        return $response->setContent(json_encode(['errorMessage' => 'Game is already busy.']));
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
        $game = $user->getCreatedGame() ? $user->getCreatedGame() : $user->getVisitedGame();
        if (!$game) {
            $game = new Game();
            $game->setCreator($user);
            $em->persist($game);
            $gameName ? $game->setName($gameName) : $game->setName('Game by ' . $game->getCreator()->getUsername());
            $user->setCreatedGame($game);
            $em->flush();
            return $response->setContent(json_encode(['message' => 'Game successfully created.']));
        }
        return $response->setContent(json_encode(['errorMessage' => 'Game already exists.']));
    }

    public function indexGetAction()
    {
        $response = new Response();
        $em = $this->getDoctrine()->getManager();

        /** @var GameRepository $gameRepository */
        $gameRepository = $em->getRepository('AppBundle\Entity\Game');

        /** @var UserRepository $userRepository */
        $userRepository = $em->getRepository('AppBundle\Entity\User');

        /** @var User $user */
        $user = $userRepository->loadUserByUsername($this->getUser()->getUsername());

        $gamesQueryBuilder = $gameRepository->createQueryBuilder('games');
        $games = $gamesQueryBuilder
            ->select('games', 'creator')
            ->leftJoin('games.creator', 'creator')
            ->where('creator.id = games.creator')
            ->andWhere($gamesQueryBuilder->expr()->isNull('games.visitor'))
            ->andWhere('games.creator != :creator')
            ->setParameter('creator', $user->getId())
            ->getQuery()
            ->getArrayResult();
        if (count($games) === 0) {
            return $response->setContent(json_encode(['games' => null]));
        }
        return $response->setContent(json_encode(['games' => $games]));
    }
}