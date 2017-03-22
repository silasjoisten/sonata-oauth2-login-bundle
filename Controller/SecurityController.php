<?php

namespace Exozet\Oauth2LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function logoutAction()
    {
        $client = $this->get('exozet.google.authorization')->getClient();
        $client->revokeToken();

        $this->get('security.token_storage')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        return $this->redirect($this->generateUrl('exozet_oauth2_login_homepage'));
    }
}
