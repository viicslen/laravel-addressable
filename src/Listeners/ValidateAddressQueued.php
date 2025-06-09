<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use ViicSlen\Addressable\Concerns\HandlesAddressValidationEvents;
use ViicSlen\Addressable\Events\AddressBook\AddressSaved;
use ViicSlen\Addressable\Facades\Addressable;

class ValidateAddressQueued implements ShouldQueue
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

        Addressable::validateAddress($event->address);
    }

    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(AddressSaved $event): bool
    {
        return $this->enabled && Addressable::shouldValidateAddress($event->address);
    }
}
