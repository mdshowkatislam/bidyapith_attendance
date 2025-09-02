<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Attribute\Argument;

class MultipleController extends Command
{
   protected $signature = 'make:multiple-controllers 
                            {controller1} 
                            {controller2} 
                            {controller3}';

    protected $description = 'Create three controllers at once';

    public function handle()
    {
        $controllers = [
            $this->argument('controller1'),
            $this->argument('controller2'),
            $this->argument('controller3'),
        ];

        foreach ($controllers as $controller) {
            Artisan::call('make:controller', [
                'name' => $controller
            ]);

            $this->info("Controller {$controller} created successfully.");
        }
    }
}
