<?php


namespace App\Twig;


use App\Entity\Inscription;
use phpDocumentor\Reflection\Types\Integer;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{

    public function getInscriptions($sortieId): Integer
    {

    }
}