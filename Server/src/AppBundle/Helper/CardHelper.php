<?php

namespace AppBundle\Helper;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;

class CardHelper
{
    private $em;

    const ATTACK_TYPE_FIELD = 'attackType';
    const POWER_FIELD = 'power';
    const MELEE_ATTACK = 1;
    const RANGE_ATTACK = 2;
    const SIEGE_ATTACK = 3;
    const BOND_ABILITY = 'bond';
    const MORALE_ABILITY = 'morale';
    const MEDIC_ABILITY = 'medic';
    const MOVED_SUCCESSFULLY = 'moved';
    const MOVED_FAIL_BY_TURN = 'opponentTurn';

    /**
     * CardHelper constructor.
     * @param $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param EntityManager $em
     * @param $userId
     * @param $gameId
     * @param $cardId
     * @return array
     * @throws EntityNotFoundException
     */
    public function moveCard($userId, $gameId, $cardId)
    {
        /** @var User $user */
        $user = $this->em->getRepository('AppBundle:User')->find($userId);
        /** @var Game $game */
        $game = $this->em->getRepository('AppBundle:Game')->find($gameId);
        $gameField = json_decode($game->getJson(), true);
        if (!$user) {
            throw new EntityNotFoundException();
        }
        if (!$game) {
            throw new EntityNotFoundException();
        }
        if ($this->canMove($game, $userId)) {
            $prefix = $gameField[GameHelper::MOVE_FIELD];
            for ($i = 0; $i < count($gameField[$prefix]['cards']); $i++) {
                if ($gameField[$prefix]['cards'][$i]['id'] == $cardId) {
                    $gameField[$prefix]['realizedCards'][] = $gameField[$prefix]['cards'][$i];
                    switch ($gameField[$prefix]['cards'][$i][self::ATTACK_TYPE_FIELD]) {
                        case self::MELEE_ATTACK:
                            $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::MELEE_POWER] += $gameField[$prefix]['cards'][$i][self::POWER_FIELD];
                            break;
                        case self::RANGE_ATTACK:
                            $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::RANGE_POWER] += $gameField[$prefix]['cards'][$i][self::POWER_FIELD];
                            break;
                        case self::SIEGE_ATTACK:
                            $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::SIEGE_POWER] += $gameField[$prefix]['cards'][$i][self::POWER_FIELD];
                            break;
                    }
                    $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::TOTAL_POWER] =
                        $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::MELEE_POWER]
                        + $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::RANGE_POWER]
                        + $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::SIEGE_POWER];

                    unset($gameField[$prefix]['cards'][$i]);
                    if ($gameField[$prefix]['cards'] && count($gameField[$prefix]['cards']) > 0) {
                        sort($gameField[$prefix]['cards']);
                    }
                }
            }
            if ($prefix == GameHelper::USER1) {
                if (!$gameField[Gamehelper::USER2][GameHelper::USER_PASSED]) {
                    $gameField[Gamehelper::MOVE_FIELD] = $gameField[Gamehelper::MOVE_FIELD] == Gamehelper::USER1 ? Gamehelper::USER2 : Gamehelper::USER1;
                }
            } elseif ($prefix == GameHelper::USER2) {
                if (!$gameField[Gamehelper::USER1][GameHelper::USER_PASSED]) {
                    $gameField[Gamehelper::MOVE_FIELD] = $gameField[Gamehelper::MOVE_FIELD] == Gamehelper::USER1 ? Gamehelper::USER2 : Gamehelper::USER1;
                }
            }
            $game->setJson(json_encode($gameField));
            $this->em->flush();
            return array(
                'result' => self::MOVED_SUCCESSFULLY,
                'data' => $game->getId()
            );
        }
        return array('result' => self::MOVED_FAIL_BY_TURN);
    }

    private function canMove(Game $game, $userId)
    {
        $gameField = json_decode($game->getJson(), true);
        if ($gameField[GameHelper::MOVE_FIELD] == Gamehelper::USER1 && $userId == $gameField[GameHelper::USER1][GameHelper::USER_ID] ||
            $gameField[GameHelper::MOVE_FIELD] == Gamehelper::USER2 && $userId == $gameField[GameHelper::USER2][GameHelper::USER_ID]
        ) {
            return true;
        } else {
            return false;
        }
    }
}