<?php

namespace alkaedaav\API\projectile;

use pocketmine\Server;
use pocketmine\timings\Timings;
use pocketmine\math\{RayTraceResult, VoxelRayTrace};

use pocketmine\event\entity\ProjectileHitBlockEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\entity\ProjectileHitEvent;

abstract class NewProjectile extends \pocketmine\entity\projectile\Projectile {

    /**
     * @param float $dx
     * @param float $dy
     * @param float $dz
     * @return void
     */
    public function move(float $dx, float $dy, float $dz) : void {
        $this->blocksAround = null;

		Timings::$entityMoveTimer->startTiming();

		$start = $this->asVector3();
		$end = $start->add($this->motion);

		$blockHit = null;
		$entityHit = null;
		$hitResult = null;

		foreach(VoxelRayTrace::betweenPoints($start, $end) as $vector3){
			$block = $this->level->getBlockAt($vector3->x, $vector3->y, $vector3->z);

			$blockHitResult = $this->calculateInterceptWithBlock($block, $start, $end);
			if($blockHitResult !== null){
				$end = $blockHitResult->hitVector;
				$blockHit = $block;
				$hitResult = $blockHitResult;
				break;
			}
		}

		$entityDistance = PHP_INT_MAX;

		$newDiff = $end->subtract($start);
		foreach($this->level->getCollidingEntities($this->boundingBox->addCoord($newDiff->x, $newDiff->y, $newDiff->z)->expand(0.2, 0.2, 0.2), $this) as $entity){
			if($entity->getId() === $this->getOwningEntityId() and $this->ticksLived < 5){
				continue;
			}

			$entityBB = $entity->boundingBox->expandedCopy(0.1, 0.1, 0.1);
            try {
                $entityHitResult = $entityBB->calculateIntercept($start, $end);
            } catch(\DivisionByZeroError $error) {
                Server::getInstance()->getLogger()->logException($error);
                $entityHitResult = null;
            }

			if($entityHitResult === null){
				continue;
			}

			$distance = $this->distanceSquared($entityHitResult->hitVector);

			if($distance < $entityDistance){
				$entityDistance = $distance;
				$entityHit = $entity;
				$hitResult = $entityHitResult;
				$end = $entityHitResult->hitVector;
			}
		}

		$this->x = $end->x;
		$this->y = $end->y;
		$this->z = $end->z;
		$this->recalculateBoundingBox();

		if($hitResult !== null){
			/** @var ProjectileHitEvent|null $ev */
			$ev = null;
			if($entityHit !== null){
				$ev = new ProjectileHitEntityEvent($this, $hitResult, $entityHit);
			}elseif($blockHit !== null){
				$ev = new ProjectileHitBlockEvent($this, $hitResult, $blockHit);
			}else{
				assert(false, "unknown hit type");
			}

			if($ev !== null){
				$ev->call();
				$this->onHit($ev);

				if($ev instanceof ProjectileHitEntityEvent){
					$this->onHitEntity($ev->getEntityHit(), $ev->getRayTraceResult());
				}elseif($ev instanceof ProjectileHitBlockEvent){
					$this->onHitBlock($ev->getBlockHit(), $ev->getRayTraceResult());
				}
			}

			$this->isCollided = $this->onGround = true;
			$this->motion->x = $this->motion->y = $this->motion->z = 0;
		}else{
			$this->isCollided = $this->onGround = false;
			$this->blockHit = $this->blockHitId = $this->blockHitData = null;

			$f = sqrt(($this->motion->x ** 2) + ($this->motion->z ** 2));
			$this->yaw = (atan2($this->motion->x, $this->motion->z) * 180 / M_PI);
			$this->pitch = (atan2($this->motion->y, $f) * 180 / M_PI);
		}

		$this->checkChunks();
		$this->checkBlockCollision();

		Timings::$entityMoveTimer->stopTiming();
	}
}

?>