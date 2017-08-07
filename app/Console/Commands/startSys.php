<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Webdriver;
class startSys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'startSys:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '用于启动系统';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Webdriver $webdriver)
    {
        parent::__construct();
        $this->webdriver = $webdriver;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->webdriver->run();


    }
}
