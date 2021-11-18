<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\backend\ClassroomController;


class CheckInClassThirty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:CheckInClassThirty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Open classroom every 1 minute using cron job';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->ClassroomController = new ClassroomController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle(){
        return $this->ClassroomController->CheckInClassThirty();
    }
}
