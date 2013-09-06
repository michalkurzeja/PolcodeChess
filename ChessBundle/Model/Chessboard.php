<?php

namespace Polcode\ChessBundle\Model;

use Polcode\ChessBundle\Exception\OutOfBoardException;
use Polcode\ChessBundle\Model\Vector;
use Polcode\ChessBundle\Entity\Piece;

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
            $board[$piece->getRank() . $piece->getFile()] = $piece;
        }

        foreach($blacks as &$piece) {
            $board[$piece->getRank() . $piece->getFile()] = $piece;
        }
    } 
    
    public function isSquareWithinBoard(Vector $square)
    {
        if($vector[0] < 1 || $vector[0] > 8 || $vector[1] < 1 || $vector[1] > 8) {
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
        if( $this->isSquareWithinBoard($square) ) {
            throw new OutOfBoardException();
        }
        
        if( isset( $this->board[$square[0] . $square[1]] ) ) {
            return $this->board[$square[0] . $square[1]];
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
                        $squares[] = $square;
                        $square->setCoordinates($square->addVector($vector));
                    }
                    $squares[] = $square; /* add square occupied by another piece */
                } catch(OutOfBoardException $e) { /* the end of the board has been reached */ }
            }
            
            return $squares;
        }
        
        foreach($piece->getMoveVectors() as $vector) {
            $square = $piece->getCoordinates()->addVector($vector);
            if( $this->isSquareWithinBoard($square) ) {
                $squares[] = $square;
            }  
        }
        
        return $squares;
    }
}
