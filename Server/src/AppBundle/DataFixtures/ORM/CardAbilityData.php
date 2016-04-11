<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Helper\CardHelper;

class CardAbilityData
{
    private $abilities = [
        [
            'name' => CardHelper::MUSTER_ABILITY,
            'description' => 'Finds any cards with the same name in your deck and plays them instantly.',
            'cards' => [
                [
                    'name' => 'Thug of gangs',
                    'power' => '3',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK
                ],
                [
                    'name' => 'Thug of gangs',
                    'power' => '3',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK
                ],
                [
                    'name' => 'Thug of gangs',
                    'power' => '3',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK
                ],
                [
                    'name' => 'Thug of gangs',
                    'power' => '4',
                    'isUnique' => false,
                    'attackType' => CardHelper::RANGE_ATTACK
                ],
                [
                    'name' => 'Thug of gangs',
                    'power' => '4',
                    'isUnique' => false,
                    'attackType' => CardHelper::RANGE_ATTACK
                ],
                [
                    'name' => 'Sad archer',
                    'power' => '5',
                    'isUnique' => false,
                    'attackType' => CardHelper::RANGE_ATTACK,
                ],
                [
                    'name' => 'Sad archer',
                    'power' => '5',
                    'isUnique' => false,
                    'attackType' => CardHelper::RANGE_ATTACK,
                ],
                [
                    'name' => 'Sad archer',
                    'power' => '5',
                    'isUnique' => false,
                    'attackType' => CardHelper::RANGE_ATTACK,
                ],
            ]
        ],
        [
            'name' => CardHelper::MEDIC_ABILITY,
            'description' => 'Choose one card from your discard pile to play instantly. This excludes heroes or other weather / special cards.',
            'cards' => [
                [
                    'name' => 'Siege medic',
                    'power' => '5',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK
                ],
                [
                    'name' => 'Healer',
                    'power' => '10',
                    'isUnique' => true,
                    'attackType' => CardHelper::RANGE_ATTACK
                ],
                [
                    'name' => 'Weak Healer',
                    'power' => '0',
                    'isUnique' => true,
                    'attackType' => CardHelper::SIEGE_ATTACK
                ],
                [
                    'name' => 'Weak Healer',
                    'power' => '0',
                    'isUnique' => true,
                    'attackType' => CardHelper::SIEGE_ATTACK
                ],
            ],
        ],
        [
            'name' => CardHelper::MORALE_ABILITY,
            'description' => 'Adds +1 strength to every card in it\'s row, excluding itself.',
            'cards' => [
                [
                    'name' => 'Siege Expert',
                    'power' => '1',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK
                ],
                [
                    'name' => 'Siege Expert',
                    'power' => '1',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK
                ],
                [
                    'name' => 'Siege Expert',
                    'power' => '1',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK
                ],
                [
                    'name' => 'Beast',
                    'power' => '8',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK,
                ],
            ]
        ],
        [
            'name' => CardHelper::BOND_ABILITY,
            'description' => 'Doubles the strength of both cards when placed next to a unit of the same name.',
            'cards' => [
                [
                    'name' => 'Infantryman',
                    'power' => '1',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK
                ],
                [
                    'name' => 'Infantryman',
                    'power' => '1',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK
                ],
                [
                    'name' => 'Infantryman',
                    'power' => '1',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK
                ],
                [
                    'name' => 'Captain',
                    'power' => '4',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK
                ],
                [
                    'name' => 'Captain',
                    'power' => '4',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK
                ],
                [
                    'name' => 'Hunter',
                    'power' => '5',
                    'isUnique' => false,
                    'attackType' => CardHelper::RANGE_ATTACK
                ],
                [
                    'name' => 'Hunter',
                    'power' => '5',
                    'isUnique' => false,
                    'attackType' => CardHelper::RANGE_ATTACK
                ],
                [
                    'name' => 'Hunter',
                    'power' => '5',
                    'isUnique' => false,
                    'attackType' => CardHelper::RANGE_ATTACK
                ],
                [
                    'name' => 'Catapult',
                    'power' => '8',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK
                ],
                [
                    'name' => 'Catapult',
                    'power' => '8',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK
                ],
            ]
        ],
        [
            'name' => CardHelper::SPY_ABILITY,
            'description' => 'Place on an opponents battlefield (counts towards their total strength), then draw two cards from your own deck.',
            'cards' => [
                [
                    'name' => 'Unique spy',
                    'power' => '0',
                    'isUnique' => true,
                    'attackType' => CardHelper::MELEE_ATTACK,
                ],
                [
                    'name' => 'Bad spy',
                    'power' => '9',
                    'isUnique' => false,
                    'attackType' => CardHelper::RANGE_ATTACK,
                ],
                [
                    'name' => 'Shadow',
                    'power' => '4',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK
                ],
                [
                    'name' => 'Ghost',
                    'power' => '5',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK
                ],
                [
                    'name' => 'Good spy',
                    'power' => '1',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK
                ],

            ]
        ],
        [
            'name' => CardHelper::AGILE_ABILITY,
            'description' => 'Can be placed in either the Close Combat or Ranged rows. May not move once the card has been placed.',
            'cards' => [
                [
                    'name' => 'Weak universal',
                    'power' => '4',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK
                ],
                [
                    'name' => 'Universal fighter',
                    'power' => '5',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK
                ],
                [
                    'name' => 'Universal fighter',
                    'power' => '5',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK
                ],
                [
                    'name' => 'Good universal',
                    'power' => '6',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK
                ],
                [
                    'name' => 'Useless universal',
                    'power' => '2',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK
                ],
            ]
        ],
        [
            'name' => null,
            'cards' => [
                //melee
                [
                    'name' => 'Soldier',
                    'power' => '1',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK,

                ],
                [
                    'name' => 'Soldier',
                    'power' => '1',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK,

                ],
                [
                    'name' => 'Fighter',
                    'power' => '2',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK,

                ],
                [
                    'name' => 'Sir',
                    'power' => '5',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK,

                ],
                [
                    'name' => 'Sir',
                    'power' => '5',
                    'isUnique' => false,
                    'attackType' => CardHelper::MELEE_ATTACK,

                ],

                //range
                [
                    'name' => 'Archer',
                    'power' => '4',
                    'isUnique' => false,
                    'attackType' => CardHelper::RANGE_ATTACK,

                ],
                [
                    'name' => 'Archer',
                    'power' => '4',
                    'isUnique' => false,
                    'attackType' => CardHelper::RANGE_ATTACK,

                ],
                [
                    'name' => 'Sniper',
                    'power' => '5',
                    'isUnique' => false,
                    'attackType' => CardHelper::RANGE_ATTACK,

                ],
                [
                    'name' => 'Sniper',
                    'power' => '6',
                    'isUnique' => false,
                    'attackType' => CardHelper::RANGE_ATTACK,

                ],

                //siege
                [
                    'name' => 'Trebuchet',
                    'power' => '6',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK,

                ],
                [
                    'name' => 'Trebuchet',
                    'power' => '6',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK,

                ],
                [
                    'name' => 'Ballista',
                    'power' => '6',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK,

                ],
                [
                    'name' => 'Ballista',
                    'power' => '6',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK,

                ],
                [
                    'name' => 'Siege tower',
                    'power' => '6',
                    'isUnique' => false,
                    'attackType' => CardHelper::SIEGE_ATTACK,

                ],

                //hero
                [
                    'name' => 'Melee General',
                    'power' => '10',
                    'isUnique' => true,
                    'attackType' => CardHelper::MELEE_ATTACK,

                ],
                [
                    'name' => 'Melee General',
                    'power' => '10',
                    'isUnique' => true,
                    'attackType' => CardHelper::MELEE_ATTACK,

                ],
                [
                    'name' => 'Range General',
                    'power' => '10',
                    'isUnique' => true,
                    'attackType' => CardHelper::RANGE_ATTACK,

                ],
                [
                    'name' => 'Range General',
                    'power' => '10',
                    'isUnique' => true,
                    'attackType' => CardHelper::RANGE_ATTACK,

                ],
            ]
        ]
    ];

    /**
     * @return array
     */
    public function getAbilities()
    {
        return $this->abilities;
    }
}