<?php

namespace AppBundle\Helper;

use AppBundle\Entity\Game;

class GameToArrayConverter
{
	public static function convertOne($game)
	{
		return $game instanceof Game ? [
			'id'      => $game->getId(),
			'name'    => $game->getName(),
			'creator' => !$game->getCreator() ? [] : [
				'id'       => $game->getCreator()->getId(),
				'username' => $game->getCreator()->getUsername(),
			],
			'visitor' => !$game->getVisitor() ? [] : [
				'id'       => $game->getVisitor()->getId(),
				'username' => $game->getVisitor()->getUsername(),
			],
		] : [];
	}

	public static function convertMany($games)
	{
		$gameArray = [];
		foreach($games as $game) {
			$gameArray[] = $game instanceof Game ? [
				'id'      => $game->getId(),
				'name'    => $game->getName(),
				'creator' => !$game->getCreator() ? [] : [
					'id'       => $game->getCreator()->getId(),
					'username' => $game->getCreator()->getUsername(),
				],
				'visitor' => !$game->getVisitor() ? [] : [
					'id'       => $game->getVisitor()->getId(),
					'username' => $game->getVisitor()->getUsername(),
				],
			] : [];
		}

		return $gameArray;
	}
}