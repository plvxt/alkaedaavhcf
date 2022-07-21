<?php

namespace alkaedaav\provider;

use alkaedaav\Loader;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\Server;
use SQLite3;

class SQLite3Provider {
	
	/** @var SQLite3 */
	protected static $connection;
	
	# This function starts the sqlite3 database and creates the tables
	public static function connect() {
		$connection = new \SQLite3(Loader::getInstance()->getDataFolder()."DataBase.db");
		$connection->exec("CREATE TABLE IF NOT EXISTS zoneclaims(factionName TEXT PRIMARY KEY, protection TEXT, x1 INT, y1 INT, z1 INT, x2 INT, y2 INT, z2 INT, level TEXT);");
		$connection->exec("CREATE TABLE IF NOT EXISTS homes(factionName TEXT PRIMARY KEY, x INT, y INT, z INT, level TEXT);");
		$connection->exec("CREATE TABLE IF NOT EXISTS player_data(playerName TEXT PRIMARY KEY, factionRank TEXT, factionName TEXT);");
		$connection->exec("CREATE TABLE IF NOT EXISTS balance(factionName TEXT PRIMARY KEY, money INT);");
        $connection->exec("CREATE TABLE IF NOT EXISTS strength(factionName TEXT PRIMARY KEY, dtr INT);");
        $connection->exec("CREATE TABLE IF NOT EXISTS spawns(spawnName TEXT PRIMARY KEY, level TEXT, x INT, y INT, z INT);");
        $connection->exec("CREATE TABLE IF NOT EXISTS subclaims(x INT, y INR, z INT, world TEXT, owner TEXT);");
        $connection->exec("CREATE TABLE IF NOT EXISTS points(factionName TEXT PRIMARY KEY, points INT);");
		self::$connection = $connection;
		Loader::getInstance()->getLogger()->info(TE::GREEN."SQLite3Provider » was loaded successfully!");
	}
	
	# This function is responsible for disconnecting the data from sqlite3
	public static function disconnect(){
		self::$connection->close();
	}
	
	/**
	 * @return SQLite3
	 */
	public static function getDataBase(){
		return self::$connection;
	}
}

?>