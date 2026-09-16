<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;


#[Signature('domain:make:domain')]
#[Description('Make a new domain')]
class domainMakerCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $domain = $this->argument('domain'); 
        } catch (\Exception $e) {
            $this->error($e->getMessage()); 
            return 1; 
        }
    }


    protected function getArguments() 
    {
        return [
            ['domain', InputArgument::REQUIRED, 'name of domain'],
        ];
    }
}
