<?php

namespace App\Jobs;

use App\Message;
use Pusher;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
		$pusher = new Pusher(env('PUSHER_KEY'), env('PUSHER_SECRET'), env('PUSHER_APP_ID'));

		$pusher->trigger($this->channel, $this->messageType, $this->messageToSend);
	}
}
