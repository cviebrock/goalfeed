<?php

namespace App\Console\Commands\MLB;

use App\Events\Goal;
use App\Jobs\MessageSender;
use App\Message;
use Illuminate\Console\Command;

class sendTestEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:run';

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
     * @return mixed
     */
    public function handle()
    {
        //
		$message = new Message('wpg');
	    dispatch(new MessageSender($message));


    }
}
