<?php

namespace ViicSlen\Addressable\Events\AddressBook;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddressSaved
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public \ViicSlen\Addressable\Models\Address $address
    ) {}
}
