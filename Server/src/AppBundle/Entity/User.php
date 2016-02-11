<?php

namespace AppBundle\Entity;

use AppBundle\Repository\RoleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * User
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
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
	private $wsId;

	/**
	 * @ORM\Column(type="string", length=100, unique=true)
	 */
	private $username;

	/**
	 * @ORM\Column(type="string", length=64)
	 */
	private $password;

	/**
	 * @ORM\Column(type="string", length=64, nullable=true)
	 */
	private $salt;

	/**
	 * @var Role
	 * @ORM\ManyToOne(targetEntity="Role", inversedBy="users",  cascade={"all"})
	 * @ORM\JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $role;

	/**
	 * @ORM\Column(name="is_active", type="boolean")
	 */
	private $isActive = true;

	/**
	 * @ORM\OneToOne(targetEntity="Token", mappedBy="user", cascade={"persist", "remove"})
	 */
	private $token;

	/**
	 * @ORM\OneToOne(targetEntity="Game", mappedBy="visitor", cascade={"persist", "remove"})
	 */
	private $visitedGame;

	/**
	 * @ORM\OneToOne(targetEntity="Game", mappedBy="creator", cascade={"persist", "remove"})
	 */
	private $createdGame;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->isActive = true;
		$this->salt = md5(uniqid(null, true));
	}

	/**
	 * Set username
	 *
	 * @param string $username
	 *
	 * @return User
	 */
	public function setUsername($username)
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * Get username
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Set password
	 *
	 * @param string $password
	 *
	 * @return User
	 */
	public function setPassword($password)
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * Get password
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Set isActive
	 *
	 * @param boolean $isActive
	 *
	 * @return User
	 */
	public function setIsActive($isActive)
	{
		$this->isActive = $isActive;

		return $this;
	}

	/**
	 * Get isActive
	 * @return boolean
	 */
	public function getIsActive()
	{
		return $this->isActive;
	}

	/**
	 * Set salt
	 *
	 * @param string $salt
	 *
	 * @return User
	 */
	public function setSalt($salt)
	{
		$this->salt = $salt;

		return $this;
	}

	/**
	 * Get salt
	 * @return string
	 */
	public function getSalt()
	{
		return $this->salt;
	}

	/**
	 * Get id
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set role
	 *
	 * @param Role $role
	 *
	 * @return User
	 */
	public function setRole(Role $role = null)
	{
		$this->role = $role;

		return $this;
	}

	/**
	 * Get role
	 * @return Role
	 */
	public function getRole()
	{
		return $this->role;
	}

	public function getRoleName()
	{
		return $this->getRole()->getName();
	}

	public function getRoles()
	{
		return [$this->role];
	}

	public function eraseCredentials()
	{
	}

	public function serialize()
	{
		return serialize(
			array(
				$this->id,
				$this->username,
				$this->password,
				// see section on salt below
				// $this->salt,
			)
		);
	}

	/** @see \Serializable::unserialize() */
	public function unserialize($serialized)
	{
		list (
			$this->id,
			$this->username,
			$this->password,
			// see section on salt below
			// $this->salt
			) = unserialize($serialized);
	}

	/**
	 * Set token
	 *
	 * @param \AppBundle\Entity\Token $token
	 *
	 * @return User
	 */
	public function setToken(\AppBundle\Entity\Token $token = null)
	{
		$this->token = $token;

		return $this;
	}

	/**
	 * Get token
	 * @return \AppBundle\Entity\Token
	 */
	public function getToken()
	{
		return $this->token;
	}

	/**
	 * Set visitedGame
	 *
	 * @param \AppBundle\Entity\Game $visitedGame
	 *
	 * @return User
	 */
	public function setVisitedGame(\AppBundle\Entity\Game $visitedGame = null)
	{
		$this->visitedGame = $visitedGame;

		return $this;
	}

	/**
	 * Get visitedGame
	 * @return \AppBundle\Entity\Game
	 */
	public function getVisitedGame()
	{
		return $this->visitedGame;
	}

	/**
	 * Set createdGame
	 *
	 * @param \AppBundle\Entity\Game $createdGame
	 *
	 * @return User
	 */
	public function setCreatedGame(\AppBundle\Entity\Game $createdGame = null)
	{
		$this->createdGame = $createdGame;

		return $this;
	}

	/**
	 * Get createdGame
	 * @return \AppBundle\Entity\Game
	 */
	public function getCreatedGame()
	{
		return $this->createdGame;
	}

    /**
     * Set wsId
     *
     * @param string $wsId
     * @return User
     */
    public function setWsId($wsId)
    {
        $this->wsId = $wsId;

        return $this;
    }

    /**
     * Get wsId
     *
     * @return string 
     */
    public function getWsId()
    {
        return $this->wsId;
    }
}
