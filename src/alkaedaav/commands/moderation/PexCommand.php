<?php

namespace alkaedaav\commands\moderation;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\provider\MysqlProvider;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class PexCommand extends PluginCommand {
	
	protected $listRanks = ["Guest", "Owner", "Admin", "Jr-Admin", "Sr-Admin", "Mod", "Sr-Mod", "Partner", "MiniYT", "YouTuber", "Famous", "Monster", "alkaedaavhcfHero", "alkaedaavhcfHero+", "Demon", "Angelic", "Streamer", "Developer", "Trainee", "Co-Owner", "NitroBooster"];
	
	/**
	 * PexCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("pex", Loader::getInstance());
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
	 * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if(!$sender->hasPermission("pex.command.use")){
            $sender->sendMessage(TE::RED."You have not permissions to use this command");
            return;
        }
        if(empty($args)){
			$sender->sendMessage(TE::RED."Use: /{$label} help (see list of commands)");
			return;
		}
		$connection = MysqlProvider::getDataBase();
		switch($args[0]){
			case "setrank":
				if(!$sender->hasPermission("pex.command.use")){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])||empty($args[2])){
					$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: playerName] [string: rankName]");
					return;
				}
				if(!is_string($args[1])||!is_string($args[2])){
					$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_not_is_string")));
					return;
				}
				if(!in_array($args[2], $this->listRanks)){
					$sender->sendMessage(TE::RED."Rank {$args[2]} not exists!");
					return;
				}
				$queryTo = $connection->query("SELECT * FROM players_data_ranks WHERE player_name = '$args[1]';");
				$result = $queryTo->fetch_array(MYSQLI_ASSOC);
				if(empty($result)){
					$connection->query("INSERT INTO players_data_ranks(player_name, rank_id) VALUES ('$args[1]', '$args[2]');");
				}else{
                    $connection->query("UPDATE players_data_ranks SET rank_id = '$args[2]' WHERE player_name = '$args[1]';");
                }
			break;
            case "remove":
                if(!$sender->hasPermission("pex.command.use")){
                    $sender->sendMessage(TE::RED."You have not permissions to use this command");
                    return;
                }
                if(empty($args[1])){
                    $sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: playerName]");
                    return;
                }
                $connection->query("DELETE FROM players_data_ranks WHERE player_name = '$args[1]';");
            break;
			case "list":
				if(!$sender->hasPermission("pex.command.use")){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])){
					$sender->sendMessage(TE::RED."Use: /pex {$args[0]} [string: rankName]");
					return;
				}
				if(!in_array($args[1], $this->listRanks)){
					$sender->sendMessage(TE::RED."Rank {$args[1]} not exists!");
					return;
				}
                $queryTo = $connection->query("SELECT * FROM players_data_ranks WHERE rank_id = '$args[1]';");
				while($result = $queryTo->fetch_array(MYSQLI_ASSOC)){
					$sender->sendMessage(TE::GREEN."List of users with rank {$args[1]} ".TE::YELLOW.$result["player_name"]);
				}
			break;
			case "help":
            case "?":
				if(!$sender->hasPermission("pex.command.use")){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				$sender->sendMessage(
				TE::YELLOW."/{$label} setrank [string: playerName] [string: rankName] ".TE::GRAY."(To place a new rank on a player in db)"."\n".
                TE::YELLOW."/{$label} remove [string: playerName] ".TE::GRAY."(To remove a player from the db)"."\n".
                TE::YELLOW."/{$label} list [string: rankName] ".TE::GRAY."(Find all players with the specified rank in the db)"
                );
			break;
		}
	}
}

?>