<?php

namespace alkaedaav\listeners;

use alkaedaav\{Loader, Factions};
use pocketmine\utils\TextFormat;
use alkaedaav\player\Player;

use alkaedaav\utils\Time;

use alkaedaav\entities\ZombieBard;

use alkaedaav\Task\EnderPearlTask;
use alkaedaav\Task\specials\{LoggerBaitTask,
    NinjaShearTask,
    StormBreakerTask,
    AntiTrapperTask,
    SpecialItemTask,
    PotionCounterTask,
    BerserkTask,
    ZombieBardItemTask};

use alkaedaav\Task\delayedtask\{StormBreakerDelayed};

use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Snowball;

use alkaedaav\item\specials\{LoggerBait,
    NinjaShear,
    StormBreaker,
    AntiTrapper,
    Strength,
    Resistance,
    Invisibility,
    PotionCounter,
    Firework,
    Cactus,
    CloseCall,
    RemovePearl,
    RageMode,
    RageBrick,
    TankMode,
    MediKit,
    ZombieBardItem,
    Sky,
    GraplingHook,
    HolyClocks};

use pocketmine\utils\TextFormat as TE;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;

use pocketmine\item\{Item, ItemIds};
use pocketmine\entity\{Effect, EffectInstance, Villager};
use pocketmine\block\{Door, Fence, FenceGate, Fire};

use pocketmine\event\block\{BlockBreakEvent, BlockPlaceEvent};
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent, ProjectileHitEvent, ProjectileHitEntityEvent};
use pocketmine\event\player\PlayerInteractEvent;

class SpecialItems implements Listener {

