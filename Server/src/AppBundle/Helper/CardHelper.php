<?php

namespace AppBundle\Helper;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query;

class CardHelper
{
    private $em;

    const ATTACK_TYPE_FIELD = 'attackType';
    const POWER_FIELD = 'power';
    const TEMP_POWER_FIELD = 'tempPower';
    const MELEE_ATTACK = 1;
    const RANGE_ATTACK = 2;
    const SIEGE_ATTACK = 3;
    const WITHOUT_ATTACK = -1;
    const BOND_ABILITY = 'bond';
    const MORALE_ABILITY = 'morale';
    const MEDIC_ABILITY = 'medic';
    const AGILE_ABILITY = 'agile';
    const SPY_ABILITY = 'spy';
    const MUSTER_ABILITY = 'muster';
    const BAD_WEATHER_ABILITY = 'bad_weather';
    const GOOD_WEATHER_ABILITY = 'good_weather';
    const HORN_ABILITY = 'horn';
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
            $opponent = $prefix == 'user1' ? 'user2' : 'user1';

            for ($i = 0; $i < count($gameField[$prefix]['cards']); $i++) {
                if ($gameField[$prefix]['cards'][$i]['id'] == $cardId) {
                    if ($gameField[$prefix]['cards'][$i]['ability']['name'] == CardHelper::SPY_ABILITY) {
                        $gameField[$opponent]['realizedCards'][] = $gameField[$prefix]['cards'][$i];
                        switch ($gameField[$prefix]['cards'][$i][self::ATTACK_TYPE_FIELD]) {
                            case self::MELEE_ATTACK:
                                $gameField[$opponent][GameHelper::POWER_FIELD][GameHelper::MELEE_POWER] += $gameField[$prefix]['cards'][$i][self::TEMP_POWER_FIELD];
                                break;
                            case self::RANGE_ATTACK:
                                $gameField[$opponent][GameHelper::POWER_FIELD][GameHelper::RANGE_POWER] += $gameField[$prefix]['cards'][$i][self::TEMP_POWER_FIELD];
                                break;
                            case self::SIEGE_ATTACK:
                                $gameField[$opponent][GameHelper::POWER_FIELD][GameHelper::SIEGE_POWER] += $gameField[$prefix]['cards'][$i][self::TEMP_POWER_FIELD];
                                break;
                        }
                        $generatedCards = $this->generateRandomCards($gameField, 2);
                        $gameField[$prefix]['cards'] = array_merge($gameField[$prefix]['cards'], $generatedCards);
                    } else {
                        switch ($gameField[$prefix]['cards'][$i]['ability']['name']) {
                            case self::BOND_ABILITY:
                                $counter = 0;
                                for ($yod = 0; $yod < count($gameField[$prefix]['realizedCards']); $yod++) {
                                    if ($gameField[$prefix]['realizedCards'][$yod]['name'] == $gameField[$prefix]['cards'][$i]['name']) {
                                        $gameField[$prefix]['realizedCards'][$yod][self::TEMP_POWER_FIELD] *= 2;
                                        $counter++;
                                        switch ($gameField[$prefix]['cards'][$i][self::ATTACK_TYPE_FIELD]) {
                                            case self::MELEE_ATTACK:
                                                $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::MELEE_POWER] += $gameField[$prefix]['realizedCards'][$yod][self::POWER_FIELD];
                                                break;
                                            case self::RANGE_ATTACK:
                                                $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::RANGE_POWER] += $gameField[$prefix]['realizedCards'][$yod][self::POWER_FIELD];
                                                break;
                                            case self::SIEGE_ATTACK:
                                                $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::SIEGE_POWER] += $gameField[$prefix]['realizedCards'][$yod][self::POWER_FIELD];
                                                break;
                                        }
                                    }
                                }
                                $gameField[$prefix]['cards'][$i][self::TEMP_POWER_FIELD] *= pow(2, $counter);
                                break;
                            case self::MORALE_ABILITY:
                                for ($yod = 0; $yod < count($gameField[$prefix]['realizedCards']); $yod++) {
                                    if ($gameField[$prefix]['realizedCards'][$yod][self::ATTACK_TYPE_FIELD] == $gameField[$prefix]['cards'][$i][self::ATTACK_TYPE_FIELD]) {
                                        if (!$gameField[$prefix]['realizedCards'][$yod]['isUnique']) {
                                            $gameField[$prefix]['realizedCards'][$yod][self::TEMP_POWER_FIELD]++;
                                        }
                                    }
                                }
                                break;
                            case self::MUSTER_ABILITY:
                                $generatedCards = $this->getMusterCards($gameField, $gameField[$prefix]['cards'][$i]);
                                $gameField[$prefix]['realizedCards'] = array_merge($gameField[$prefix]['realizedCards'], $generatedCards);
                                break;
                            case self::MEDIC_ABILITY:
                                if (count($gameField[$prefix]['graveyard'])) {
                                    $healedCardNumber = rand(0, count($gameField[$prefix]['graveyard']) - 1);
                                    $healedCard = $gameField[$prefix]['graveyard'][$healedCardNumber];
                                    $healedCard['ability'] = null;
                                    $gameField[$prefix]['realizedCards'][] = $healedCard;
                                    switch ($healedCard[self::ATTACK_TYPE_FIELD]) {
                                        case self::MELEE_ATTACK:
                                            $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::MELEE_POWER] += $healedCard[self::POWER_FIELD];
                                            break;
                                        case self::RANGE_ATTACK:
                                            $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::RANGE_POWER] += $healedCard[self::POWER_FIELD];
                                            break;
                                        case self::SIEGE_ATTACK:
                                            $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::SIEGE_POWER] += $healedCard[self::POWER_FIELD];
                                            break;
                                    }
                                    unset($gameField[$prefix]['graveyard'][$healedCardNumber]);
                                    sort($gameField[$prefix]['graveyard']);
                                }
                                break;
                            case self::BAD_WEATHER_ABILITY:
                                for ($yod = 0; $yod < count($gameField[$prefix]['realizedCards']); $yod++) {
                                    if ($gameField[$prefix]['realizedCards'][$yod][self::ATTACK_TYPE_FIELD] == $gameField[$prefix]['cards'][$i][self::ATTACK_TYPE_FIELD]) {
                                        if (!$gameField[$prefix]['realizedCards'][$yod]['isUnique']) {
                                            $gameField[$prefix]['realizedCards'][$yod][self::TEMP_POWER_FIELD] = 1;
                                        }
                                    }
                                }
                                for ($yod = 0; $yod < count($gameField[$opponent]['realizedCards']); $yod++) {
                                    if ($gameField[$opponent]['realizedCards'][$yod][self::ATTACK_TYPE_FIELD] == $gameField[$prefix]['cards'][$i][self::ATTACK_TYPE_FIELD]) {
                                        if (!$gameField[$opponent]['realizedCards'][$yod]['isUnique']) {
                                            $gameField[$opponent]['realizedCards'][$yod][self::TEMP_POWER_FIELD] = 1;
                                        }
                                    }
                                }
                                $gameField[$opponent]['realizedCards'][] = $gameField[$prefix]['cards'][$i];
                                break;
                        }
                        if ($gameField[$prefix]['cards'][$i]['ability']['name'] == CardHelper::AGILE_ABILITY) {
                            $types = array(self::MELEE_ATTACK, self::RANGE_ATTACK, self::SIEGE_ATTACK);
                            $gameField[$prefix]['cards'][$i][self::ATTACK_TYPE_FIELD] = $types[rand(0, count($types) - 1)];
                            switch ($gameField[$prefix]['cards'][$i][self::ATTACK_TYPE_FIELD]) {
                                case self::MELEE_ATTACK:
                                    $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::MELEE_POWER] += $gameField[$prefix]['cards'][$i][self::TEMP_POWER_FIELD];
                                    break;
                                case self::RANGE_ATTACK:
                                    $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::RANGE_POWER] += $gameField[$prefix]['cards'][$i][self::TEMP_POWER_FIELD];
                                    break;
                                case self::SIEGE_ATTACK:
                                    $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::SIEGE_POWER] += $gameField[$prefix]['cards'][$i][self::TEMP_POWER_FIELD];
                                    break;
                            }
                            $gameField[$prefix]['realizedCards'][] = $gameField[$prefix]['cards'][$i];
                        } elseif ($gameField[$prefix]['cards'][$i]['ability']['name'] == CardHelper::HORN_ABILITY) {
                            $types = array(self::MELEE_ATTACK, self::RANGE_ATTACK, self::SIEGE_ATTACK);
                            $hornTarget = $types[rand(0, count($types) - 1)];
                            $gameField[$prefix]['cards'][$i][self::ATTACK_TYPE_FIELD] = $hornTarget;
                            for ($yod = 0; $yod < count($gameField[$prefix]['realizedCards']); $yod++) {
                                if ($gameField[$prefix]['realizedCards'][$yod][self::ATTACK_TYPE_FIELD] == $hornTarget) {
                                    if (!$gameField[$prefix]['realizedCards'][$yod]['isUnique']) {
                                        $gameField[$prefix]['realizedCards'][$yod][self::TEMP_POWER_FIELD] *= 2;
                                    }
                                }
                            }
                            $gameField[$prefix]['realizedCards'][] = $gameField[$prefix]['cards'][$i];
                        } elseif ($gameField[$prefix]['cards'][$i]['ability']['name'] == CardHelper::GOOD_WEATHER_ABILITY) {
                            for ($yod = 0; $yod < count($gameField[$prefix]['realizedCards']); $yod++) {
                                if ($gameField[$prefix]['realizedCards'][$yod][self::ATTACK_TYPE_FIELD] == $gameField[$prefix]['cards'][$i][self::ATTACK_TYPE_FIELD]) {
                                    if (!$gameField[$prefix]['realizedCards'][$yod]['isUnique']) {
                                        $gameField[$prefix]['realizedCards'][$yod][self::TEMP_POWER_FIELD] = $gameField[$prefix]['realizedCards'][$yod][self::POWER_FIELD];
                                    }
                                }
                            }
                            for ($yod2 = 0; $yod2 < count($gameField[$opponent]['realizedCards']); $yod2++) {
                                if ($gameField[$opponent]['realizedCards'][$yod2][self::ATTACK_TYPE_FIELD] == $gameField[$prefix]['cards'][$i][self::ATTACK_TYPE_FIELD]) {
                                    if (!$gameField[$opponent]['realizedCards'][$yod2]['isUnique']) {
                                        $gameField[$opponent]['realizedCards'][$yod2][self::TEMP_POWER_FIELD] = $gameField[$prefix]['cards'][$i][self::POWER_FIELD];
                                    }
                                }
                            }
                        } else {
                            switch ($gameField[$prefix]['cards'][$i][self::ATTACK_TYPE_FIELD]) {
                                case self::MELEE_ATTACK:
                                    $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::MELEE_POWER] += $gameField[$prefix]['cards'][$i][self::TEMP_POWER_FIELD];
                                    break;
                                case self::RANGE_ATTACK:
                                    $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::RANGE_POWER] += $gameField[$prefix]['cards'][$i][self::TEMP_POWER_FIELD];
                                    break;
                                case self::SIEGE_ATTACK:
                                    $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::SIEGE_POWER] += $gameField[$prefix]['cards'][$i][self::TEMP_POWER_FIELD];
                                    break;
                            }
                            $gameField[$prefix]['realizedCards'][] = $gameField[$prefix]['cards'][$i];
                        }
                    }
                    $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::TOTAL_POWER] =
                        $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::MELEE_POWER]
                        + $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::RANGE_POWER]
                        + $gameField[$prefix][GameHelper::POWER_FIELD][GameHelper::SIEGE_POWER];


                    $gameField[$opponent][GameHelper::POWER_FIELD][GameHelper::TOTAL_POWER] =
                        $gameField[$opponent][GameHelper::POWER_FIELD][GameHelper::MELEE_POWER]
                        + $gameField[$opponent][GameHelper::POWER_FIELD][GameHelper::RANGE_POWER]
                        + $gameField[$opponent][GameHelper::POWER_FIELD][GameHelper::SIEGE_POWER];
                    unset($gameField[$prefix]['cards'][$i]);

                    if ($gameField[$prefix]['cards'] && count($gameField[$prefix]['cards']) > 0) {
                        sort($gameField[$prefix]['cards']);
                    }
                    if ($gameField[$opponent]['cards'] && count($gameField[$opponent]['cards']) > 0) {
                        sort($gameField[$opponent]['cards']);
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

    private function generateRandomCards($gameField, $count)
    {
        $usedCards = array_merge($gameField['user1']['cards'], $gameField['user2']['cards'], $gameField['user1']['realizedCards'], $gameField['user2']['realizedCards'], $gameField['user1']['graveyard'], $gameField['user2']['graveyard']);
        $allCards = $this->em->getRepository('AppBundle:Card')
            ->createQueryBuilder('card')
            ->select('card', 'ability')
            ->leftJoin('card.ability', 'ability')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

        $diffuse = $this->cards_diff($allCards, $usedCards);
        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $numberInList = rand(0, count($diffuse) - 1);
            $result[] = $diffuse[$numberInList];
            unset($diffuse[$numberInList]);
            sort($diffuse);
        }
        return $result;
    }

    private function getMusterCards($gameField, $musterCard)
    {
        $usedCards = array_merge($gameField['user1']['cards'], $gameField['user2']['cards'], $gameField['user1']['realizedCards'], $gameField['user2']['realizedCards'], $gameField['user1']['graveyard'], $gameField['user2']['graveyard']);
        $allCards = $this->em->getRepository('AppBundle:Card')
            ->createQueryBuilder('card')
            ->select('card', 'ability')
            ->leftJoin('card.ability', 'ability')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

        $diffuse = $this->cards_diff($allCards, $usedCards);
        $result = [];
        for ($i = 0; $i < count($diffuse); $i++) {
            if ($diffuse[$i]['name'] == $musterCard['name']) {
                $result[] = $diffuse[$i];
            }
        }
        return $result;
    }

    private function cards_diff($arr1, $arr2)
    {
        $result = [];
        foreach ($arr1 as $item1) {
            $inArray = false;
            foreach ($arr2 as $item2) {
                if ($item1['id'] == $item2['id']) {
                    $inArray = true;
                }
            }
            if (!$inArray) {
                $result[] = $item1;
            }
        }
        return $result;
    }
}