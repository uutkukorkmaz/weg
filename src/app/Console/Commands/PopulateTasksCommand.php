<?php

namespace App\Console\Commands;


use App\Contracts\TaskProvider;
use App\Models\Provider;
use App\Models\Task;
use Illuminate\Console\Command;

class PopulateTasksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-tasks {provider?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates tasks for given or all providers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $provider = $this->argument('provider');

        $counter = fn() => Task::count();
        if(!$provider){
            // Populate all providers
            Provider::active()->get()->each(function(Provider $provider){
                $this->populate($provider);
            });

            $this->info("System has now {$counter()} tasks in total");
            return;
        }

        $provider = \App\Models\Provider::from($provider);
        if(!$provider){
            $this->error("Provider {$provider} not found");
            return;
        }

        $this->populate($provider);
        $this->info("System has now {$provider->tasks()->count()} tasks for {$provider->name}, {$counter()} in total");
    }

    private function populate(Provider $provider)
    {
        $this->info("Populating tasks for {$provider->name} [ ".get_class($provider->instance)." ]");
        $provider->tasks()->createMany($provider->instance->retrieveTasks()->toArray());
    }
}
