<?php

namespace alkaedaav\utils;

use pocketmine\item\enchantment\Enchantment;

class  {
	
	/** @var Array[] */
	protected static $enchantments = [
		"PROTECTION" => Enchantment::PROTECTION,
		"FIRE_PROTECTION" => Enchantment::FIRE_PROTECTION,
		"FEATHER_FALLING" => Enchantment::FEATHER_FALLING,
		"BLAST_PROTECTION" => Enchantment::BLAST_PROTECTION,
		"PROJECTILE_PROTECTION" => Enchantment::PROJECTILE_PROTECTION,
		"THORNS" => Enchantment::THORNS,
		"RESPIRATION" => Enchantment::RESPIRATION,
		"DEPTH_STRIDER" => Enchantment::DEPTH_STRIDER,
		"AQUA_AFFINITY" => Enchantment::AQUA_AFFINITY,
		"SHARPNESS" => Enchantment::SHARPNESS,
		"SMITE" => Enchantment::SMITE,
		"BANE_OF_ARTHROPODS" => Enchantment::BANE_OF_ARTHROPODS,
		"KNOCKBACK" => Enchantment::KNOCKBACK,
		"FIRE_ASPECT" => Enchantment::FIRE_ASPECT,
		"LOOTING" => Enchantment::LOOTING,
		"EFFICIENCY" => Enchantment::EFFICIENCY,
		"SILK_TOUCH" => Enchantment::SILK_TOUCH,
		"UNBREAKING" => Enchantment::UNBREAKING,
		"FORTUNE" => Enchantment::FORTUNE,
		"POWER" => Enchantment::POWER,
		"PUNCH" => Enchantment::PUNCH,
		"FLAME" => Enchantment::FLAME,
		"NIGHT_VISION" => Enchantment::NIGHT_VISION,
		"Blaze" => Enchantment::Blaze,
		"LUCK_OF_THE_SEA" => Enchantment::LUCK_OF_THE_SEA,
		"LURE" => Enchantment::LURE,
		"FROST_WALKER" => Enchantment::FROST_WALKER,
		"MENDING" => Enchantment::MENDING,
		"BINDING" => Enchantment::BINDING,
		"VANISHING" => Enchantment::VANISHING,
		"IMPALING" => Enchantment::IMPALING,
		"RIPTIDE" => Enchantment::RIPTIDE,
		"LOYALTY" => Enchantment::LOYALTY,
		"CHANNELING" => Enchantment::CHANNELING,
		"MULTISHOT" => Enchantment::MULTISHOT,
		"PIERCING" => Enchantment::PIERCING,
		"QUICK_CHARGE" => Enchantment::QUICK_CHARGE,
		"SOUL_SPEED" => Enchantment::SOUL_SPEED,
	];
	
	/**
	 * @return String[]
	 */
	public static function getEnchantments() : String {
		$names = [];
		foreach(self::$enchantments as $name => $id){
			$names[] = strtolower($name)." "."(".$id.")";
		}
		return implode(", ", $names);
	}
}

?>