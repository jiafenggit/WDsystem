<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Webdriver;

/**
 * Class startSys
 * @package App\Console\Commands
 * 系统启动命令 绑定
 */
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
        $this->info('选择启动哪个网站，1:知乎 2:爱股票');
        $id = $this->ask('你的选择是');
        if(in_array($id,[1,2])){
            $this->webdriver->run($id);
        }else{
            $this->error('不合法输入');
        }




    }
}
