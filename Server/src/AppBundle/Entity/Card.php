<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * Card
 *
 * @ORM\Table(name="card")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CardRepository")
 */
class Card
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $power;

    /**
     * @ORM\Column(type="integer")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $attackType;


    /**
     * @ORM\Column(type="boolean")
     */
    private $isUnique;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ability;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set power
     *
     * @param integer $power
     * @return Card
     */
    public function setPower($power)
    {
        $this->power = $power;

        return $this;
    }

    /**
     * Get power
     *
     * @return integer 
     */
    public function getPower()
    {
        return $this->power;
    }

    /**
     * Set name
     *
     * @param integer $name
     * @return Card
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return integer 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set attackType
     *
     * @param integer $attackType
     * @return Card
     */
    public function setAttackType($attackType)
    {
        $this->attackType = $attackType;

        return $this;
    }

    /**
     * Get attackType
     *
     * @return integer 
     */
    public function getAttackType()
    {
        return $this->attackType;
    }

    /**
     * Set isUnique
     *
     * @param boolean $isUnique
     * @return Card
     */
    public function setIsUnique($isUnique)
    {
        $this->isUnique = $isUnique;

        return $this;
    }

    /**
     * Get isUnique
     *
     * @return boolean 
     */
    public function getIsUnique()
    {
        return $this->isUnique;
    }

    /**
     * Set ability
     *
     * @param integer $ability
     * @return Card
     */
    public function setAbility($ability)
    {
        $this->ability = $ability;

        return $this;
    }

    /**
     * Get ability
     *
     * @return integer 
     */
    public function getAbility()
    {
        return $this->ability;
    }
}
