<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UserTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:test_comand {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description 999';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        dump($name);
        dump('run command success !');
        $user = User::all();
        dump($user);
        return Command::SUCCESS;
    }
}
