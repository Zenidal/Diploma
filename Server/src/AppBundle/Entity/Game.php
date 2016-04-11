<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 * @ORM\Table(name="game")
 */
class Game
{
    function __construct()
    {
        $this->users = new ArrayCollection();
        $this->isAccepted = false;
        $this->isEnded = false;
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="games")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $json;

    /**
     * @ORM\Column(type="text", name="is_accepted", type="boolean")
     */
    private $isAccepted;

    /**
     * @ORM\Column(type="text", name="is_ended", type="boolean")
     */
    private $isEnded;

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
     * @return Game
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
     * Set json
     *
     * @param string $json
     * @return Game
     */
    public function setJson($json)
    {
        $this->json = $json;

        return $this;
    }

    /**
     * Get json
     *
     * @return string 
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * Add users
     *
     * @param \AppBundle\Entity\User $users
     * @return Game
     */
    public function addUser(\AppBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \AppBundle\Entity\User $users
     */
    public function removeUser(\AppBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set isAccepted
     *
     * @param boolean $isAccepted
     * @return Game
     */
    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    /**
     * Get isAccepted
     *
     * @return boolean 
     */
    public function getIsAccepted()
    {
        return $this->isAccepted;
    }

    /**
     * Set isEnded
     *
     * @param boolean $isEnded
     * @return Game
     */
    public function setIsEnded($isEnded)
    {
        $this->isEnded = $isEnded;

        return $this;
    }

    /**
     * Get isEnded
     *
     * @return boolean 
     */
    public function getIsEnded()
    {
        return $this->isEnded;
    }
}
