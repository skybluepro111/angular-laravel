<?php

namespace App\Models;

class AdSet
{
    private $ads;

    public function __construct(array $ads) {
        $this->ads = $ads;
    }

    public function getAll()
    {
        return $this->ads;
    }
}