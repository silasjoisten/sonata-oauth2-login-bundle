<?php

namespace Exozet\Oauth2LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ExozetOauth2LoginBundle:Default:index.html.twig');
    }
}
