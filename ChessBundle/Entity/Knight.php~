<?php

namespace Polcode\ChessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="pieces")
 */
class Knight extends Piece
{
    /**
     * @ORM\Column(type="boolean")
     * 
     * @var boolean
     */
    protected $is_checking = false;
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $game_id;

    /**
     * @var boolean
     */
    protected $is_white;

    /**
     * @var integer
     */
    protected $rank;

    /**
     * @var integer
     */
    protected $row;


    /**
     * Set is_checking
     *
     * @param boolean $isChecking
     * @return Knight
     */
    public function setIsChecking($isChecking)
    {
        $this->is_checking = $isChecking;
    
        return $this;
    }

    /**
     * Get is_checking
     *
     * @return boolean 
     */
    public function getIsChecking()
    {
        return $this->is_checking;
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
     * Set game_id
     *
     * @param integer $gameId
     * @return Knight
     */
    public function setGameId($gameId)
    {
        $this->game_id = $gameId;
    
        return $this;
    }

    /**
     * Get game_id
     *
     * @return integer 
     */
    public function getGameId()
    {
        return $this->game_id;
    }

    /**
     * Set is_white
     *
     * @param boolean $isWhite
     * @return Knight
     */
    public function setIsWhite($isWhite)
    {
        $this->is_white = $isWhite;
    
        return $this;
    }

    /**
     * Get is_white
     *
     * @return boolean 
     */
    public function getIsWhite()
    {
        return $this->is_white;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return Knight
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    
        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set row
     *
     * @param integer $row
     * @return Knight
     */
    public function setRow($row)
    {
        $this->row = $row;
    
        return $this;
    }

    /**
     * Get row
     *
     * @return integer 
     */
    public function getRow()
    {
        return $this->row;
    }
}