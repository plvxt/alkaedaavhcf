<?php

namespace alkaedaav\crate;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat as TE;
use pocketmine\level\particle\FloatingTextParticle;

class Crate {
	
	/** @var String */
	protected $name;

    /** @var String */
	protected $block;

	/** @var String */
	protected $key;

    /** @var String */
	protected $keyName;

    /** @var String */
	protected $nameFormat;
	
	/** @var Array[] */
	protected $items = [];

    /** @var Array[] */
	protected $data = [];

    /** @var Array[] */
	protected $position = [];

    /** @var Array[] */
	protected $particles = [];

    /**
     * Crate Constructor.
     * @param String $name
     * @param Array $items
     * @param String $block
	 * @param String $key
     * @param String $keyName
     * @param String $nameFormat
     * @param Mixed $position
     * @param Mixed $particles
     */
	public function __construct(String $name, Array $items, String $block, String $key, String $keyName, String $nameFormat, $position = null, $particles = null){
		$this->name = $name;
		$this->items = $items;
		$this->block = $block;
		$this->key = $key;
		$this->keyName = $keyName;
		$this->nameFormat = $nameFormat;
		$this->data["block"][$block] = $name;
		$this->data["key"][$key] = $name;

		if(!empty($position)){
			$this->setPosition($position);
		}
		if(!empty($particles)){
			$this->setParticles($particles);
		}
		$this->updateTag();
	}
	
	/**
	 * @return String
	 */
	public function getName() : String {
		return $this->name;
	}

	/**
	 * @param String $name
	 */
	public function setName(String $name){
		$this->name = $name;
	}
	
	/**
	 * @return Array[]
	 */
	public function getItems() : Array {
		return $this->items;
	}

	/**
	 * @param Array $items
	 */
	public function setItems(Array $items){
		$this->items = $items;
	}
	
	/**
	 * @return String
	 */
	public function getBlock() : String {
		return $this->block;
	}

	/**
	 * @param String $block
	 */
	public function setBlock(String $block){
		$this->block = $block;
	}

	/**
	 * @return String
	 */
	public function getKey() : String {
		return $this->key;
	}

	/**
	 * @param String $key
	 */
	public function setKey(String $key){
		$this->key = $key;
	}
	
	/**
	 * @return String
	 */
	public function getKeyName() : String {
		return str_replace("&", "§", $this->keyName);
	}

	/**
	 * @param String $keyName
	 */
	public function setKeyName(String $keyName){
		$this->keyName = $keyName;
	}
	
	/**
	 * @return String
	 */
	public function getNameFormat() : String {
		return str_replace("&", "§", $this->nameFormat);
	}

	/**
	 * @param String $nameFormat
	 */
	public function setNameFormat(String $nameFormat){
		$this->nameFormat = $nameFormat;
	}
	
	/**
	 * @param Array $position
	 */
	public function setPosition($position){
		$this->position = $position;
	}
	
	/**
	 * @return Array[]
	 */
	public function getPosition(){
		return $this->position;
	}
	
	/**
	 * @param Array $particles
	 */
	public function setParticles($particles){
		$this->particles[$this->getName()] = $particles;
	}
	
	/**
	 * @return Array[]
	 */
	public function getParticles(){
		return $this->particles;
	}
	
	/**
	 * @param Int $id
	 * @param Int $damage
	 * @return bool
	 */
	public function isBlock(Int $id, Int $damage) : bool {
		return isset($this->data["block"][$id.":".$damage]) ? $this->getName() : false;
	}

	/**
	 * @param Int $id
	 * @param Int $damage
	 * @return bool
	 */
	public function isKey(Int $id, Int $damage) : bool {
		return isset($this->data["key"][$id.":".$damage]) ? $this->getName() : false;
	}
	
	/**
	 * @return bool
	 */
	public function updateTag() : bool {
		if(empty($this->getPosition())){
			return false;
		}
		$position = new Vector3($this->getPosition()[0] + 0.5, $this->getPosition()[1] + 1, $this->getPosition()[2] + 0.5);
		$this->setParticles(new FloatingTextParticle($position, "", $this->getNameFormat()."\n".TE::DARK_GRAY."-*-\n".TE::WHITE."Toca para abrir\n".TE::AQUA."buy.sandia.vip"));
		foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
			$this->addParticles($player);
		}
		return true;
	}
	
	/**
	 * @param Player $player
	 */
	public function addParticles(Player $player){
		if(!empty($this->getParticles())){
			foreach(array_values($this->getParticles()) as $particle){
				if($particle instanceof FloatingTextParticle){
					foreach($particle->encode() as $decode){
						$particle->setInvisible(false);
						$player->dataPacket($decode);
					}
				}
			}
		}
	}
}

?>