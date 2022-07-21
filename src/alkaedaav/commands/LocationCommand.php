<?php

namespace alkaedaav\commands;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};

class LocationCommand extends PluginCommand {

    /**
     * LocationCommand Constructor.
     */
    public function __construct(){
        parent::__construct("tl", Loader::getInstance());
        
        parent::setDescription("Send your position to your faction members");
    }
    
    /**
     * @param CommandSender $sender
     * @param String $label
     * @param Array $args
     * @return void
     */
    public function execute(CommandSender $sender, String $label, Array $args) : void {
        if(!Factions::inFaction($sender->getName())){
            $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
            return;
        }
        if($sender->getChat() === Player::FACTION_CHAT){
            $sender->setChat(Player::FACTION_CHAT);
            $sender->chat("["."X: ".$sender->getFloorX()." "."Y: ".$sender->getFloorY()." "."Z: ".$sender->getFloorZ()."]");
        }
        if($sender->getChat() === Player::PUBLIC_CHAT){
            $sender->setChat(Player::FACTION_CHAT);
            $sender->chat("["."X: ".$sender->getFloorX()." "."Y: ".$sender->getFloorY()." "."Z: ".$sender->getFloorZ()."]");
            $sender->setChat(Player::PUBLIC_CHAT);
        }
    }
}

?>