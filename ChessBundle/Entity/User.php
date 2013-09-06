<?php

namespace Polcode\ChessBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="chess_user")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @UniqueEntity(fields="email", message="Email already taken")
 */
class User extends BaseUser
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
	 * 
	 * @var int
     */
    protected $id;
	
	/**
	 * @ORM\Column(type="string", length=100)
	 * 
	 * @var string
     */
    protected $username;
	
	/**
	 * @ORM\Column(type="string", length=100)
	 * 
     * @var string
     */
    protected $email;
	
	/** 
	 * @ORM\Column(type="string", length=32)
	 *
     * The salt to use for hashing
     *
     * @var string
     */
    protected $salt;
	
	/**
     * Encrypted password. Must be persisted.
     *
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=100)
	 * 
	 * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    protected $plainPassword;
	
	/**
     * @ORM\Column(type="datetime", nullable=true)
	 * 
	 * @var \DateTime
     */
    protected $lastLogin;
	
	/**
     * @ORM\Column(type="string", length=30)
	 * 
	 * @var array
     */
    protected $roles;
	
	public function __construct()
    {
        parent::__construct();
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
}