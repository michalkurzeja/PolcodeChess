<?php

namespace Polcode\ChessBundle\Entity\Pieces;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Polcode\ChessBundle\Model\Vector;

/**
 * @ORM\Entity
 * @ORM\Table(name="pieces")
 */
class Pawn extends Piece
{
    /**
     * @ORM\Column(type="boolean")
     * 
     * @var boolean
     */
    protected $is_checking = false;
    
    /**
     * @ORM\Column(type="boolean")
     * 
     * @var boolean
     */
    protected $en_passable = false;
    
    /**
     * @ORM\Column(type="boolean")
     * 
     * @var boolean
     */
    protected $has_moved = false; 
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
    
    public function __construct()
    {
        parent::__construct();
        $this->setMultimove(false);
    }

    /**
     * @return array
     */
    public function getMoveVectors()
    { 
        $vectors = array( new Vector(0, 1, $this->getIsWhite()) );
    }
    
    /**
     * Set is_checking
     *
     * @param boolean $isChecking
     * @return Pawn
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
     * Set en_passable
     *
     * @param boolean $enPassable
     * @return Pawn
     */
    public function setEnPassable($enPassable)
    {
        $this->en_passable = $enPassable;
    
        return $this;
    }

    /**
     * Get en_passable
     *
     * @return boolean 
     */
    public function getEnPassable()
    {
        return $this->en_passable;
    }

    /**
     * Set has_moved
     *
     * @param boolean $hasMoved
     * @return Pawn
     */
    public function setHasMoved($hasMoved)
    {
        $this->has_moved = $hasMoved;
    
        return $this;
    }

    /**
     * Get has_moved
     *
     * @return boolean 
     */
    public function getHasMoved()
    {
        return $this->has_moved;
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
     * @return Pawn
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
     * @return Pawn
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
     * @return Pawn
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
     * @return Pawn
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
    /**
     * @var integer
     */
    protected $file;


    /**
     * Set file
     *
     * @param integer $file
     * @return Pawn
     */
    public function setFile($file)
    {
        $this->file = $file;
    
        return $this;
    }

    /**
     * Get file
     *
     * @return integer 
     */
    public function getFile()
    {
        return $this->file;
    }
}