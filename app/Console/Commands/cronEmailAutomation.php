<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class cronEmailAutomation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:emailautomationhitter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email Automation';

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
        $page = getHitUrl("http://www.jimsinfo.org/microsite/lms/public/automation/email/hit/mail");
        /*$page = getHitUrl("http://www.jimsinfo.org/microsite/lms/public/api/api/inhouse/getwebsitehome");
        $page = getHitUrl("http://www.jimsinfo.org/microsite/lms/public/api/api/inhouse/getwebsiteinner");
        $page = getHitUrl("http://www.jimsinfo.org/microsite/lms/public/api/api/inhouse/getgoogle");
        $page = getHitUrl("http://www.jimsinfo.org/microsite/lms/public/api/api/inhouse/getfacebook");
        $page = getHitUrl("http://www.jimsinfo.org/microsite/lms/public/api/api/inhouse/getmbauniverse");
        $page = getHitUrl("http://www.jimsinfo.org/microsite/lms/public/api/api/inhouse/getadmissionform");
        $page = getHitUrl("http://www.jimsinfo.org/microsite/lms/public/api/api/inhouse/getipu");
        $page = getHitUrl("http://www.jimsinfo.org/microsite/lms/public/api/api/inhouse/getmocktest");*/
        echo "Success";
        Log::info("UpdateInfo: finished");
    }
}
