<?php

namespace Mintreu\LaravelTwist\Commands;

use Illuminate\Console\Command;

class LaravelTwistCommand extends Command
{
    public $signature = 'laravel-twist';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
