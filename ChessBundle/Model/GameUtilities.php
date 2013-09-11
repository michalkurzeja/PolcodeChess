<?php

namespace Polcode\ChessBundle\Model;

class GameUtilities
{
    public function getPieceArray($piece, $moves)
    {
        foreach($moves as $index => $square) {
            $moves[$index] = $square->toArray();
        }
        
        return array(
            'classname' => $piece->getPieceName(),
            'file' => $piece->getFile(),
            'rank' => $piece->getRank(),
            'is_white' => $piece->getIsWhite(),
            'moves' => $moves
        );
        
    }
    
    public function getAllPiecesArray($chessboard, $gm)
    {
        $pieces = $chessboard->getPieces();
        $pieces_array = array();
        
        foreach($pieces as $piece) {
            $pieces_array[ $piece->getId() ] = $this->getPieceArray($piece, $gm->getValidMoves($piece));
        }
        
        return $pieces_array;
    }
    
    public function getUserGameById($user, $game_id)
    {
        foreach($user->getAllGames() as $game) {
            if($game->getId() == $game_id) {
                return $game;
            }
        }
        
        throw new NotYourGameException();
    }
    
    
}
