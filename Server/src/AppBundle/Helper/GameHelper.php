<?php

namespace AppBundle\Helper;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query;

class GameHelper
{
    const MELEE_POWER = 'attack1';
    const RANGE_POWER = 'attack2';
    const SIEGE_POWER = 'attack3';
    const TOTAL_POWER = 'attack4';

    const CREATOR = 'creator';
    const VISITOR = 'visitor';
    const DRAW = 'draw';

    const CREATOR_PASSED = 'creatorPassed';
    const VISITOR_PASSED = 'visitorPassed';
    const PASSED_SUCCESSFULLY = 'passed';
    const PASSED_UNSUCCESSFULLY = 'failed';

    const MOVE_FIELD = 'move';
    const POWER_FIELD = 'power';
    const ROUND_FIELD = 'round';
    const CREATOR_WON_FIELD = 'creatorWon';
    const VISITOR_WON_FIELD = 'visitorWon';
    const CREATOR_REALIZED_FIELD = 'creatorRealizedCards';
    const VISITOR_REALIZED_FIELD = 'visitorRealizedCards';
    const CREATOR_CARDS = 'creatorCards';
    const VISITOR_CARDS = 'visitorCards';
    const WINNER = 'winner';

    public static function generate(EntityManager $em)
    {
        $cardRepository = $em->getRepository('AppBundle:Card');
        $allCards = $cardRepository
            ->createQueryBuilder('e')
            ->select('e')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

        $allCards1 = $allCards;
        $allCards2 = $allCards;
        $cards1 = [];
        $cards2 = [];
        for ($i = 0; $i < 10; $i++) {
            $numberInList = rand(0, count($allCards1) - 1);
            $cards1[] = $allCards1[$numberInList];
            unset($allCards1[$numberInList]);
            sort($allCards1);
        }

        for ($i = 0; $i < 10; $i++) {
            $numberInList = rand(0, count($allCards2) - 1);
            $cards2[] = $allCards2[$numberInList];
            unset($allCards2[$numberInList]);
            sort($allCards2);
        }

        $resultArray = [
            self::ROUND_FIELD => 1,
            self::CREATOR_WON_FIELD => 0,
            self::VISITOR_WON_FIELD => 0,
            self::CREATOR_PASSED => false,
            self::VISITOR_PASSED => false,
            self::MOVE_FIELD => self::CREATOR,
            self::POWER_FIELD => [
                self::CREATOR => [
                    self::MELEE_POWER => 0,
                    self::RANGE_POWER => 0,
                    self::SIEGE_POWER => 0,
                    self::TOTAL_POWER => 0
                ],
                self::VISITOR => [
                    self::MELEE_POWER => 0,
                    self::RANGE_POWER => 0,
                    self::SIEGE_POWER => 0,
                    self::TOTAL_POWER => 0
                ]
            ],
            self::CREATOR_REALIZED_FIELD => [],
            self::VISITOR_REALIZED_FIELD => [],
            self::CREATOR_CARDS => $cards1,
            self::VISITOR_CARDS => $cards2,
            self::WINNER => ''
        ];
        return json_encode($resultArray);
    }

