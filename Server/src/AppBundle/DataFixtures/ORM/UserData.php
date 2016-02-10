<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Form\Type\RoleType;

class UserData
{
	private $users = [
		[
			'username' => 'Admin',
			'password' => '123456',
			'isActive' => true,
			'roleName' => RoleType::ROLE_ADMIN,
		],
		[
			'username' => 'Zenidal',
			'password' => '123456',
			'isActive' => true,
			'roleName' => RoleType::ROLE_GAMER,
		],
		[
			'username' => 'Skeleos',
			'password' => '123456',
			'isActive' => true,
			'roleName' => RoleType::ROLE_GAMER,
		],
		[
			'username' => 'Gerald',
			'password' => '123456',
			'isActive' => true,
			'roleName' => RoleType::ROLE_GAMER,
		],
	];

	/**
	 * @return array
	 */
	public function getUsers()
	{
		return $this->users;
	}
}