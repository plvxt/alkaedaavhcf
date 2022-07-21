<?php

namespace alkaedaav\listeners\block;

use alkaedaav\{Loader, Factions};

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;

use pocketmine\block\{Door, Fence, FenceGate, Trapdoor, Chest, TrappedChest};
use pocketmine\item\{Bucket, Hoe, Shovel};

class BlockBreak implements Listener {

    /**
     * BlockBreak Constructor.
     */
    public function __construct(){
        
    }
    
    /**
     * @paran BlockBreakEvent $event
     * @return void
     */
    public function onBlockBreak(BlockBreakEvent $event) : void {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if(Factions::isSpawnRegion($block)||Factions::isProtectedRegion($block)){
        	if($player->isGodMode()) return;
        	$event->setCancelled(true);
        }
        if($event->isCancelled()) return;
        foreach($event->getDrops() as $drop){
            $player->getInventory()->addItem($drop);
        }
        $event->setDrops([]);
    }
}

?>