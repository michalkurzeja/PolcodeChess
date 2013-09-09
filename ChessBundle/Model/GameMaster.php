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
        // $whites = array( new Pieces\Rook(3,2,true,1) );
        // $blacks = array( new Pieces\Pawn(3,5,false,1) );
        $whites = array(    new Pieces\Pawn(2,2,true,1), new Pieces\Bishop(3,1,true,1), new Pieces\Knight(2,1,true,1),
                            new Pieces\Rook(1,1,true,1), new Pieces\Queen(4,1,true,1), new Pieces\King(5,1,true,1) );
        $blacks = array(    new Pieces\Pawn(2,7,false,1), new Pieces\Bishop(3,8,false,1), new Pieces\Knight(2,8,false,1),
                            new Pieces\Rook(1,8,false,1), new Pieces\Queen(4,8,false,1), new Pieces\King(5,8,false,1) );
        $this->chessboard = new Chessboard($whites, $blacks);
    }
    
    public function getValidMoves()
    {
        $pieces = $this->chessboard->getPieces();
        $positions = '';
        
        foreach($pieces as &$piece) {
            $positions .= "{$piece}".PHP_EOL;
            $squares = $this->chessboard->getPieceMoveList($piece);
              foreach($squares as &$square) {
                  $positions .= "\t{$square}" . PHP_EOL;
              }
        }
        
        return $positions;
    }
}
