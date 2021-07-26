<?php
namespace App\Controller;

use App\Helper\amoCRMHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Driver\Connection;
use App\Entity\Leads;
class LuckyController extends AbstractController{
    /**
     * @Route("/lucky/number")
     */
    public function number(){
        $number = random_int(0,100);

        return $this->render("lucky/number.html.twig", array(
            'number' => $number
        ));
    }
    
    
}
