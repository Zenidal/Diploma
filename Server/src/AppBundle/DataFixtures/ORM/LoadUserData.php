<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Repository\RoleRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class LoadUserData implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{

	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * Load data fixtures with the passed EntityManager
	 *
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		/** @var EntityManager $em */
		$em = $this->container->get('doctrine')->getEntityManager();
		/** @var RoleRepository $roleRepository */
		$roleRepository = $em->getRepository('AppBundle:Role');

		$userData = new UserData();
		foreach($userData->getUsers() as $user) {
			$newUser = new User();
			/** @var PasswordEncoderInterface $encoder */
			$encoder = $this->container
				->get('security.encoder_factory')
				->getEncoder($newUser);
			$newUser->setUsername($user['username']);
			$newUser->setPassword($encoder->encodePassword($user['password'], $newUser->getSalt()));
			$newUser->setIsActive($user['isActive']);
			$newUser->setRole($roleRepository->findOneBy(['name' => $user['roleName']]));
			$manager->persist($newUser);
		}
		$manager->flush();
	}

	/**
	 * Get the order of this fixture
	 * @return integer
	 */
	public function getOrder()
	{
		return 2;
	}

	/**
	 * Sets the Container.
	 *
	 * @param ContainerInterface|null $container A ContainerInterface instance or null
	 *
	 * @api
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
}