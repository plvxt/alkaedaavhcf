<?php

namespace alkaedaav\Task;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use pocketmine\item\{Item, ItemIds};
use pocketmine\entity\{Effect, EffectInstance};

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class BardTask extends Task {

    /**
     * BardTask Constructor.
     */
    public function __construct(){

    }
    
    /**
     * @param Int $currentTick
     * @return void
     */
    public function onRun(Int $currentTick) : void {
        foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
        	$player->checkClass();
            if($player->isBardClass() && $player->getBardEnergy() < 120){
				$player->setBardEnergy($player->getBardEnergy() + 1);
	            if(Factions::inFaction($player->getName())){
	                switch($player->getInventory()->getItemInHand()->getId()){
	                    case ItemIds::SUGAR:
	                    	if(Factions::isSpawnRegion($player)) return;
	                        foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
	                            $online = Loader::getInstance()->getServer()->getPlayer($value);
	                            if($online instanceof Player && $online->distanceSquared($player) < 250){
	                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 10, 1));
	                            }
	                        }
	                    break;
	                    case ItemIds::IRON_INGOT:
	                    	if(Factions::isSpawnRegion($player)) return;
	                        foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
	                            $online = Loader::getInstance()->getServer()->getPlayer($value);
	                            if($online instanceof Player && $online->distanceSquared($player) < 250){
	                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 20 * 10, 1));
	                            }
	                        }
	                    break;
	                    case ItemIds::BLAZE_POWDER:
	                    	if(Factions::isSpawnRegion($player)) return;
	                        foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
	                            $online = Loader::getInstance()->getServer()->getPlayer($value);
	                            if($online instanceof Player && $online->distanceSquared($player) < 250){
	                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 20 * 10, 0));
	                            }
	                        }
	                    break;
	                    case ItemIds::GHAST_TEAR:
	                    	if(Factions::isSpawnRegion($player)) return;
	                        foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
	                            $online = Loader::getInstance()->getServer()->getPlayer($value);
	                            if($online instanceof Player && $online->distanceSquared($player) < 250){
	                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 20 * 10, 1));
	                            }
	                        }
	                    break;
	                    case ItemIds::FEATHER:
	                    	if(Factions::isSpawnRegion($player)) return;
	                        foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
	                            $online = Loader::getInstance()->getServer()->getPlayer($value);
	                            if($online instanceof Player && $online->distanceSquared($player) < 250){
	                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 20 * 10, 1));
	                            }
	                        }
	                    break;
	                    case ItemIds::DYE:
	                    	if(Factions::isSpawnRegion($player)) return;
	                        foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
	                            $online = Loader::getInstance()->getServer()->getPlayer($value);
	                            if($online instanceof Player && $online->distanceSquared($player) < 250){
	                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 20 * 10, 0));
	                            }
	                        }
	                    break;
	                    case ItemIds::MAGMA_CREAM:
	                    	if(Factions::isSpawnRegion($player)) return;
	                        foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
	                            $online = Loader::getInstance()->getServer()->getPlayer($value);
	                            if($online instanceof Player && $online->distanceSquared($player) < 250){
	                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 20 * 10, 1));
	                            }
	                        }
	                    break;
	                }
	            }
	        }
	    }
	}
}

?>