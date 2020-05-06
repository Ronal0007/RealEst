<?php

namespace App\Providers;

use App\Events\PaymentEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\Backup\Events\BackupWasSuccessful;
use Spatie\Backup\Events\BackupZipWasCreated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        BackupZipWasCreated::class => [
            'App\Listeners\SendBackupListener'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
