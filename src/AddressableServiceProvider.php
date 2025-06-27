<?php

namespace ViicSlen\Addressable;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\InvokableValidationRule;
use Sokil\IsoCodes\IsoCodesFactory;
use Sokil\IsoCodes\TranslationDriver\SymfonyTranslationDriver;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ViicSlen\Addressable\Contracts\ValidatesAddress;
use ViicSlen\Addressable\Events\AddressSaved;
use ViicSlen\Addressable\Exceptions\InvalidAddressValidator;
use ViicSlen\Addressable\Listeners\ValidateAddress;
use ViicSlen\Addressable\Listeners\ValidateAddressQueued;
use ViicSlen\Addressable\Rules\CountryCode;
use ViicSlen\Addressable\Rules\CurrencyCode;

class AddressableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-addressable')
            ->hasConfigFile()
            ->hasMigration('create_addresses_table')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations();
            });
    }

    public function packageBooted(): void
    {
        $this->registerValidationRules();
        $this->registerEventListener();
    }

    public function packageRegistered(): void
    {
        $this->registerIsoCodeFactory();
        $this->registerAddressValidator();
    }

    protected function registerValidationRules(): void
    {
        Validator::extend('country', static function ($attribute, $value, $parameters, $validator) {
            return InvokableValidationRule::make(new CountryCode)
                ->setValidator($validator)
                ->passes($attribute, $value);
        });

        Validator::extend('currency', static function ($attribute, $value, $parameters, $validator) {
            return InvokableValidationRule::make(new CurrencyCode)
                ->setValidator($validator)
                ->passes($attribute, $value);
        });
    }

    protected function registerAddressValidator(): void
    {
        /** @var \ViicSlen\Addressable\Contracts\ValidatesAddress|null $validator */
        $validator = config('addressable.validation.validator');

        if (! $validator) {
            return;
        }

        if (! class_exists($validator) || ! is_subclass_of($validator, ValidatesAddress::class)) {
            throw new InvalidAddressValidator($validator);
        }

        $this->app->bind(ValidatesAddress::class, $validator);
    }

    protected function registerIsoCodeFactory(): void
    {
        $this->app->singleton(IsoCodesFactory::class, function () {
            $driver = new SymfonyTranslationDriver;
            $driver->setLocale(config('app.locale', 'en_US'));

            return new IsoCodesFactory(translationDriver: $driver);
        });
    }

    protected function registerEventListener(): void
    {
        if (! config('addressable.validation.enabled', true)) {
            return;
        }

        $listener = config('addressable.validation.queued', true)
            ? ValidateAddressQueued::class
            : ValidateAddress::class;

        Event::listen(AddressSaved::class, $listener);
    }
}
