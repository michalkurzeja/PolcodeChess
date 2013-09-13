<?php

namespace Polcode\ChessBundle\Model\Rules;

use Polcode\ChessBundle\Entity\Pieces;

class EnPassantRule extends RuleAbstract
{
    private $check_vector = null;
    
    public function __counstruct($chessboard, $game)
    {
        parent::construct($chessboard, $game);
        
        $this->check_vector = array(    new Vector(1, 0), new Vector(1, 1), new Vector(0, 1), new Vector(-1, 1), 
                                        new Vector(-1, 0), new Vector(-1, -1), new Vector(0, -1), new Vector(1, -1) );
    }
    
    public function checkRule(&$args)
    {
        $piece = $args['piece'];
        $squares = &$args['squares'];
        
        
    }
     
    public function canMoveFromSquare($piece)
    {
         foreach($this->check_vector as $vector) {
            $square = $piece->getCoordinates()->addVector($vector);
            try{
                while( null === $this->getSquareContent($square) ) { /* add empty squares */
                    $square->setCoordinates($square->addVector($vector));
                }
                
                $found_piece = $this->getSquareContent($square);
                
                if( $found_piece->getIsWhite() != $piece->getIsWhite() ) {
                    $this->isCheckingKing( $found_piece, $piece->getIsWhite(), $piece );
                }
            } catch(OutOfBoardException $e) {} /* the end of the board has been reached */
         }
    }
    
    /* ignored piece - piece that won't be taken into calculations (in case it moves from it's square) */
    public function isCheckingKing($piece, $white, $ignored_piece = null)
    {
        if( $piece instanceof Pieces\Pawn || $piece instanceof Pieces\Knight || $piece instanceof Pieces\King ) {
            return false;
        }
        
        $king  =$this->chessboard->getKing( $white );
        
        foreach($piece->getMoveVectors() as $vector) {
            
        }
    }
}