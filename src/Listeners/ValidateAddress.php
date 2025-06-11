<?php

namespace ViicSlen\Addressable\Listeners;

use ViicSlen\Addressable\Concerns\HandlesAddressValidationEvents;
use ViicSlen\Addressable\Events\AddressSaved;
use ViicSlen\Addressable\Facades\Addressable;

class ValidateAddress
{
    use HandlesAddressValidationEvents;

    /**
     * Handle the event.
     */
    public function handle(AddressSaved $event): void
    {
        if (! $this->enabled) {
            return;
        }

        if (! Addressable::shouldValidateAddress($event->address)) {
            return;
        }

        Addressable::validateAddress($event->address);
    }
}
