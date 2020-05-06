<?php

namespace App\Listeners;

use App\Notifications\SendBackupFileNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\File;
use Spatie\Backup\Events\BackupZipWasCreated;

class SendBackupListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BackupZipWasCreated  $event
     * @return void
     */
    public function handle(BackupZipWasCreated $event)
    {
        User::find(1)->notify(new SendBackupFileNotification($event->pathToZip));
    }
}
