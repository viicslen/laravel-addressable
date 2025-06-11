<?php

namespace ViicSlen\Addressable;

use Illuminate\Support\Facades\Event;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ViicSlen\Addressable\Contracts\ValidatesAddress;
use ViicSlen\Addressable\Events\AddressSaved;
use ViicSlen\Addressable\Exceptions\AddressBook\InvalidAddressValidator;
use ViicSlen\Addressable\Listeners\ValidateAddress;
use ViicSlen\Addressable\Listeners\ValidateAddressQueued;

class AddressableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-addressable')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_addresses_table')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations();
            });
    }

    public function packageRegistered(): void
    {
        /** @var \ViicSlen\Addressable\Contracts\ValidatesAddress|null $validator */
        $validator = config('addressable.validation.default');

        if (! $validator) {
            return;
        }

        if (! class_exists($validator) || ! is_subclass_of($validator, ValidatesAddress::class)) {
            throw new InvalidAddressValidator($validator);
        }

        $this->app->bind(ValidatesAddress::class, new $validator);
    }

    public function packageBooted(): void
    {
        $listener = config('addressable.validation.queued', true)
            ? ValidateAddressQueued::class
            : ValidateAddress::class;

        Event::listen(AddressSaved::class, $listener);
    }
}
