<?php
namespace App\Controller;

use App\Helper\amoCRMHelper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\DBAL\Driver\Connection;
use App\Entity\Leads;

class CreateOrderController extends Controller{
    public function __construct(amoCRMHelper $amoCrmHelper)
    {
        $this->amoCrmHelper = $amoCrmHelper;
    }
/**
     * @Route("/auth")
     */
public function auth():Response{
    //$this->amoCrmHelper->auth();
    $token = $this->amoCrmHelper->getAccessToken();
    $result = $this->amoCrmHelper->addLead($token);
    $result2 = $this->amoCrmHelper->addContact($token);
    return new Response();
}
}