<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ability
 *
 * @ORM\Table(name="ability")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AbilityRepository")
 */
class Ability
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Card", mappedBy="ability", cascade={"persist", "remove"})
     */
    private $cards;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cards = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return Ability
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Ability
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add cards
     *
     * @param \AppBundle\Entity\Card $cards
     * @return Ability
     */
    public function addCard(\AppBundle\Entity\Card $cards)
    {
        $this->cards[] = $cards;

        return $this;
    }

    /**
     * Remove cards
     *
     * @param \AppBundle\Entity\Card $cards
     */
    public function removeCard(\AppBundle\Entity\Card $cards)
    {
        $this->cards->removeElement($cards);
    }

    /**
     * Get cards
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCards()
    {
        return $this->cards;
    }
}
