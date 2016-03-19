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
        if ($this->canMove($userId, $gameId)) {
            $prefix = $gameField[GameHelper::MOVE_FIELD];
            for ($i = 0; $i < count($gameField[$prefix . 'Cards']); $i++) {
                if ($gameField[$prefix . 'Cards'][$i]['id'] == $cardId) {
                    $gameField[$prefix . 'RealizedCards'][] = $gameField[$prefix . 'Cards'][$i];
                    switch ($gameField[$prefix . 'Cards'][$i][self::ATTACK_TYPE_FIELD]) {
                        case self::MELEE_ATTACK:
                            $gameField[GameHelper::POWER_FIELD][$prefix][GameHelper::MELEE_POWER] += $gameField[$prefix . 'Cards'][$i][self::POWER_FIELD];
                            break;
                        case self::RANGE_ATTACK:
                            $gameField[GameHelper::POWER_FIELD][$prefix][GameHelper::RANGE_POWER] += $gameField[$prefix . 'Cards'][$i][self::POWER_FIELD];
                            break;
                        case self::SIEGE_ATTACK:
                            $gameField[GameHelper::POWER_FIELD][$prefix][GameHelper::SIEGE_POWER] += $gameField[$prefix . 'Cards'][$i][self::POWER_FIELD];
                            break;
                    }
                    $gameField[GameHelper::POWER_FIELD][$prefix][GameHelper::TOTAL_POWER] =
                        $gameField[GameHelper::POWER_FIELD][$prefix][GameHelper::MELEE_POWER]
                        + $gameField[GameHelper::POWER_FIELD][$prefix][GameHelper::RANGE_POWER]
                        + $gameField[GameHelper::POWER_FIELD][$prefix][GameHelper::SIEGE_POWER];

                    unset($gameField[$prefix . 'Cards'][$i]);
                    if ($gameField[$prefix . 'Cards'] && count($gameField[$prefix . 'Cards']) > 0) {
                        sort($gameField[$prefix . 'Cards']);
                    }
                }
            }
            if ($prefix == GameHelper::CREATOR) {
                if (!$gameField[Gamehelper::VISITOR . 'Passed']) {
                    $gameField[Gamehelper::MOVE_FIELD] = $gameField[Gamehelper::MOVE_FIELD] == Gamehelper::CREATOR ? Gamehelper::VISITOR : Gamehelper::CREATOR;
                }
            } elseif ($prefix == GameHelper::VISITOR) {
                if (!$gameField[Gamehelper::CREATOR . 'Passed']) {
                    $gameField[Gamehelper::MOVE_FIELD] = $gameField[Gamehelper::MOVE_FIELD] == Gamehelper::CREATOR ? Gamehelper::VISITOR : Gamehelper::CREATOR;
                }
            }
            $game->setJson(json_encode($gameField));
            $this->em->flush();
            return array(
                'result' => self::MOVED_SUCCESSFULLY,
                'data' => $game
            );
        }
        return array('result' => self::MOVED_FAIL_BY_TURN);
    }

    private function canMove($userId, $gameId)
    {
        /** @var Game $game */
        $game = $this->em->getRepository('AppBundle:Game')->find($gameId);
        $gameField = json_decode($game->getJson(), true);
        if ($gameField[GameHelper::MOVE_FIELD] == Gamehelper::CREATOR && $userId == $game->getCreator()->getId() ||
            $gameField[GameHelper::MOVE_FIELD] == Gamehelper::VISITOR && $userId == $game->getVisitor()->getId()
        ) {
            return true;
        } else {
            return false;
        }
    }
}