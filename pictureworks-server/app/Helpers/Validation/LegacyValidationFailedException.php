<?php

namespace App\Helpers\Validation;

use Exception;

enum LegacyValidationFailedExceptionCode
{
    const MISSING_ATTR = 1;
    const INVALID_PASS = 2;
}

class LegacyValidationFailedException extends Exception
{
    public static function fromMissingAttr($attr): LegacyValidationFailedException
    {
        $msg = "Missing key/value for \"{$attr}\"";
        return new LegacyValidationFailedException($msg, LegacyValidationFailedExceptionCode::MISSING_ATTR);
    }

    public static function fromInvalidPassword(): LegacyValidationFailedException
    {
        return new LegacyValidationFailedException('Invalid password', LegacyValidationFailedExceptionCode::INVALID_PASS);
    }
}
