<?php

namespace alkaedaav\entities;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\API\projectile\Throwable;

use pocketmine\utils\TextFormat as TE;
use pocketmine\math\Vector3;
use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;

use pocketmine\event\entity\EntityDamageEvent;

class FishingHook extends Throwable {
	
	const NETWORK_ID = self::FISHING_HOOK ;

	/** @var float */
    public $width = 0.25, $length = 0.25, $height = 0.25;

	/** @var float */
    protected $gravity = 0.11, $drag = 0.01;
    
    /** @var bool */
    protected $hasFishing = false;
    
    /**
     * FishingHook Constructor.
     * @param Level $level
     * @param CompoundTag $nbt
     * @param Entity $shootingEntity
     */
    public function __construct(Level $level, CompoundTag $nbt, ?Entity $shootingEntity = null){
        parent::__construct($level, $nbt, $shootingEntity);
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
			$this->close();
            $hasUpdate = true;
		}
		$this->timings->stopTiming();
        return $hasUpdate;
    }
}

?>