<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Card;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCardData implements FixtureInterface, OrderedFixtureInterface
{
	/**
	 * Load data fixtures with the passed EntityManager
	 *
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$cardData = new CardData();
		foreach($cardData->getCards() as $card) {
			$newCard = new Card();

			$newCard->setName($card['name']);
			$newCard->setPower($card['power']);
			$newCard->setIsUnique($card['isUnique']);
			$newCard->setAttackType($card['attackType']);
			$newCard->setAbility($card['ability']);

			$manager->persist($newCard);
		}
		$manager->flush();
	}

	/**
	 * Get the order of this fixture
	 * @return integer
	 */
	public function getOrder()
	{
		return 3;
	}
}