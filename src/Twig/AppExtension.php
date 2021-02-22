<?php


namespace App\Twig;


use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    /**
     * @inheritDoc
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('convert', [$this, 'convert'])
        ];
    }
    public function convert($dt)
    {
       $dateDepartTimeStamp = strtotime($dt);
       dump($dt);
       dump($dateDepartTimeStamp);

       return $datePlusOneMonth = date("d/m/y", strtotime('+ 1 month',$dateDepartTimeStamp));
    }
}