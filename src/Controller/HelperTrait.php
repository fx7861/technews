<?php

namespace App\Controller;


use Behat\Transliterator\Transliterator;

trait HelperTrait
{
    public function slugify($text) {
        return  Transliterator::transliterate($text);
    }
}