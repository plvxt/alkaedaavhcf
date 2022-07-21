<?php

namespace alkaedaav\provider;

use alkaedaav\Loader;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\Server;

class MysqlProvider {

    /** @var MySQL */
    protected static $connection;

	# This function starts the socket to mysql to execute the rest obtaining the values of the configuration
    public static function connect(){
        $connection = mysqli_connect(Loader::getDefaultConfig("MySQL")["hostname"], Loader::getDefaultConfig("MySQL")["username"], Loader::getDefaultConfig("MySQL")["password"], Loader::getDefaultConfig("MySQL")["database"], Loader::getDefaultConfig("MySQL")["port"]);
        if($connection){
            self::$connection = $connection;

            $connection->query("CREATE TABLE IF NOT EXISTS players_data_ranks(player_name TEXT, rank_id TEXT, prefix_id TEXT);");

            $connection->query("CREATE TABLE IF NOT EXISTS players_saved_information(player_name TEXT, uuid TEXT, clientId TEXT, country TEXT, address TEXT, factionName TEXT);");

            Loader::getInstance()->getLogger()->info(TE::GREEN."MysqlProvider » connection was successfull!");
        }else{
            Loader::getInstance()->getLogger()->info(TE::RED."Could not connect to MySQL!");
        }
    }

	# This function is responsible for disconnecting the sockets when the server shuts down
    public static function disconnect(){
        self::$connection->close();
    }

    /**
     * @return MySQL
     */
    public static function getDataBase(){
        return self::$connection;
    }
}

?>