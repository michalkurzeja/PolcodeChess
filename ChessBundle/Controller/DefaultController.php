<?php

namespace Polcode\ChessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $chess;
        return $this->render('PolcodeChessBundle:Default:index.html.twig', array('name' => $name));
    }
}
