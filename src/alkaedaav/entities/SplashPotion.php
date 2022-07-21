<?php

namespace alkaedaav\entities;

use alkaedaav\player\Player;

use alkaedaav\API\projectile\Throwable;

use pocketmine\item\Potion;
use pocketmine\level\Level;
use pocketmine\utils\Color;

use pocketmine\entity\{Entity, Living};

use pocketmine\block\{Block, BlockFactory};

use pocketmine\math\RayTraceResult;

use pocketmine\nbt\tag\{CompoundTag, ShortTag};

use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

use pocketmine\event\entity\ProjectileHitBlockEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\entity\ProjectileHitEvent;

class SplashPotion extends Throwable {
	
	const NETWORK_ID = self::SPLASH_POTION;

	/** @var float */
    public $width = 0.25, $length = 0.25, $height = 0.25;

	/** @var float */
    protected $gravity = 0.1, $drag = 0.05;
    
    /**
     * SplashPotion Constructor.
     * @param Level $level
     * @param CompoundTag $nbt
     * @param Entity $shootingEntity
     */
    public function __construct(Level $level, CompoundTag $nbt, ?Entity $shootingEntity = null){
    	parent::__construct($level, $nbt, $shootingEntity);
    }

    /**
     * @return Int
     */
    public function getPotionId() : Int {
    	return $this->namedtag["PotionId"];
   }
   
   /**
    * @return void
    */
   public function splashOnPlayer() : void {
   	$radius = 6;
   	$colors = [new Color(0x38, 0x5d, 0xc6)];
   
   	$this->getLevel()->broadcastLevelEvent($this, LevelEventPacket::EVENT_PARTICLE_SPLASH, Color::mix(...$colors)->toARGB());
	   $this->getLevel()->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_GLASS);
   	foreach($this->getLevel()->getNearbyEntities($this->getBoundingBox()->expand($radius, $radius, $radius)) as $entity){
   		foreach(Potion::getPotionEffectsById($this->getPotionId()) as $effect){
   			if($entity instanceof Player){
   				$entity->addEffect($effect);
   			}
   		}
   	}
   	$this->close();
   }
   
   /** 
	 * @param Int $currentTick
	 * @return bool
	 */
	public function onUpdate(Int $currentTick) : bool {
		if($this->closed){
			return false;
		}
		$this->timings->startTiming();
		$hasUpdate = parent::onUpdate($currentTick);
		
		if($this->isCollided){
		    $this->splashOnPlayer();
			$hasUpdate = true;
		}
		$this->timings->stopTiming();
		return $hasUpdate;
    }

    /**
     * @param Entity $entity
     * @param RayTraceResult $hitResult
     * @return void
     */
     /*
    protected function onHitEntity(Entity $entity, RayTraceResult $hitResult) : void {
        //parent::onHitEntity($entity, $hitResult);
    }*/
}

?>