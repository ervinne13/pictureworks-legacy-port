<?php

namespace App\Providers\Pictureworks;

use App\Console\Commands\WriteCommentCommand;
use App\Helpers\User\Comment\UserCommentValidator;
use App\Helpers\User\Comment\UserCommentValidatorBehavior;
use App\Helpers\Validation\LegacyValidator;
use Illuminate\Support\ServiceProvider;

class UserCommentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserCommentValidator::class, function ($app) {
            $validator = $app->make(LegacyValidator::class);
            return new UserCommentValidator($validator, UserCommentValidatorBehavior::CHECKS_FOR_PASSWORD);
        });

        $this->app
            ->when(WriteCommentCommand::class)
            ->needs(UserCommentValidator::class)
            ->give(function ($app) {
                $validator = $app->make(LegacyValidator::class);
                return new UserCommentValidator($validator, UserCommentValidatorBehavior::IGNORES_PASSWORD);
            });
    }
}
