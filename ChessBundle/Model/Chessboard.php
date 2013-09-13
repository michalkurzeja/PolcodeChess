<?php

namespace Polcode\ChessBundle\Model;

use Polcode\ChessBundle\Exception\OutOfBoardException;
use Polcode\ChessBundle\Model\Vector;
use Polcode\ChessBundle\Entity\Pieces\Piece;

class Chessboard
{
    private $whites;
    
    private $blacks;
    
    private $board;
    
    public function __construct($whites, $blacks)
    {
        $this->_init($whites, $blacks);
    }
    
    private function _init($whites, $blacks)
    {
        $this->whites = $whites;
        $this->blacks = $blacks;
        
        $this->board = array();
        
        foreach($whites as &$piece) {
            $this->board[$piece->getFile() . $piece->getRank()] = $piece;
        }

        foreach($blacks as &$piece) {
            $this->board[$piece->getFile() . $piece->getRank()] = $piece;
        }
    } 
    
    public function findPieceById($piece_id)
    {
        $pieces = $this->getPieces();
        
        foreach($pieces as $p) {
            if( $p->getId() == $piece_id ) {
                return $p;
            }
        }
        
        return null;
    }
    
    public function getPieces($color = null)
    {
        if($color == 'white') {
            return $this->whites;
        }
        
        if($color == 'black') {
            return $this->blacks;
        }
        
        return array_merge($this->whites, $this->blacks);
    }
    
    public function getKing($white)
    {
        if($white) {
            $pieces = $this->whites;
        } else {
            $pieces = $this->blacks;
        }
        
        foreach($pieces as $piece) {
            if( $piece instanceof Polcode\ChessBundle\Entity\Pieces\King ) {
                return $piece;
            }
        }
        
        /* virtually impossible... */
        return null;
    }
    
    public function isSquareWithinBoard(Vector $square)
    {
        if($square->getX() < 1 || $square->getX() > 8 ||$square->getY() < 1 ||$square->getY() > 8) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @param Polcode\ChessBundle\Model\Vector
     * @throws OutOfBoardException
     */
    public function getSquareContent(Vector $square)
    {
        if( !$this->isSquareWithinBoard($square) ) {
            throw new OutOfBoardException();
        }
        
        if( isset( $this->board[$square->getX() . $square->getY()] ) ) {
            return $this->board[$square->getX() . $square->getY()];
        }
        
        return null;
    }
    
    public function getPieceMoveList(Piece $piece)
    {
        $squares = array();
        
        if($piece->getMultimove()) {
            foreach($piece->getMoveVectors() as $vector) {
                $square = $piece->getCoordinates()->addVector($vector);
                try{
                    while( null === $this->getSquareContent($square) ) { /* add empty squares */
                        $squares[] = clone $square;
                        $square->setCoordinates($square->addVector($vector));
                    }
                    if( $this->getSquareContent($square)->getIsWhite() != $piece->getIsWhite() ) {
                        $squares[] = $square; /* add square occupied by another piece */
                    }
                } catch(OutOfBoardException $e) {} /* the end of the board has been reached */
            }
            
            return $squares;
        }
        
        foreach($piece->getMoveVectors() as $vector) {
            $square = $piece->getCoordinates()->addVector($vector);
            if( $this->isSquareWithinBoard($square) ) {
                $sq_content = $this->getSquareContent($square);
                
                if( !$sq_content || 
                        ( $sq_content->getIsWhite() != $piece->getIsWhite() &&
                        false == ($piece instanceof \Polcode\ChessBundle\Entity\Pieces\Pawn) ) ) {
                    $squares[] = $square;
                }
            }  
        }
        
        return $squares;
    }
}
