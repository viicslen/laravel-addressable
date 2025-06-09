<?php

namespace ViicSlen\Addressable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ViicSlen\Addressable\Addressable
 */
class Addressable extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \ViicSlen\Addressable\Addressable::class;
    }
}
