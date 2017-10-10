<?php

namespace App\Jobs;

use App\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Pusher;


class MessageSender implements ShouldQueue
{

    use InteractsWithQueue, Queueable, SerializesModels;

    protected $messageToSend;

    protected $messageType;

    protected $channel;

    /**
     * Create a new job instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->channel = $message->channelId;
        $this->messageType = $message->messageType;
        $this->messageToSend = $message->toMessageJson();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendPusher();
        $this->sendSlanger();
    }

    private function sendPusher()
    {
        $pusher = new Pusher(env('PUSHER_KEY'), env('PUSHER_SECRET'), env('PUSHER_APP_ID'));

        $pusher->trigger($this->channel, $this->messageType, $this->messageToSend);
    }

    private function sendSlanger()
    {
        $pusher = new Pusher(env('SLANGER_KEY'), env('SLANGER_SECRET'), env('SLANGER_APP_ID'), [],
            env('SLANGER_APP_HOST'), 4567);

        $pusher->trigger($this->channel, $this->messageType, $this->messageToSend);
    }
}
