<?php

namespace ViicSlen\Addressable\Models\AddressBook;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Rinvex\Country\Country;
use ViicSlen\Addressable\Database\Factories\AddressFactory;
use ViicSlen\Addressable\Events\AddressBook\AddressSaved;
use ViicSlen\Addressable\Exceptions\AddressBook\ImmutableAddressException;

class Address extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'country',
        'country_name',
        'display_address',
        'google_maps_url',
    ];

    protected $dispatchesEvents = [
        'saved' => AddressSaved::class,
    ];

    protected static function booted(): void
    {
        static::updating(function (self $address) {
            if ($address->immutable && $this->isDirty()) {
                throw new ImmutableAddressException;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'primary' => 'boolean',
            'billing' => 'boolean',
            'shipping' => 'boolean',
            'immutable' => 'boolean',
            'validated' => 'boolean',
            'residential' => 'boolean',
            'valid' => 'boolean',
        ];
    }

    protected function country(): Attribute
    {
        return Attribute::get(
            fn ($value, array $attributes): ?Country => isset($attributes['country_code'])
                ? country(strtolower($attributes['country_code']))
                : null
        );
    }

    protected function countryName(): Attribute
    {
        return Attribute::get(
            fn ($value, array $attributes): ?string => isset($attributes['country_code'])
                ? country(strtolower($attributes['country_code']))->getName()
                : null
        );
    }

    protected function displayAddress(): Attribute
    {
        return Attribute::get(
            fn ($value, array $attributes): string => collect([
                $attributes['street1'],
                $attributes['street2'],
                $attributes['city'],
                collect([
                    $attributes['state'],
                    $attributes['postal_code'],
                ])->filter()->implode(' '),
                $this->country_name,
            ])->filter()->implode(', ')
        );
    }

    protected function googleMapsUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => ! empty($this->display_address) ? sprintf(
            'https://maps.google.com/maps?q=%s',
            urlencode($this->display_address),
        ) : null);
    }

    public static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
    }

    public function getTable()
    {
        return config('addressable.model.table', parent::getTable());
    }

    public function getConnectionName()
    {
        return config('addressable.model.connection', parent::getConnectionName());
    }

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
