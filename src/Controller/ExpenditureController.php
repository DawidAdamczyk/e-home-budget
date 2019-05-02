<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExpenditureController extends AbstractController
{
    public function list()
    {
        return $this->render('expenditure\list.html.twig');
    }
}