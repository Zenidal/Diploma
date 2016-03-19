<?php

namespace AppBundle\Helper;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class GameHelper
{
    const MELEE_POWER = 'attack1';
    const RANGE_POWER = 'attack2';
    const SIEGE_POWER = 'attack3';
    const TOTAL_POWER = 'attack4';

    const CREATOR = 'creator';
    const VISITOR = 'visitor';

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
            self::WINNER => null
        ];
        return json_encode($resultArray);
    }
}