<?php

namespace Polcode\ChessBundle\Entity\Pieces;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="pieces")
 */
class Rook extends Piece
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
    protected $file;

    public function __construct($file, $rank, $is_white, $game_id)
    {
        parent::__construct($file, $rank, $is_white, $game_id);
        $this   ->setMultimove(true);
    }

    /**
     * @return array
     */
    public function getMoveVectors()
    { 
        return array( new Vector(1, 0), new Vector(0, 1) );
    }

    /**
     * Set is_checking
     *
     * @param boolean $isChecking
     * @return Rook
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
     * @return Rook
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
     * @return Rook
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
     * @return Rook
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
     * Set file
     *
     * @param integer $file
     * @return Rook
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