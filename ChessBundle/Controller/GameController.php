<?php

namespace Polcode\ChessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Polcode\ChessBundle\Exception\NotYourGameException;
use Polcode\ChessBundle\Exception\InvalidMoveException;
use Polcode\ChessBundle\Exception\InvalidFormatException;

class GameController extends Controller
{
    public function createAction()
    {
        $gm = $this->get('GameMaster');
        
        $game_id = $gm->createNewGame($this->getUser());
        
        return $this->redirect( $this->generateUrl('display', array('game_id' => $game_id)), 301 );
    }
    
    public function joinAction($game_id)
    {
        $gm = $this->get('GameMaster');
        
        $gm->joinGame($this->getUser(), $game_id);
        
        return $this->redirect( $this->generateUrl('display', array('game_id' => $game_id)), 301 );
    }
    
    public function displayAction($game_id)
    {
        $gm = $this->get('GameMaster');
        $user = $this->getUser();
        
        try {
            $player_white = $gm->loadGameState( $user, $game_id );
        } catch(NotYourGameException $e) {
            return new Response('You\'re not allowed to view this game!');
        }
        
        $color = $player_white ? 'white' : 'black';
        $my_turn = $gm->isMyTurn( $user ) ? 'yes' : 'no';
        
        $move_count = $gm->getMoveCount();
        
        $cont = $gm->getAllValidMoves();
        return $this->render('PolcodeChessBundle:Game:game.html.twig', array(   'game_id' => $game_id, 
                                                                                'move_count' => $move_count,
                                                                                'my_turn' => $my_turn,
                                                                                'color' => $color,
                                                                                'content' => $cont));
    }
    
    public function updateAction($game_id)
    {
        
        $gm = $this->get('GameMaster');
        
        try {
            $data = json_decode($this->get('request')->getContent());
            $update = $gm->getUpdate($this->getUser(), $game_id, $data->move_count);
        } catch(InvalidFormatException $e) {
            return new Response('Wrong format!', 404);
        } catch(NotYourGameException $e) {
            return new Response('Not your game!', 404);
        }
        
        return new JsonResponse($update);
    }

    public function moveAction($game_id)
    {        
        $gm = $this->get('GameMaster');
        
        try {            
            $data = json_decode($this->get('request')->getContent());
            $gm->movePiece($this->getUser(), $game_id, $data);
        } catch(InvalidFormatException $e) {
            return new Response('Wrong format!', 404);
        } catch(NotYourGameException $e) {
            return new Response('Not your game!', 404);
        } catch(InvalidMoveException $e) {
            return new Response('Invalid move!', 404);
        }

        return new Response('Move accepted!');
    }

    public function getPiecesAction($game_id)
    {
        $gm = $this->get('GameMaster');

        try {
            $pieces = $gm->getGamePieces( $this->getuser(), $game_id );
            return new JsonResponse($pieces);
        } catch(NotYourGameException $e) {
            return new Response('Not your game!', 404);
        }
    }    
}
