<?php

namespace Modules\Admin\Console;

use Illuminate\Console\Command;

class GenerateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database {--host=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $arrayIP = [];
        $this->warn("Command create database MSYQL 8.0");
        $this->info("CREATE DATABASE IF NOT EXISTS `" . env('DB_DATABASE') . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
        $this->info("CREATE USER " . env('DB_USERNAME') . "@" . env('DB_HOST') . " IDENTIFIED BY '" . env('DB_PASSWORD') . "';");
        $this->info("GRANT ALL PRIVILEGES ON " . env('DB_DATABASE') . ".*   TO " . env('DB_USERNAME') . "@" . env('DB_HOST') . "  WITH GRANT OPTION;");
        $this->info("ALTER USER " . env('DB_USERNAME') . "@" . env('DB_HOST') . " IDENTIFIED WITH mysql_native_password BY '" . env('DB_PASSWORD') . "';");
        $this->info("mysqldump -u" . env('DB_USERNAME') . " -p" . env('DB_PASSWORD') . " --opt --default-character-set=utf8 --add-drop-table " . env('DB_DATABASE') . " > " . storage_path('logs') . "/" . env('DB_DATABASE') . ".sql");
        foreach ($arrayIP as $ip) {
            $this->info("scp -P 9900 " . storage_path('logs') . "/" . env('DB_DATABASE') . ".sql root@$ip:/home/");
            $this->info("scp -P 9900 " . storage_path('database/json') . "/datajson.text root@$ip:/home/");
            $this->info("scp -P 9900 /Volumes/Data/data/ root@$ip:/home/");
        }
        $this->info("rsync -av --exclude={'*.log','*.text','*.cfn','*.xml','*.index','*.cls','*.sql','*.slack','*.allow'} india/ usa");
        $this->info("rsync -avz -e 'ssh -p 9900' --exclude={'*.log','*.text','*.cfn','*.xml','*.index','*.cls','*.sql'} --progress root@178.128.116.251:/var/www/singapore/ /var/www/source/");
        return 0;
    }
}
