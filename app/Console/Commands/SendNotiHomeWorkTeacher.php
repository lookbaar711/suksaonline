<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\MemberNotification;
use App\Events\SendNotiFrontend;

use App\Http\Controllers\frontend\BuildHomeworkController;

class SendNotiHomeWorkTeacher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:SendHomeworkTeacher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Noti Checked to teacher every 1 minute using cron job';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->BuildHomeworkController = new BuildHomeworkController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->BuildHomeworkController->ConjobSendNotiTeacher();

    }
}
