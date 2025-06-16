<?php

namespace ViicSlen\Addressable\Concerns;

use Closure;
use ViicSlen\Addressable\Exceptions\ImmutableAddressException;

trait HasImmutability
{
    protected static bool $disableImmutability = false;

    public static function disableImmutability(): void
    {
        self::$disableImmutability = true;
    }

    public static function enableImmutability(): void
    {
        self::$disableImmutability = false;
    }

    public static function withoutImmutability(Closure|callable $callback): mixed
    {
        try {
            self::disableImmutability();

            return $callback();
        } finally {
            self::enableImmutability();
        }
    }

    protected function bootHasImmutability(): void
    {
        static::updating(function (self $address) {
            if (self::$disableImmutability === false && $address->immutable && $address->isDirty()) {
                throw new ImmutableAddressException;
            }
        });
    }
}
