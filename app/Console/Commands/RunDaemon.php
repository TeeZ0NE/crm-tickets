<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Http\TicketBags\{MotherWhmcsDaemonLite,Billmgr};
class WhmcsDaemonLite
{
	use MotherWhmcsDaemonLite;
}
class BillManager{
	use Billmgr;
}

class RunDaemon extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daemon:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run daemon 4 receive tickets';

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
     * @return void
     */
    public function handle()
    {
	    $whmcs_services = (array)config('services_arr.whmcs_services');
	    foreach ($whmcs_services as $whmcs_service){
		    $WhmcsLite = new WhmcsDaemonLite($whmcs_service);
		    $WhmcsLite->getandStoreDataFromTicket();
		    flush();
	    }
	    $billMgr_services = (array)config('services_arr.billmgr_services');
	    foreach ($billMgr_services as $billMgr_service){
		    $billMgr = new BillManager($billMgr_service);
		    $billMgr->getAndStoreDataFromTicket();
		    flush();
	    }
    }
}
