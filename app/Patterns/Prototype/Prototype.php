<?php


namespace App\Patterns\Prototype;

interface Prototype
{
    public function clone(): self;
}
