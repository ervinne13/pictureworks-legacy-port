<?php

namespace Pictureworks;

use Illuminate\Support\Facades\Hash;

class AppKeyValidator
{
    public function validate($key)
    {
        // We use env directly instead of config since we're not gonna put a
        // default value in it anyway
        return Hash::check($key, env("LEGACY_APP_PASSWORD_HASH"));
    }
}