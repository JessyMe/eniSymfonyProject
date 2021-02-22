<?php


namespace App\Twig;


use DateTime;
use Doctrine\DBAL\Types\DateTimeType;
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
            new TwigFunction('todayConvert', [$this, 'todayConvert'])
        ];
    }
    public function todayConvert()
    {



        $nowmoins = date('y-m-d', strtotime('- 1 month'));
        $nowmoinsConvert = DateTime::createFromFormat("y-m-d",$nowmoins,null);
        return $nowmoinsConvert;

    }
}