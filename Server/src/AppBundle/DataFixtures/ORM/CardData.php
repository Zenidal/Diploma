<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Helper\CardHelper;

class CardData
{
	private $cards = [
		//melee
		[
			'name'       => 'Soldier1',
			'power'      => '1',
			'isUnique'   => false,
			'attackType' => CardHelper::MELEE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'Soldier1',
			'power'      => '1',
			'isUnique'   => false,
			'attackType' => CardHelper::MELEE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'Infantryman',
			'power'      => '1',
			'isUnique'   => false,
			'attackType' => CardHelper::MELEE_ATTACK,
			'ability'    => CardHelper::BOND_ABILITY
		],
		[
			'name'       => 'Infantryman',
			'power'      => '1',
			'isUnique'   => false,
			'attackType' => CardHelper::MELEE_ATTACK,
			'ability'    => CardHelper::BOND_ABILITY
		],
		[
			'name'       => 'Infantryman',
			'power'      => '1',
			'isUnique'   => false,
			'attackType' => CardHelper::MELEE_ATTACK,
			'ability'    => CardHelper::BOND_ABILITY
		],
		[
			'name'       => 'Soldier2',
			'power'      => '2',
			'isUnique'   => false,
			'attackType' => CardHelper::MELEE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'Captain',
			'power'      => '4',
			'isUnique'   => false,
			'attackType' => CardHelper::MELEE_ATTACK,
			'ability'    => CardHelper::BOND_ABILITY
		],
		[
			'name'       => 'Captain',
			'power'      => '4',
			'isUnique'   => false,
			'attackType' => CardHelper::MELEE_ATTACK,
			'ability'    => CardHelper::BOND_ABILITY
		],
		[
			'name'       => 'Soldier5',
			'power'      => '5',
			'isUnique'   => false,
			'attackType' => CardHelper::MELEE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'Soldier5',
			'power'      => '5',
			'isUnique'   => false,
			'attackType' => CardHelper::MELEE_ATTACK,
			'ability'    => null
		],

		//range
		[
			'name'       => 'Archer4',
			'power'      => '4',
			'isUnique'   => false,
			'attackType' => CardHelper::RANGE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'Archer4',
			'power'      => '4',
			'isUnique'   => false,
			'attackType' => CardHelper::RANGE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'Hunter',
			'power'      => '5',
			'isUnique'   => false,
			'attackType' => CardHelper::RANGE_ATTACK,
			'ability'    => CardHelper::BOND_ABILITY
		],
		[
			'name'       => 'Hunter',
			'power'      => '5',
			'isUnique'   => false,
			'attackType' => CardHelper::RANGE_ATTACK,
			'ability'    => CardHelper::BOND_ABILITY
		],
		[
			'name'       => 'Hunter',
			'power'      => '5',
			'isUnique'   => false,
			'attackType' => CardHelper::RANGE_ATTACK,
			'ability'    => CardHelper::BOND_ABILITY
		],
		[
			'name'       => 'Archer5',
			'power'      => '5',
			'isUnique'   => false,
			'attackType' => CardHelper::RANGE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'Archer6',
			'power'      => '6',
			'isUnique'   => false,
			'attackType' => CardHelper::RANGE_ATTACK,
			'ability'    => null
		],

	    //siege
		[
			'name'       => 'Siege',
			'power'      => '1',
			'isUnique'   => false,
			'attackType' => CardHelper::SIEGE_ATTACK,
			'ability'    => CardHelper::MORALE_ABILITY
		],
		[
			'name'       => 'Siege',
			'power'      => '1',
			'isUnique'   => false,
			'attackType' => CardHelper::SIEGE_ATTACK,
			'ability'    => CardHelper::MORALE_ABILITY
		],
		[
			'name'       => 'Siege',
			'power'      => '1',
			'isUnique'   => false,
			'attackType' => CardHelper::SIEGE_ATTACK,
			'ability'    => CardHelper::MORALE_ABILITY
		],
		[
			'name'       => 'Siege medic',
			'power'      => '5',
			'isUnique'   => false,
			'attackType' => CardHelper::SIEGE_ATTACK,
			'ability'    => CardHelper::MEDIC_ABILITY
		],
		[
			'name'       => 'Trebuchet',
			'power'      => '6',
			'isUnique'   => false,
			'attackType' => CardHelper::SIEGE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'Trebuchet',
			'power'      => '6',
			'isUnique'   => false,
			'attackType' => CardHelper::SIEGE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'Ballista',
			'power'      => '6',
			'isUnique'   => false,
			'attackType' => CardHelper::SIEGE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'Ballista',
			'power'      => '6',
			'isUnique'   => false,
			'attackType' => CardHelper::SIEGE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'Siege tower',
			'power'      => '6',
			'isUnique'   => false,
			'attackType' => CardHelper::SIEGE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'Catapult',
			'power'      => '8',
			'isUnique'   => false,
			'attackType' => CardHelper::SIEGE_ATTACK,
			'ability'    => CardHelper::BOND_ABILITY
		],
		[
			'name'       => 'Catapult',
			'power'      => '8',
			'isUnique'   => false,
			'attackType' => CardHelper::SIEGE_ATTACK,
			'ability'    => CardHelper::BOND_ABILITY
		],

	    //hero
		[
			'name'       => 'MeleeHero10',
			'power'      => '10',
			'isUnique'   => true,
			'attackType' => CardHelper::MELEE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'MeleeHero10',
			'power'      => '10',
			'isUnique'   => true,
			'attackType' => CardHelper::MELEE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'RangeHero10',
			'power'      => '10',
			'isUnique'   => true,
			'attackType' => CardHelper::RANGE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'RangeHero10',
			'power'      => '10',
			'isUnique'   => true,
			'attackType' => CardHelper::RANGE_ATTACK,
			'ability'    => null
		],

	    //spy
	];

	/**
	 * @return array
	 */
	public function getCards()
	{
		return $this->cards;
	}
}