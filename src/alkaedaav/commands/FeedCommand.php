<?php

namespace alkaedaav\commands;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\command\{PluginCommand, CommandSender};
use pocketmine\utils\{Config, TextFormat as TE};

use pocketmine\item\{Item, ItemIds};

class FeedCommand extends PluginCommand {

    /**
     * FeedCommand Constructor.
     */
    public function __construct(){
        parent::__construct("feed", Loader::getInstance());
        
        parent::setPermission("feed.command.use");
        parent::setDescription("Can fill your food bar to 100%");
    }

    /**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
	    if(!$sender->hasPermission("feed.command.use")){
            $sender->sendMessage(TE::RED."You have not permissions to use this command");
	        return;
        }
        $sender->setFood(20);
        $sender->setSaturation(20);

        $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_fill_food_correctly")));
    }
}

?>