<?php

namespace Polcode\ChessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Polcode\ChessBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="games")
 */
class Game
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * 
     * @var User
     */
    protected $white;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * 
     * @var User
     */
    protected $black;
    
    /**
     * @ORM\Column(type="boolean")
     * 
     * @var boolean
     */
    protected $white_turn;
    
    /**
     * @ORM\Column(type="datetime")
     * 
     * @var datetime
     */
    protected $start_time;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @var datetime
     */
    protected $end_time;
}
