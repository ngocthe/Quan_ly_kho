<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeCRUD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make CRUD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = $this->argument('model');
        $this->call('make:controller', [
            'name' => 'Api\Business' . '\\' . $model . 'Controller',
            '--api' => true,
        ]);
        $this->call('make:model', [
            'name' => 'App\Models\\' . $model,
            '--migration' => true,
        ]);
        $this->call('make:request', [
            'name' => $model . 'Request'
        ]);
        $this->call('make:resource', [
            'name' => $model . 'Resource'
        ]);
    }
}
