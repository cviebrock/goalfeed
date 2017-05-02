# Goalfeed - Backend

I don't have time to continue actively developing this, so I'm opening this up.

Laravel based back-end for the goalfeed service.

Most of the magic happens takes place within the commands which are located in app/Console/Commands and scheduled within app/Console/Kernel.php

To get up and running, copy .env.example to .env, run the migrations, configure pusher and cron the scheduler. See Laravel 5.3 docs for more details