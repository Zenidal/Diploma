<?php

namespace AppBundle\Helpers;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class GameJsonGenerator
{
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
            $numberInList = rand(0, count($allCards1));
            $cards1[] = $allCards1[$numberInList];
            unset($allCards1[$numberInList]);
            sort($allCards1);
        }

        for ($i = 0; $i < 10; $i++) {
            $numberInList = rand(0, count($allCards2));
            $cards2[] = $allCards2[$numberInList];
            unset($allCards2[$numberInList]);
            sort($allCards2);
        }

        $resultArray = [
            'round' => 1,
            'won1' => 0,
            'won2' => 0,
            'move' => 1,
            'realizedCards1' => [],
            'realizedCards2' => [],
            'cards1' => $cards1,
            'cards2' => $cards2,
            'winner' => null
        ];
        return json_encode($resultArray);
    }
}