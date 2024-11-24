<?php

namespace alkaedaav\entities;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use alkaedaav\API\projectile\Throwable;

use pocketmine\utils\TextFormat as TE;
use pocketmine\math\Vector3;
use pocketmine\entity\Entity;
use pocketmine\nbt\tag\CompoundTag;

use pocketmine\math\RayTraceResult;

use pocketmine\level\{Position, Level};
use pocketmine\item\{Item, ItemIds};

use pocketmine\block\{Block, BlockIds, FenceGate, Slab};

use pocketmine\event\entity\EntityDamageEvent;

use pocketmine\level\sound\EndermanTeleportSound;

class EnderPearl extends Throwable {

	const NETWORK_ID = self::ENDER_PEARL;

    /** @var Vector3 */
    protected $position;

    /** @var float */
    public $width = 1.25, $length = 0.25, $height = 1.25;

    /** @var float */
    protected $gravity = 0.03, $drag = 0.01;
    /**
     * EnderPearl Constructor.
     * @param Level $level
     * @param CompoundTag $nbt
     * @param Entity $shootingEntity
     */
    public function __construct(Level $level, CompoundTag $nbt, ?Entity $shootingEntity = null){
        parent::__construct($level, $nbt, $shootingEntity);
    }

    /**
     * @return void
     */
    protected function teleportAt() : void {
        if(!$this->getOwningEntity() instanceof Player||!$this->getOwningEntity()->isOnline()){
            $this->kill();
            return;
        }
        if($this->getOwningEntity() instanceof Player && $this->isFence()){
			$this->kill();
			$this->getOwningEntity()->setEnderPearl(false);
			$this->getOwningEntity()->sendTip(TE::YELLOW."Your EnderPearl was returned, to avoid glitching");
			return;
		}
		if($this->getOwningEntity() instanceof Player && $this->getOwningEntity()->isCombatTag() && Factions::isSpawnRegion($this)){
			$this->kill();
			$this->getOwningEntity()->setEnderPearl(false);
			return;
		}
		if($this->y > 0){
			$this->getLevel()->addSound(new EndermanTeleportSound($this->getOwningEntity()));
			
			$this->getOwningEntity()->teleport($this->getPositionPlayer());
			$this->getOwningEntity()->attack(new EntityDamageEvent($this->getOwningEntity(), EntityDamageEvent::CAUSE_FALL, 2));
			
			$this->getLevel()->addSound(new EndermanTeleportSound($this->getOwningEntity()));
            if($this->isPearling()){
				$direction = $this->getOwningEntity()->getDirectionVector()->multiply(2);
				$this->getLevel()->addSound(new EndermanTeleportSound($this->getOwningEntity()));
				
                $this->getOwningEntity()->teleport(Position::fromObject($this->getOwningEntity()->add($direction->x, (int)$direction->y + 1, $direction->z), $this->getOwningEntity()->getLevel()));
                $this->getOwningEntity()->attack(new EntityDamageEvent($this->getOwningEntity(), EntityDamageEvent::CAUSE_FALL, 2));
                
                $this->getLevel()->addSound(new EndermanTeleportSound($this->getOwningEntity()));
            }
		}
		$this->kill();
	}

    /**
     * @return void
     */
    protected function readPosition() : void {
        $new = $this->getPosition();
        if($new->distanceSquared($this->getPositionPlayer()) > 1){
            $this->setPositionPlayer(new Vector3($this->x, (int)$this->y, $this->z));
        }
	}
	
	/**
     * @param Vector3 $position
     */
    protected function setPositionPlayer(Vector3 $position){
        $this->position = $position;
    }

    /**
     * @return Vector3
     */
    protected function getPositionPlayer() : Vector3 {
        return $this->position === null ? new Vector3(0, 0, 0) : $this->position;
    }

    /**
	 * @return bool
	 */
	public function isFence() : bool {
		for($x = ((int)$this->x); $x <= ((int)$this->x); $x++){
			for($z = ((int)$this->z); $z <= ((int)$this->z); $z++){
				$block = $this->level->getBlockAt((int)$this->x, (int)$this->y, (int)$this->z);
				if($block instanceof FenceGate){
					return true;
				}else{
					return false;
				}
			}
		}
		return false;
    }
    
    /**
	 * @return bool
	 */
	public function isPearling() : bool {
		for($x = ($this->x + 0.1); $x <= ($this->x - 0.1); $x++){
			for($z = ($this->z + 0.1); $z <= ($this->z - 0.1); $z++){
				$block = $this->getLevel()->getBlockAt($x, $this->y, $z);
				if($block instanceof Slab){
					return true;
				}else{
					return false;
				}
			}
		}
		return false;
	}

    /** 
	 * @param Int $currentTick
	 * @return bool
	 */
	public function onUpdate(Int $currentTick) : bool {
		if($this->closed){
			return false;
		}
		$this->readPosition();
		
		$this->timings->startTiming();
		$hasUpdate = parent::onUpdate($currentTick);
		
		if($this->isCollided){
			$this->teleportAt();
			$hasUpdate = true;
		}
		$this->timings->stopTiming();
		return $hasUpdate;
    }
}

?>