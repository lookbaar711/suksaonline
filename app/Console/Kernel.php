<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\OpenClassRoom::class,
        Commands\CloseClassRoom::class,
        Commands\QueueSendEmail::class,
        Commands\CheckPrivateClassRoom::class,
        Commands\CheckClassRoom::class,
        Commands\SetOfflineStatus::class,
        Commands\OpenClassRoomRealTime::class,
        Commands\CheckPrivateClassRoomRealTime::class,
        Commands\SendEmailChat::class,
        Commands\GetBankTransection::class,
        Commands\SetAutoApproveTopupCoins::class,
        Commands\GetCoinsCourse::class,
        Commands\SetCancelCourse::class,
        Commands\PostTestQuestions::class,
        Commands\UpdateClassRoomLink::class,
        Commands\SendNotiHomeWorkTeacher::class,
        Commands\SendNotiHomeWorkTeacherQ::class,
        Commands\ConjobUpdateStatusHomework::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cron:OpenClassRoom')->everyMinute();
        $schedule->command('cron:SendNotiOpenClassRoom')->everyMinute();
        $schedule->command('cron:CloseClassRoom')->everyMinute();
        $schedule->command('cron:QueueSendEmail')->everyMinute();
        $schedule->command('cron:CheckPrivateClassRoom')->everyMinute();
        $schedule->command('cron:CheckClassRoom')->everyMinute();
        $schedule->command('cron:SetOfflineStatus')->everyMinute();
        $schedule->command('cron:OpenClassRoomRealTime')->everyMinute();
        $schedule->command('cron:CheckPrivateClassRoomRealTime')->everyMinute();
        $schedule->command('cron:SendEmailChat')->everyMinute();
        $schedule->command('cron:GetBankTransection')->everyMinute();
        $schedule->command('cron:SetAutoApproveTopupCoins')->everyMinute();
        $schedule->command('cron:GetCoinsCourse ')->everyMinute();
        $schedule->command('cron:SetCancelCourse ')->everyMinute();
        $schedule->command('cron:PostTestQuestions')->everyMinute();
        $schedule->command('cron:UpdateClassRoomLink')->everyMinute();
        $schedule->command('cron:CheckInClassFifteen')->everyMinute();
        $schedule->command('cron:CheckInClassFifteenQueue')->everyMinute();
        $schedule->command('cron:CheckInClassThirty')->everyMinute();
        $schedule->command('cron:CheckInClassThirtyQueue')->everyMinute();
        $schedule->command('cron:CheckInClassFortyfive')->everyMinute();
        $schedule->command('cron:CheckInClassFortyfiveQueue')->everyMinute();
        $schedule->command('cron:CheckInClassSixty')->everyMinute();
        $schedule->command('cron:CheckInClassSixtyQueue')->everyMinute();
        $schedule->command('cron:updateClassRoomLinkMember')->everyMinute();
        $schedule->command('cron:SendHomeworkTeacher')->dailyAt('03:03');
        $schedule->command('cron:SendHomeworkTeacherQ')->everyMinute();
        $schedule->command('cron:ConjobUpdateStatusHomework')->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
