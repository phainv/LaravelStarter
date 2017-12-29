<?php

namespace App\Contracts;

interface CardGenerator
{
    /**
     * Generate an unique code.
     *
     * @return int
     */
    public function generate();
}
