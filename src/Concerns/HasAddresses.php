<?php

namespace ViicSlen\Addressable\Concerns\AddressBook;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @used-by \Illuminate\Database\Eloquent\Model
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasAddresses
{
    public function addresses(): MorphMany
    {
        return $this->morphMany(\ViicSlen\Addressable\Models\Address::class, 'addressable');
    }

    public function billingAddresses(): MorphMany
    {
        return $this->addresses()->where('billing', true);
    }

    public function shippingAddresses(): MorphMany
    {
        return $this->addresses()->where('shipping', true);
    }

    public function primaryAddress(): MorphOne
    {
        return $this->addresses()->one()->ofMany($this->getPrimaryAggregateColumns(), fn (Builder $query) => $query->where('primary', true));
    }

    public function primaryBillingAddress(): MorphOne
    {
        return $this->billingAddresses()->one()->ofMany($this->getPrimaryAggregateColumns(), fn (Builder $query) => $query->where('primary', true));
    }

    public function primaryShippingAddress(): MorphOne
    {
        return $this->shippingAddresses()->one()->ofMany($this->getPrimaryAggregateColumns(), fn (Builder $query) => $query->where('primary', true));
    }

    protected function getPrimaryAggregateColumns(): array
    {
        return ['created_at' => 'max'];
    }
}
