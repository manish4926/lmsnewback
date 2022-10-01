<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class cronCounsellorDailyCallMailAutomation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:emailcounsellordailycallautomation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Counsellor Daily Call Email Report Automation';

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
        Log::info("UpdateInfo: started");
        $page = getHitUrl("https://jimsinfo.org/microsite/lms/public/automation/report/daily/counsellor/report");
        $page = getHitUrl("https://jimsinfo.org/microsite/lms/public/automation/report/daily/counsellor/quality/report");
        echo "Success";
        Log::info("UpdateInfo: finished");
    }
}
