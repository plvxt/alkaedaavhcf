<?php

namespace alkaedaav\entities\tiles;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\block\MonsterSpawner;

use pocketmine\tile\Spawnable;
use pocketmine\math\Vector3;
use pocketmine\entity\Entity;
use pocketmine\level\Level;

use pocketmine\block\{Block, Air};
use pocketmine\item\{Item, ItemIds};
use pocketmine\nbt\tag\CompoundTag;

class MonsterTileSpawner extends Spawnable {
	
	const ENTITY_NAME_KEY = "Spawner_Name";
	
	/** @var Int */
	protected $delay = 0;
	
	/** @var String */
	protected $spawner = "";
	
	/**
	 * MonsterTileSpawner
	 * @param Level $level
	 * @param CompoundTag $nbt
	 */
	public function __construct(Level $level, CompoundTag $nbt){
		parent::__construct($level, $nbt);
		$this->scheduleUpdate();
	}
	
	/**
	 * @return bool
	 */
	public function onUpdate() : bool {
		if($this->closed){
			return false;
		}
		if(!$this->getLevel()->getBlock($this) instanceof MonsterSpawner){
			$this->close();
			return false;
		}
		$success = false;
		if(--$this->delay <= 0 && $this->canUpdate()){
			$position = $this->add(rand(-4, 4), rand(-1, 1), rand(-4, 4));
    		$entity = Entity::createEntity($this->getName(), $this->getLevel(), Entity::createBaseNBT($position->add(0, 2), null, 0, 0));
    		if($entity instanceof Entity){
                $success = true;
                $entity->spawnToAll();
            }
   	 	}
   	 	if($success){
   	 	    $this->delay = Loader::getDefaultConfig("LevelManager")["EntitySpawnDelay"];
    	}
        return true;
	}
	
	/**
	 * @return bool
	 */
	public function canUpdate() : bool {
		if($this->getLevel()->isChunkLoaded($this->getX() >> 4, $this->getZ() >> 4)){
			$hasUpdate = false;
			foreach($this->getLevel()->getEntities() as $player){
				if($player instanceof Player && (int)$player->distance($this) <= 16){
					$hasUpdate = true;
				}
			}
			return $hasUpdate;
		}
		return $hasUpdate;
	}
	
	/**
     * @param CompoundTag $nbt
     */
    public function addAdditionalSpawnData(CompoundTag $nbt): void {
        $nbt->setString(self::ENTITY_NAME_KEY, $this->getName());
        $this->scheduleUpdate();
    }

    /**
     * @param CompoundTag $nbt
     */
    public function readSaveData(CompoundTag $nbt): void {
        if($nbt->hasTag(self::ENTITY_NAME_KEY)){
        	$this->setName($nbt->getString(self::ENTITY_NAME_KEY));
        }
    }

    /**
     * @param CompoundTag $nbt
     */
    public function writeSaveData(CompoundTag $nbt): void {
        $this->addAdditionalSpawnData($nbt);
    }
    
    /**
     * @param String $name
     */
    public function setName(String $spawner){
    	$this->spawner = $spawner;
    }
    
    /**
     * @return String
     */
    public function getName() : String {
    	return $this->spawner;
    }
}

?>