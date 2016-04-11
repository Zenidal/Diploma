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

    const USER1 = 'user1';
    const USER2 = 'user2';

    const USER_ID = 'userId';

    const USER_PASSED = 'passed';
    const PASSED_SUCCESSFULLY = 'passed';
    const PASSED_UNSUCCESSFULLY = 'failed';

    const MOVE_FIELD = 'move';
    const POWER_FIELD = 'power';
    const ROUND_FIELD = 'round';
    const USER_WON_FIELD = 'won';
    const DRAW = 'draw';
    const USER_REALIZED_FIELD = 'realizedCards';
    const USER_CARDS = 'cards';
    const WINNER = 'winner';

    public static function generate(EntityManager $em, $user1Id, $user2Id)
    {
        $cardRepository = $em->getRepository('AppBundle:Card');
        $allCards = $cardRepository
            ->createQueryBuilder('card')
            ->select('card', 'ability')
            ->join('card.ability', 'ability')
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
            self::MOVE_FIELD => self::USER1,
            self::WINNER => '',
            self::USER1 =>
                [
                    self::USER_ID => $user1Id,
                    self::USER_WON_FIELD => 0,
                    self::USER_PASSED => false,
                    self::USER_REALIZED_FIELD => [],
                    self::USER_CARDS => $cards1,
                    self::POWER_FIELD => [
                        self::MELEE_POWER => 0,
                        self::RANGE_POWER => 0,
                        self::SIEGE_POWER => 0,
                        self::TOTAL_POWER => 0,
                    ],
                ],
            self::USER2 =>
                [
                    self::USER_ID => $user2Id,
                    self::USER_WON_FIELD => 0,
                    self::USER_PASSED => false,
                    self::USER_REALIZED_FIELD => [],
                    self::USER_CARDS => $cards2,
                    self::POWER_FIELD => [
                        self::MELEE_POWER => 0,
                        self::RANGE_POWER => 0,
                        self::SIEGE_POWER => 0,
                        self::TOTAL_POWER => 0,
                    ],
                ],
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
                if ($gameField[self::USER1][self::POWER_FIELD][self::TOTAL_POWER] > $gameField[self::USER2][self::POWER_FIELD][self::TOTAL_POWER]) {
                    $gameField[self::USER1][self::USER_WON_FIELD] += 1;
                    $gameField[self::MOVE_FIELD] = self::USER1;
                }
                if ($gameField[self::USER1][self::POWER_FIELD][self::TOTAL_POWER] < $gameField[self::USER2][self::POWER_FIELD][self::TOTAL_POWER]) {
                    $gameField[self::USER2][self::USER_WON_FIELD] += 1;
                    $gameField[self::MOVE_FIELD] = self::USER2;
                }
                if ($gameField[self::USER1][self::POWER_FIELD][self::TOTAL_POWER] == $gameField[self::USER2][self::POWER_FIELD][self::TOTAL_POWER]) {
                    $gameField[self::USER1][self::USER_WON_FIELD] += 1;
                    $gameField[self::USER2][self::USER_WON_FIELD] += 1;
                    $gameField[self::MOVE_FIELD] = self::USER1;
                }
                $gameField[self::ROUND_FIELD]++;
                $gameField[self::USER1][self::USER_PASSED] = false;
                $gameField[self::USER2][self::USER_PASSED] = false;
                $gameField[self::USER1][self::USER_REALIZED_FIELD] = [];
                $gameField[self::USER2][self::USER_REALIZED_FIELD] = [];
                $gameField[self::POWER_FIELD] = [
                    self::USER1 => [
                        self::MELEE_POWER => 0,
                        self::RANGE_POWER => 0,
                        self::SIEGE_POWER => 0,
                        self::TOTAL_POWER => 0
                    ],
                    self::USER2 => [
                        self::MELEE_POWER => 0,
                        self::RANGE_POWER => 0,
                        self::SIEGE_POWER => 0,
                        self::TOTAL_POWER => 0
                    ]
                ];
                if ($gameField[self::USER1][self::USER_WON_FIELD] >= 2 || $gameField[self::USER2][self::USER_WON_FIELD] >= 2) {
                    if ($gameField[self::USER1][self::USER_WON_FIELD] > $gameField[self::USER2][self::USER_WON_FIELD]) {
                        $gameField[self::WINNER] = self::USER1;
                    } elseif ($gameField[self::USER1][self::USER_WON_FIELD] < $gameField[self::USER2][self::USER_WON_FIELD])
                        $gameField[self::WINNER] = self::USER2;
                    else {
                        $gameField[self::WINNER] = self::DRAW;
                    }
                    $game->setIsEnded(true);
                }
            } else {
                $prefix = $userId == $gameField[GameHelper::USER1][GameHelper::USER_ID] ? Gamehelper::USER1 : Gamehelper::USER2;
                $opponent = $userId == $gameField[GameHelper::USER1][GameHelper::USER_ID] ? Gamehelper::USER2 : Gamehelper::USER1;
                $gameField[$prefix][self::USER_PASSED] = true;
                $gameField[self::MOVE_FIELD] = $opponent;
            }
            $game->setJson(json_encode($gameField));
            $em->flush();
            return array(
                'result' => self::PASSED_SUCCESSFULLY,
                'data' => $game->getId()
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
        if (!$gameField[Gamehelper::USER1][GameHelper::USER_PASSED] && $userId == $gameField[GameHelper::USER1][GameHelper::USER_ID] ||
            !$gameField[Gamehelper::USER2][GameHelper::USER_PASSED] && $userId == $gameField[GameHelper::USER2][GameHelper::USER_ID]
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
        if (!$gameField[Gamehelper::USER1][GameHelper::USER_PASSED] && $gameField[Gamehelper::USER2][GameHelper::USER_PASSED] && $userId == $gameField[GameHelper::USER1][GameHelper::USER_ID] ||
            !$gameField[Gamehelper::USER2][GameHelper::USER_PASSED] && $gameField[Gamehelper::USER1][GameHelper::USER_PASSED] && $userId == $gameField[GameHelper::USER2][GameHelper::USER_ID]
        ) {
            return true;
        } else {
            return false;
        }
    }
}