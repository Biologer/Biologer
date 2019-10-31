<?php

namespace App\Http\Controllers\Api\My;

use App\Http\Resources\UserResource;

class ProfileController
{
    public function show()
    {
        return new UserResource(auth()->user());
    }
}
