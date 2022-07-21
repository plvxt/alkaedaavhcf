<?php

namespace alkaedaav\FormAPI;

use pocketmine\form\Form;
use pocketmine\Player;

abstract class FormData implements Form {

	/** @var array */
	protected $data = [];

	/** @var Callable */
	private $callable;

	/**
	 * FormData Constructor.
	 * @param Callable
	 */
	public function __construct(?callable $callable){
		$this->callable = $callable;
	}

	public function sendToPLayer(Player $player) : void {
		$player->sendForm($this);
	}

	public function getCallable() : ?callable {
		return $this->callable;
	}

	public function setCallable(?callable $callable){
		$this->callable = $callable;
	}

	public function handleResponse(Player $player, $data) : void {
		$this->processDataForm($data);
		$callable = $this->getCallable();
		if($callable !== null){
			$callable($player, $data);
		}
	}

	public function processDataForm(&$data) : void {
		
	}

	public function jsonSerialize(){
		return $this->data;
	}
}







