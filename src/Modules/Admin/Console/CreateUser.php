<?php

namespace Modules\Admin\Console;

use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
     * @return mixed
     */
    public function handle()
    {
        $email = $this->ask('Nhập email','johndoe@example.com');
        $password = $this->ask('Nhập password','secret');
        $name = $this->ask('Nhập họ tên','Peter Lê');
        User::factory()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            //['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
           // ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
