<?php

namespace Polcode\ChessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Polcode\ChessBundle\Model\GameMaster;

class DefaultController extends Controller
{
    public function indexAction()
    {  
        $gm = new GameMaster();
        
        $pos = $gm->getValidMoves();
        
        return $this->render('PolcodeChessBundle:Default:index.html.twig', array('content' => $pos));
    }
}
