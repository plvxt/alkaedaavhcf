<?php

namespace alkaedaav\listeners\block;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use pocketmine\event\Listener;
use pocketmine\entity\Human;

use pocketmine\block\{ItemFrame, Door, Fence, FenceGate, Trapdoor, Chest, TrappedChest};
use pocketmine\item\{Bucket, Hoe, Shovel};

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockPlaceEvent;

use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};

class BlockPlace implements Listener {
	
    /**
     * BlockPlace Constructor.
     */
    public function __construct(){
        
    }
    
    /**
     * @paran BlockPlace $event
     * @return void
     */
    public function onBlockPlace(BlockPlaceEvent $event) : void {
    	$player = $event->getPlayer();
        $block = $event->getBlock();
        if(Factions::isSpawnRegion($block)||Factions::isProtectedRegion($block)){
        	if($player->isGodMode()) return;
        	$event->setCancelled(true);
        }
    }
    
    /**
     * @param PlayerInteractEvent $event
     * @return void
     */
    public function onPlayerInteractEvent(PlayerInteractEvent $event) : void {
    	$player = $event->getPlayer();
    	$block = $event->getBlock();
        $item = $event->getItem();
        if($block instanceof ItemFrame||$block instanceof Fence||$block instanceof FenceGate||$block instanceof Door||$block instanceof Trapdoor||$block instanceof Chest||$block instanceof TrappedChest||$item instanceof Bucket||$item instanceof Hoe||$item instanceof Shovel){
    	    if(Factions::isSpawnRegion($block)||Factions::isProtectedRegion($block)){
    			if($player->isGodMode()) return;
    			$event->setCancelled(true);
    		}
    	}
    }
    
    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onEntityDamageEvent(EntityDamageEvent $event) : void {
    	$player = $event->getEntity();
    	if(Factions::isSpawnRegion($player)){
    		if($player instanceof Human||$player instanceof Player){
    			$event->setCancelled(true);
    		}
    	}
    	if($event instanceof EntityDamageByEntityEvent){
            $damager = $event->getDamager();
    		if($player instanceof Player && $damager instanceof Player){
    			if(Factions::isSpawnRegion($damager) && !Factions::isSpawnRegion($player)){
    				$event->setCancelled(true);
    			}
    		}
    	}
    }
}

?>