<?php
namespace  App\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SomeService
{
    private $router;

    public function __conctruct(UrlGeneratorInterface $router){
        $this->router = $router;
    }

    public function someMethod(){
        $url = $this->router->generate(
            'app_catalog_show'
        );
        return $url;
    }
}