<?php

namespace Polcode\ChessBundle\Model\Rules;

use Polcode\ChessBundle\Entity\Pieces;
use Polcode\ChessBundle\Exception\OutOfBoardException;

class PawnKillRule extends RuleAbstract
{
    public function checkRule(&$args)
    {
        $piece = $args['piece'];
        $squares = &$args['squares'];
        
        /* check if $piece is a Pawn */
        if( !($piece instanceof Pieces\Pawn) ) {
            return;
        }
        
        $piece_coords = $piece->getCoordinates();
        
        $move_vector = $piece->getMoveVectors();
        $kill_vector = $move_vector[0]->setX( $move_vector[0]->getX() - 1 );
        
        $target = null;
        
        $kill_square = $piece_coords->addVector( $kill_vector );
        
        try {
            $target = $this->chessboard->getSquareContent( $kill_square );

            if( $target && $target->getIsWhite() != $piece->getIsWhite() ) {
                $squares[] = $kill_square;
            }
        } catch(OutOfBoardException $e) {}


        $kill_vector->setX( $kill_vector->getX() + 2 );
        
        $kill_square = $piece_coords->addVector( $kill_vector );
        
        try {
            $target = $this->chessboard->getSquareContent( $kill_square );

            if( $target && $target->getIsWhite() != $piece->getIsWhite() ) {
                $squares[] = $piece_coords->addVector( $kill_vector );
            }
        } catch(OutOfBoardException $e) {}
    }
}
