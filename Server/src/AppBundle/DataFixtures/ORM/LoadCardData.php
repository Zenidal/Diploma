<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Ability;
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
		$cardAbilityData = new CardAbilityData();
		foreach($cardAbilityData->getAbilities() as $ability) {
			if($ability['name']){
				$newAbility = new Ability();
				$newAbility->setName($ability['name']);
				$newAbility->setDescription($ability['description']);
				foreach($ability['cards'] as $card){
					$newCard = new Card();

					$newCard->setName($card['name']);
					$newCard->setPower($card['power']);
					$newCard->setTempPower($card['power']);
					$newCard->setIsUnique($card['isUnique']);
					$newCard->setAttackType($card['attackType']);
					$newCard->setAbility($newAbility);

					$manager->persist($newCard);
					$newAbility->addCard($newCard);
				}
			} else{
				foreach($ability['cards'] as $card){
					$newCard = new Card();

					$newCard->setName($card['name']);
					$newCard->setPower($card['power']);
					$newCard->setTempPower($card['power']);
					$newCard->setIsUnique($card['isUnique']);
					$newCard->setAttackType($card['attackType']);

					$manager->persist($newCard);
				}
			}
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