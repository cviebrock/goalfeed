<?php

namespace App;

class Message
{
	//
	public $channelId;

	public $messageType;
	public $payload;

	const MESSAGE_TYPE_GOAL = 'goal';


	public function __construct(Team $team = null) {
		if(!$team){
			$team = 'WPG';
		}

		$this->channelId  = 'goals';
		$this->messageType = self::MESSAGE_TYPE_GOAL;

		$this->payload = $team;

	}

	public function isSendable(){
		$sendable = true;

		if(!$this->payload){
			$sendable = false;
		}

		if(!$this->messageType){
			$sendable = false;
		}

		return $sendable;
	}

	public function toMessageJson(){

		$message = [
			'team' => $this->payload->team_code,
			'league' => $this->payload->league_id
		];

		return json_encode($message);
	}

}