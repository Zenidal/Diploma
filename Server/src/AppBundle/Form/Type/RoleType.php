<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class RoleType extends AbstractType
{
    const ROLE_GAMER = 'ROLE_GAMER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    public static function getRoleValues()
    {
        return [
            self::ROLE_GAMER => 'ROLE_GAMER',
            self::ROLE_ADMIN => 'ROLE_ADMIN',
        ];
    }

    public function getName()
    {
        return 'role';
    }
}