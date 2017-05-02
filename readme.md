# Goalfeed - Backend

I don't have time to continue actively developing this, so I'm opening this up.

While you could build a standalone app that would repeatedly poll in this manner, I'm not sure how kindly the NHL would take to all the added frequent requests. By doing it from a single source and then announcing goals to all listeners via pusher, it reduces the traffic to the NHL gamecentre scoreboards

Laravel based back-end for the goalfeed service.

Most of the magic happens takes place within the commands which are located in app/Console/Commands and scheduled within app/Console/Kernel.php

To get up and running, copy .env.example to .env, run the migrations, configure pusher and cron the scheduler. See Laravel 5.3 docs for more details