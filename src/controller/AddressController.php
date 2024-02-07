<?php
namespace Controller;
use Controller\AbstractController;
use Model\Address;
class AddressController extends AbstractController{
    public function handleRequest (){
        $address = new Address();
    }

}