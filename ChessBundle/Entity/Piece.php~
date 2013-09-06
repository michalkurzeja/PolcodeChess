<?php

namespace Polcode\ChessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="pieces")
 * @UniqueEntity(fields="id")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="piece_type", type="string")
 * @ORM\DiscriminatorMap({"piece" = "Piece", "pawn" = "Pawn", "bishop"="Bishop", "knight"="Knight", "rook"="Rook", "queen"="Queen", "king"="King"})
 */
class Piece
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
     * @ORM\Column(type="integer")
     * 
     * @var int
     */
    protected $game_id;
    
    /**
     * @ORM\Column(type="boolean")
     * 
     * @var boolean
     */
    protected $is_white;
    
    /**
     * @ORM\Column(type="integer")
     * 
     * @var int
     */
    protected $rank;
    
    /**
     * @ORM\Column(type="integer")
     * 
     * @var int
     */
    protected $row;

    function __construct($rank, $row, $is_white, $game_id)
    {
        $this->rank = $this->setRank($rank);
        $this->row = $this->setRow($row);
        $this->is_white = $this->setIsWhite($is_white);
        $this->game_id = $this->setGameId($game_id);
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
     * @return Piece
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
     * @return Piece
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
     * @return Piece
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
     * @return Piece
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
     * Set has_moved
     *
     * @param boolean $hasMoved
     * @return Piece
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
}