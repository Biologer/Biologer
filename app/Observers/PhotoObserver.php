<?php

namespace App\Observers;

use App\Photo;

class PhotoObserver
{
    /**
     * Listen to the Photo deleted event.
     *
     * @param  \App\Photo  $user
     * @return void
     */
    public function deleted(Photo $photo)
    {
        $photo->removeFile();
    }
}
