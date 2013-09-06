<?php

namespace Polcode\ChessBundle\Model;

use Polcode\ChessBundle\Model\Chessboard;
use Polcode\ChessBundle\Entity\Pieces;

class GameMaster
{
    private $chessboard;
    
    public function __construct()
    {
        $this->_init();
    }
    
    private function _init()
    {
        $whites = array( new Pawn() );
        $this->chessboard = new Chessboard();
    }
}
