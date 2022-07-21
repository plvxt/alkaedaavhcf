<?php

namespace alkaedaav\commands;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class EndPlayersCommand extends PluginCommand {

    /**
     * EndPlayersCommand Constructor.
     */
    public function __construct(){
        parent::__construct("endplayers", Loader::getInstance());
        
        parent::setPermission("endplayers.command.use");
        parent::setDescription("Can see how many players there are in the End world");
    }

    /**
     * @param CommandSender $sender
     * @param String $label
     * @param Array $args
     * @return void
     */
    public function execute(CommandSender $sender, String $label, Array $args) : void {
        if(!$sender->hasPermission("endplayers.command.use")){
            $sender->sendMessage(TE::RED."You have not permissions to use this command");
            return;
        }
        if(!Loader::getInstance()->getServer()->isLevelGenerated(Loader::getDefaultConfig("LevelManager")["levelEndName"])){
            $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_current_world_is_disable")));
            return;
        }
        if(!Loader::getInstance()->getServer()->isLevelLoaded(Loader::getDefaultConfig("LevelManager")["levelEndName"])){
            Loader::getInstance()->getServer()->loadLevel(Loader::getDefaultConfig("LevelManager")["levelEndName"]);
        }
        $level = Loader::getInstance()->getServer()->getLevelByName(Loader::getDefaultConfig("LevelManager")["levelEndName"]);
        $sender->sendMessage(str_replace(["&", "{players}", "{worldName}"], ["§", count($level->getPlayers()), $level->getName()], Loader::getConfiguration("messages")->get("player_current_player_in_a_world")));
    }
}

?>