    /**
     * SpecialItems Constructor.
     */
    public function __construct(){
        
    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onEntityDamageEvent(EntityDamageEvent $event) : void {
        $player = $event->getEntity();
        if($event instanceof EntityDamageByEntityEvent){
            $damager = $event->getDamager();
            if($player instanceof Player && $damager instanceof Player){
                if($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK){
	                $item = $damager->getInventory()->getItemInHand();
	                if(!Factions::isSpawnRegion($damager) && $item instanceof StormBreaker && $item->getNamedTagEntry(StormBreaker::CUSTOM_ITEM) instanceof CompoundTag){
	                    
						if(Factions::inFaction($player->getName()) && Factions::inFaction($damager->getName()) && Factions::getFaction($player->getName()) === Factions::getFaction($damager->getName())) return;
						
	                    if($damager->isStormBreaker()){
	                        $damager->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($damager->getStormBreakerTime())], Loader::getConfiguration("messages")->get("stormbreaker_cooldown")));
	                        $event->setCancelled(true);
	                        return;
	                    }
						$damager->sendMessage(str_replace(["&", "{playerName}"], ["§", $player->getName()], Loader::getConfiguration("messages")->get("stormbreaker_was_used_correctly")));
	
						# This task is executed after a few seconds, to remove the player's helmet
	                    Loader::getInstance()->getScheduler()->scheduleDelayedTask(new StormBreakerDelayed($player), 40);
	
	                    $item->reduceUses($damager);
	                    $damager->setStormBreaker(true);
	                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new StormBreakerTask($damager), 20);
	                }
	                if(!Factions::isSpawnRegion($damager) && $item instanceof AntiTrapper && $item->getNamedTagEntry(AntiTrapper::CUSTOM_ITEM) instanceof CompoundTag){
		
	                    if(Factions::inFaction($player->getName()) && Factions::inFaction($damager->getName()) && Factions::getFaction($player->getName()) === Factions::getFaction($damager->getName())) return;
	
	                    if($damager->isAntiTrapper()){
	                        $damager->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($damager->getAntiTrapperTime())], Loader::getConfiguration("messages")->get("antitrapper_cooldown")));
	                        $event->setCancelled(true);
	                        return;
	                    }
	                    $item->reduceUses($damager);
	                    $damager->setAntiTrapper(true);
	                    //here we place the time for which the player cannot place blocks
	                    $player->setAntiTrapperTarget(true);
	                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new AntiTrapperTask($damager, $player), 20);
	                }
                    if(!Factions::isSpawnRegion($damager) && $item instanceof PotionCounter && $item->getNamedTagEntry(PotionCounter::CUSTOM_ITEM) instanceof CompoundTag){

                        if(Factions::inFaction($player->getName()) && Factions::inFaction($damager->getName()) && Factions::getFaction($player->getName()) === Factions::getFaction($damager->getName())) return;

                        if($damager->isPotionCounter()){
                            $damager->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($damager->getPotionCounterTime())], Loader::getConfiguration("messages")->get("potioncounter_cooldown")));
                            $event->setCancelled(true);
                            return;
                        }
                        $item->reduceUses($damager);

                        $inventory = [];
                        $enderchest = [];
                        foreach($player->getInventory()->getContents() as $slot => $item){
                            if($item->getId() === 438 && $item->getDamage() === 22){
                                $inventory[] = $item;
                            }
                        }
                        foreach($player->getEnderChestInventory()->getContents() as $slot => $item){
                            if($item->getId() === 438 && $item->getDamage() === 22){
                                $enderchest[] = $item;
                            }
                        }
                        $damager->sendMessage(str_replace(["&", "{playerName}", "{potionsTotal}"], ["§",$player->getName(), count($inventory)], Loader::getConfiguration("messages")->get("potioncounter_count_target_inventory_potion")));
                        $damager->sendMessage(str_replace(["&", "{playerName}", "{potionsTotal}"], ["§",$player->getName(), count($enderchest)], Loader::getConfiguration("messages")->get("potioncounter_count_target_enderchest_potion")));

                        $damager->setPotionCounter(true);
                        Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new PotionCounterTask($damager), 20);
                    }
                }
	        }
	    }
	}
	
    /**
     * @param BlockBreakEvent $event
     * @return void
     */
    public function onBlockBreak(BlockBreakEvent $event) : void {
        $player = $event->getPlayer();
        if($player->isAntiTrapperTarget()){
            $player->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getAntiTrapperTime())], Loader::getConfiguration("messages")->get("antitrapper_target_cooldown")));
            $event->setCancelled(true);
        }
    }

    /**
     * @param BlockPlaceEvent $event
     * @return void
     */
    public function onBlockPlace(BlockPlaceEvent $event) : void {
        $player = $event->getPlayer();
        if($player->isAntiTrapperTarget()){
            $player->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getAntiTrapperTime())], Loader::getConfiguration("messages")->get("antitrapper_target_cooldown")));
            $event->setCancelled(true);
        }
    }

    /**
     * @param PlayerInteractEvent $event
     * @return void
     */
    public function onPlayerInteractEvent(PlayerInteractEvent $event) : void {
        $player = $event->getPlayer();
        if(!$player instanceof Player) {
            return;
        }
        $block = $event->getBlock();
        $item = $event->getItem();
        if($player->isAntiTrapperTarget()){
            if($block instanceof Fence||$block instanceof FenceGate||$block instanceof Door){
                $player->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getAntiTrapperTime())], Loader::getConfiguration("messages")->get("antitrapper_target_cooldown")));
                $event->setCancelled(true);
                $player->setSpecialItem(true);
                $player->setSpecialItem(true);
            }
        }
        if($item instanceof Strength && $item->getNamedTagEntry(Strength::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 10, 2));

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
                            $online->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 10, 2));
                        }
                    }
                }
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setSpecialItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
            }
        }
        if($item instanceof Resistance && $item->getNamedTagEntry(Resistance::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 15 * 10, 2));

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
                            $online->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 15 * 10, 2));
                        }
                    }
                }
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setSpecialItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
            }
        }
        if($item instanceof Cactus && $item->getNamedTagEntry(Cactus::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 15 * 10, 2));
                 $player->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 10, 2));
                 $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 15 * 10, 2));
                 $player->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 15 * 10, 2));
                 

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
                            $online->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 15 * 10, 2));
                        }
                    }
                }
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setSpecialItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
            }
        }
        if($item instanceof NinjaShear && $item->getNamedTagEntry(NinjaShear::CUSTOM_ITEM) instanceof CompoundTag) {
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
                $message = TextFormat::RED . "No puedes usar esto porque nadie te ha golpeado!";
                if(!$player->isCombatTag()) {
                    $player->sendMessage($message);
                    return;
                } elseif(!$player->hasNinjaShearPosition()) {
                    $player->sendMessage($message);
                    return;
                }
                if($player->canUseNinjaShear()) {
                    $player->updateLastUseNinjaShearTime();
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new NinjaShearTask($player), 20);
                    $item->reduceUses($player);
                } else {
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getNinjaShearTimeRemaining())], Loader::getConfiguration("messages")->get("ninjashear_cooldown")));
                }
            }
        }
        if($item instanceof RageMode && $item->getNamedTagEntry(RageMode::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 15 * 10, 2));
                 $player->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 10, 2));
                

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
                            $online->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 15 * 10, 2));
                        }
                    }
                }
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setSpecialItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
            }
        }
        if ($item instanceof LoggerBait && $item->getNamedTagEntry(LoggerBait::CUSTOM_ITEM) instanceof CompoundTag) {
            if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
                if ($player->isSpecialItem()) {
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $nbt = Entity::createBaseNBT(new Vector3((float)$player->getX(), (float)$player->getY(), (float)$player->getZ()));
                $human = new Villager($player->getLevel(), $nbt);
                $human->setNameTagVisible(true);
                $human->setNameTagAlwaysVisible(true);
                $human->yaw = $player->getYaw();
                $human->pitch = $player->getPitch();
                $human->setNameTag(TE::GRAY . "(Combat-Logger) " . TE::RED . $player->getName() . TE::GRAY);
                $human->spawnToAll();
                $player->setSpecialItem(true);
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setSpecialItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new LoggerBaitTask($player), 20);
            }
            return;
        }
        if($item instanceof RageBrick && $item->getNamedTagEntry(RageBrick::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 15 * 10, 2));
                 $player->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 10, 2));
                

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
                            $online->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 15 * 10, 2));
                        }
                    }
                }
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setSpecialItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
            }
        }
        if($item instanceof Sky && $item->getNamedTagEntry(Sky::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::LEVITATION), 15 * 10, 3));
                

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
                            $online->addEffect(new EffectInstance(Effect::getEffect(Effect::LEVITATION), 15 * 10, 2));
                        }
                    }
                }
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setSpecialItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
            }
        }
        if($item instanceof MediKit && $item->getNamedTagEntry(MediKit::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isBerserkItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getBerserkTime())], Loader::getConfiguration("messages")->get("berserk_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 25 * 10, 2));
                 $player->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 25 * 10, 1));
                 $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 25 * 10, 2));
                 $player->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 25 * 10, 1));
                 
                

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
                            $online->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 15 * 10, 2));
                        }
                    }
                }
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setBerserkItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new BerserkTask($player), 20);
               }
            }
            if($item instanceof ZombieBardItem && $item->getNamedTagEntry(ZombieBardItem::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
                if($player->isBardItem()){
                  $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getBardTime())], Loader::getConfiguration("messages")->get("bard_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $nbt = Entity::createBaseNBT($block->add(0, 1, 0));
				$ent = new ZombieBard($player->getLevel(), $nbt, $player->getName());
				$ent->spawnToAll();
			
				$item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setBardItem(true);
				
				Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new ZombieBardItemTask($player), 20);
            }
        }
        if($item instanceof TankMode && $item->getNamedTagEntry(TankMode::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 15 * 10, 2));
                 $player->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 15 * 10, 2));
                

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
                            $online->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 15 * 10, 2));
                        }
                    }
                }
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setSpecialItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
            }
        }
        if($item instanceof GraplingHook && $item->getNamedTagEntry(GraplingHook::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->setMotion($player->getDirectionVector()->add(0, 0.2, 0)->multiply(3));
                

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
                            $online->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 15 * 10, 2));
                        }
                    }
                }
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setSpecialItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
            }
        }
        if($item instanceof HolyClocks && $item->getNamedTagEntry(TankMode::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->removeEffect(Effect::SLOWNESS);
                $player->removeEffect(Effect::POISON);
                  $player->removeEffect(Effect::WEAKNESS);
                  $player->removeEffect(Effect::NAUSEA);
                  $player->removeEffect(Effect::BLINDNESS);
                

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
                            $online->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 15 * 10, 2));
                        }
                    }
                }
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setSpecialItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
            }
        }
        if($item instanceof CloseCall && $item->getNamedTagEntry(CloseCall::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 10, 2));
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 15 * 10, 4));

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
                            $online->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 10, 2));
                        }
                    }
                }
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setSpecialItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
            }
        }
        if($item instanceof RemovePearl && $item->getNamedTagEntry(RemovePearl::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                  }
                if($player->isEnderPearl()){
                    $player->setEnderPearl(false);
                } else {
                    $player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("secondchance_cannot_use_if_not_have_cooldown")));
                    $event->setCancelled(true);
                    return;
                    }
                    $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setSpecialItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
            }
        }
        if($item instanceof Invisibility && $item->getNamedTagEntry(Invisibility::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 20 * 60, 1));

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
                            $online->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 20 * 60, 1));
                        }
                    }
                }
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setSpecialItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
            }
        }
        if($item instanceof Firework && $item->getNamedTagEntry(Firework::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->knockBack($player, 0, $player->getDirectionVector()->x, $player->getDirectionVector()->z, 2.1);

                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setSpecialItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
            }
        }
    }

    public function onProjectileHit(ProjectileHitEntityEvent $event): void
    {
        $projectile = $event->getEntity();
        if (!$projectile instanceof Snowball) {
            return;
        }
        $sender = $projectile->getOwningEntity();
        if ($sender instanceof Player and $event instanceof ProjectileHitEntityEvent and $sender->hasPermission("snowball.use")) {
            $player = $event->getEntityHit();
            if (!$sender instanceof Player) {
                return;
            }
            $target = $event->getEntityHit();
            if (!$target instanceof Player) {
                return;
            }
            if ($target->getName() !== $sender->getName()) {
                $sender->sendMessage(TE::BOLD . TE::GOLD . "Snowball" . TE::RESET . TE::WHITE . ": " . TE::GRAY . "you marked the player " . TE::BOLD . TE::GREEN . $target->getName());
                $target->addEffect(new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 20 * 5, 0));
                $target->addEffect(new EffectInstance(Effect::getEffect(Effect::BLINDNESS), 20 * 5, 0));
            }
        }
    }

    public function onFight(EntityDamageByEntityEvent $event): void {
        $entity = $event->getEntity();
        if(!$entity instanceof Player) {
            return;
        }
        $entity->setNinjaShearPosition($event->getDamager()->asVector3());
    }

}