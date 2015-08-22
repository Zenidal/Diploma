<?php
namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Role;
class LoadRoleData implements FixtureInterface, OrderedFixtureInterface
{
    private $roles = [
        [
            'name' => 'ROLE_ADMIN',
            'users' => []
        ],
        [
            'name' => 'ROLE_GAMER',
            'users' => []
        ]
    ];

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->roles as $role) {
            $newRole = new Role();
            $newRole->setName($role['name']);
            $manager->persist($newRole);
        }
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}