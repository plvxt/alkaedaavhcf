<?php

namespace alkaedaav\enchantments;

use alkaedaav\enchantments\type\{FireResistance, Speed, Invisibility, JumpBoost};

use pocketmine\item\enchantment\Enchantment;

class Enchantments {
	
	/** @var Array[] */
	protected static $enchantments = [];

    /**
     * @return void
     */
    public static function init() : void {
        Enchantment::registerEnchantment(self::$enchantments["Speed"] = new Speed());
        Enchantment::registerEnchantment(self::$enchantments["Fire Resistance"] = new FireResistance());
        Enchantment::registerEnchantment(self::$enchantments["Invisibility"] = new Invisibility());
        Enchantment::registerEnchantment(self::$enchantments["Jump Boost"] = new JumpBoost());
    }
    
    /**
     * @param String $name
     * @return Enchantment
     */
    public static function getEnchantmentByName(String $name) : Enchantment {
    	return self::$enchantments[$name];
    }
    
    /**
     * @return Array[]
     */
    public static function getEnchantments() : Array {
    	return self::$enchantments;
    }
}

?>