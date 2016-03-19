<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
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
        if ($game->getVisitor() && $game->getVisitor()->getId()) {
            return $response->setContent(json_encode(['errorMessage' => 'Game is already busy.']));
        }
        if ($game->getCreator() && !$game->getVisitor()) {
            if ($user->getCreatedGame()) {
                $removableGame = $user->getCreatedGame();
                $user->setCreatedGame(null);
                $em->remove($removableGame);
                $em->flush();
            }
            $game->setVisitor($user);
            $game->setJson(Helper\GameHelper::generate($em));
            $user->setVisitedGame($game);
            $em->flush();

            return $response->setContent(json_encode(['message' => 'Game successfully accepted.']));
        }

        return $response->setContent(json_encode(['errorMessage' => 'You can\'t accept your game.']));
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

    public function checkActualGameAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var GameRepository $gameRepository */
        $gameRepository = $em->getRepository('AppBundle\Entity\Game');
        $gameQueryBuilder = $gameRepository->createQueryBuilder('games');

        /** @var UserRepository $userRepository */
        $userRepository = $em->getRepository('AppBundle\Entity\User');
        /** @var User $user */
        $user = $userRepository->loadUserByUsername($this->getUser()->getUsername());

        $games = $gameQueryBuilder
            ->select('games, creator, visitor')
            ->innerJoin('games.creator', 'creator')
            ->innerJoin('games.visitor', 'visitor')
            ->where('creator.id = games.creator')
            ->where('visitor.id = games.visitor')
            ->where($gameQueryBuilder->expr()->isNotNull('games.visitor'))
            ->where($gameQueryBuilder->expr()->isNotNull('games.creator'))
            ->where('games.creator = :creator')
            ->orWhere('games.visitor = :visitor')
            ->setParameters(
                [
                    'creator' => $user->getId(),
                    'visitor' => $user->getId(),
                ]
            )
            ->getQuery()
            ->getArrayResult();

        $response = new Response();
        if (count($games) !== 0) {
            return $response->setContent(json_encode(['isExist' => true]));
        } else {
            return $response->setContent(json_encode(['isExist' => false]));
        }
    }

    public function gameAction()
    {
        $response = new Response();
        $em = $this->getDoctrine()->getManager();

        /** @var UserRepository $userRepository */
        $userRepository = $em->getRepository('AppBundle\Entity\User');

        /** @var User $user */
        $user = $userRepository->loadUserByUsername($this->getUser()->getUsername());

        /** @var GameRepository $gameRepository */
        $gameRepository = $em->getRepository('AppBundle\Entity\Game');

        $gameId = $user->getCreatedGame() ? $user->getCreatedGame() : $user->getVisitedGame();

        if(!$gameId){
            return $response->setContent(json_encode(['errorMessage' => 'User haven\'t got actual game.']));
        }

        /** @var Game $game */
        $game = $gameRepository->find($gameId);

        if (!$game) {
            return $response->setContent(json_encode(['errorMessage' => 'Game not found.']));
        }

        $gameArray = array(
            'id' => $game->getId(),
            'name' => $game->getName(),
            'visitorId' => $game->getVisitor()->getId(),
            'creatorId' => $game->getCreator()->getId(),
            'json' => $game->getJson()
        );

        return $response->setContent(json_encode(['game' => $gameArray]));
    }
}