<?php

namespace ViicSlen\Addressable\Concerns;

trait HandlesAddressValidationEvents
{
    protected bool $enabled = true;

    /**
     * Create a new listener instance.
     */
    public function __construct()
    {
        $this->enabled = config('addressable.validation.enabled', true);
    }
}
