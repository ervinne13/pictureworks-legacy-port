<?php

namespace App\Helpers\Validation;

use App\Helpers\Validation\LegacyValidationFailedException;
use Pictureworks\AppKeyValidator;

class LegacyValidator
{
    public function __construct(
        protected AppKeyValidator $appKeyValidator
    ) {
    }

    /**
     * The original requests will send out erros one at a time. Let's emulate that behavior here.
     */
    public function assertAttrComplete($body, $attrs)
    {
        foreach ($attrs as $attr) {
            if (!isset($body[$attr]) || !$body[$attr] || !$attr) {
                throw LegacyValidationFailedException::fromMissingAttr($attr);
            }
        }
    }

    /**
     * Note that using policies does not seem to be a good idea here.
     * Policies and Request@authorized are both 403s, meaning that they
     * are usually for people who are authenticated, but are not authorized.
     * 
     * Not having a password or and incorrect one sounds like an authentication
     * problem and not authorization to me. Let's do a custom one for it 
     */
    public function assertPasswordCorrect($password)
    {
        if (!$this->appKeyValidator->validate($password)) {
            throw LegacyValidationFailedException::fromInvalidPassword();
        }
    }
}
