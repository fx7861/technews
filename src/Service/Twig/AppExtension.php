<?php

namespace App\Service\Twig;


use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new \Twig_Filter('summary', function ($text) {
                $string = strip_tags($text);
                if (strlen($string) > 200) {
                    $stringCut = substr($string, 0, 200);
                    $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '...';
                }
                return $string;
            })
        ];
    }

}