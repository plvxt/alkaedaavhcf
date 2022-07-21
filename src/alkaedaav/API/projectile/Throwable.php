<?php

namespace alkaedaav\API\projectile;

use pocketmine\block\Block;
use pocketmine\math\RayTraceResult;

abstract class Throwable extends NewProjectile {

    /** @var Int */
    public $width = 0.25, $height = 0.25;

    /** @var float */
    protected $gravity = 0.03, $drag = 0.01;

    /**
     * @param Block $blockId
     * @param RayTraceResult $hitResult
     * @return void
     */
    protected function onHitBlock(Block $blockId, RayTraceResult $hitResult) : void {
        parent::onHitBlock($blockId, $hitResult);
        $this->flagForDespawn();
    }
}

?>