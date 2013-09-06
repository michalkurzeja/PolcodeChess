<?php

namespace Polcode\ChessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="pieces")
 */
class King extends Piece
{   
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
        $vectors = array(   new Vector(1, 0), new Vector(1, 1), new Vector(0, 1), new Vector(-1, 1), 
                            new Vector(-1, 0), new Vector(-1, -1), new Vector(0, -1), new Vector(1, -1) );
    }

    /**
     * Set has_moved
     *
     * @param boolean $hasMoved
     * @return King
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
     * @return King
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
     * @return King
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
     * @return King
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
     * @return King
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
     * @return King
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