    public function pass(EntityManager $em, $userId, $gameId)
    {
        /** @var User $user */
        $user = $em->getRepository('AppBundle:User')->find($userId);
        /** @var Game $game */
        $game = $em->getRepository('AppBundle:Game')->find($gameId);
        $gameField = json_decode($game->getJson(), true);
        if (!$user) {
            throw new EntityNotFoundException();
        }
        if (!$game) {
            throw new EntityNotFoundException();
        }
        if ($this->canPass($game, $userId)) {
            if ($this->roundEnd($game, $userId)) {
                if ($gameField[self::POWER_FIELD][self::CREATOR][self::TOTAL_POWER] > $gameField[self::POWER_FIELD][self::VISITOR][self::TOTAL_POWER]) {
                    $gameField[self::CREATOR_WON_FIELD] += 1;
                    $gameField[self::MOVE_FIELD] = self::CREATOR;
                }
                if ($gameField[self::POWER_FIELD][self::CREATOR][self::TOTAL_POWER] < $gameField[self::POWER_FIELD][self::VISITOR][self::TOTAL_POWER]) {
                    $gameField[self::VISITOR_WON_FIELD] += 1;
                    $gameField[self::MOVE_FIELD] = self::VISITOR;
                }
                if ($gameField[self::POWER_FIELD][self::CREATOR][self::TOTAL_POWER] == $gameField[self::POWER_FIELD][self::VISITOR][self::TOTAL_POWER]) {
                    $gameField[self::CREATOR_WON_FIELD] += 1;
                    $gameField[self::VISITOR_WON_FIELD] += 1;
                    $gameField[self::MOVE_FIELD] = self::CREATOR;
                }
                $gameField[self::ROUND_FIELD]++;
                $gameField[self::CREATOR_PASSED] = false;
                $gameField[self::VISITOR_PASSED] = false;
                $gameField[self::CREATOR_REALIZED_FIELD] = [];
                $gameField[self::VISITOR_REALIZED_FIELD] = [];
                $gameField[self::POWER_FIELD] = [
                    self::CREATOR => [
                        self::MELEE_POWER => 0,
                        self::RANGE_POWER => 0,
                        self::SIEGE_POWER => 0,
                        self::TOTAL_POWER => 0
                    ],
                    self::VISITOR => [
                        self::MELEE_POWER => 0,
                        self::RANGE_POWER => 0,
                        self::SIEGE_POWER => 0,
                        self::TOTAL_POWER => 0
                    ]
                ];
                if ($gameField[self::CREATOR_WON_FIELD] >= 2 || $gameField[self::VISITOR_WON_FIELD] >= 2) {
                    if ($gameField[self::CREATOR_WON_FIELD] > $gameField[self::VISITOR_WON_FIELD]) {
                        $gameField[self::WINNER] = self::CREATOR;
                    } elseif ($gameField[self::CREATOR_WON_FIELD] < $gameField[self::VISITOR_WON_FIELD])
                        $gameField[self::WINNER] = self::VISITOR;
                    else {
                        $gameField[self::WINNER] = self::DRAW;
                    }
                }
            } else {
                $prefix = $userId == $game->getCreator()->getId() ? Gamehelper::CREATOR : Gamehelper::VISITOR;
                $opponent = $userId == $game->getCreator()->getId() ? Gamehelper::VISITOR : Gamehelper::CREATOR;
                $gameField[$prefix . 'Passed'] = true;
                $gameField[self::MOVE_FIELD] = $opponent;
            }
            $game->setJson(json_encode($gameField));
            $em->flush();
            return array(
                'result' => self::PASSED_SUCCESSFULLY,
                'data' => $game
            );
        }
        return array(
            'result' => self::PASSED_UNSUCCESSFULLY
        );

    }

    private
    function canPass(Game $game, $userId)
    {
        $gameField = json_decode($game->getJson(), true);
        if (!$gameField[Gamehelper::CREATOR . 'Passed'] && $userId == $game->getCreator()->getId() ||
            !$gameField[Gamehelper::VISITOR . 'Passed'] && $userId == $game->getVisitor()->getId()
        ) {
            return true;
        } else {
            return false;
        }
    }

    private
    function roundEnd(Game $game, $userId)
    {
        $gameField = json_decode($game->getJson(), true);
        if (!$gameField[Gamehelper::CREATOR . 'Passed'] && $gameField[Gamehelper::VISITOR . 'Passed'] && $userId == $game->getCreator()->getId() ||
            !$gameField[Gamehelper::VISITOR . 'Passed'] && $gameField[Gamehelper::CREATOR . 'Passed'] && $userId == $game->getVisitor()->getId()
        ) {
            return true;
        } else {
            return false;
        }
    }
}