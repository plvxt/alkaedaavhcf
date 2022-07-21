<?php

namespace alkaedaav\commands;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class AutoFeedCommand extends PluginCommand {

    /**
     * AutoFeedCommand Constructor.
     */
    public function __construct(){
        parent::__construct("autofeed", Loader::getInstance());
        
        parent::setPermission("autofeed.command.use");
        parent::setDescription("Can fill your food bar automatically");
    }

    /**
     * @param CommandSender $sender
     * @param String $label
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, String $label, Array $args) : void {
        if(!$sender->hasPermission("autofeed.command.use")){
            $sender->sendMessage(TE::RED."You have not permissions to use this command");
            return;
        }
        if($sender->isAutoFeed()){
            $sender->setAutoFeed(false);
            $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_autofeed_disable_correctly")));
        }else{
            $sender->setAutoFeed(true);
            $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_autofeed_enable_correctly")));
        }
    }
}

?>