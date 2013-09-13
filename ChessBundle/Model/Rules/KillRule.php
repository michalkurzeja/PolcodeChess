<?php

namespace Polcode\ChessBundle\Model\Rules;

use Polcode\ChessBundle\Entity\Pieces;

class KillRule extends RuleAbstract
{
    public function checkRule(&$args)
    {
        $piece = $args['piece'];
        $squares = $args['squares'];
        
        /* check if $piece is a Pawn */
        if( !($piece instanceof Pieces\Pawn) ) {
            return;
        }
        
        $kill_vector = $piece->getMoveVectors();
    }
}
