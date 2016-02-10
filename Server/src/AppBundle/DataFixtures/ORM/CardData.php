<?php

namespace AppBundle\DataFixtures\ORM;

class CardData
{
	const MELEE_ATTACK = 1;
	const RANGE_ATTACK = 2;
	const SIEGE_ATTACK = 3;
	const BOND_ABILITY = 'bond';
	const MORALE_ABILITY = 'morale';

	private $cards = [
		[
			'name'       => '',
			'power'      => '',
			'isUnique'   => false,
			'attackType' => '',
			'ability'    => null
		],
		[
			'name'       => 'Soldier',
			'power'      => '1',
			'isUnique'   => false,
			'attackType' => self::MELEE_ATTACK,
			'ability'    => null
		],
		[
			'name'       => 'Infantryman',
			'power'      => '1',
			'isUnique'   => false,
			'attackType' => self::MELEE_ATTACK,
			'ability'    => self::BOND_ABILITY
		],
		[
			'name'       => 'Infantryman',
			'power'      => '1',
			'isUnique'   => false,
			'attackType' => self::MELEE_ATTACK,
			'ability'    => self::BOND_ABILITY
		],
		[
			'name'       => 'Infantryman',
			'power'      => '1',
			'isUnique'   => false,
			'attackType' => self::MELEE_ATTACK,
			'ability'    => self::BOND_ABILITY
		],
		[
			'name'       => '',
			'power'      => '',
			'isUnique'   => false,
			'attackType' => '',
			'ability'    => null
		],

	];

	/**
	 * @return array
	 */
	public function getCards()
	{
		return $this->cards;
	}
}