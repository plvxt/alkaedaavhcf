<?php

namespace alkaedaav\Task;

use pocketmine\{Player, Server};

use pocketmine\scheduler\Task;
use alkaedaav\{Loader, Factions};
use pocketmine\utils\TextFormat as TE;

class TagPlayer extends Task {
  
  public function __construct(){
   
    Loader::getInstance()->getScheduler()->scheduleRepeatingTask($this, 300);
  }
  
  function onRun(int $tick){
    foreach(Server::getInstance()->getOnlinePlayers() as $player){
      $faction = self::getNameFaction($player);
      $dtr = self::getDTRFaction($player);
      $player->setScoreTag("§c[ ".$faction." |§a ".$dtr."§c■ ]");
      $player->setNameTag(str_replace($player->getDisplayName(), TE::WHITE . $player->getDisplayName(), $player->getNameTag()));
    }
  }
  
  static function getNameFaction($player){
    if(Factions::getFaction($player->getName()) !== null){
      return Factions::getFaction($player->getName());
    } else {
      return "";
    }
  }
  
  static function getDTRFaction($player){
    if(Factions::getStrength(self::getNameFaction($player)) !== null){
      return Factions::getStrength(self::getNameFaction($player));
    } else {
      return 0;
    }
  }
}