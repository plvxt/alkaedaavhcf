<?php

namespace alkaedaav\item\specials;

use pocketmine\item\{Item, ProjectileItem};

use pocketmine\Player;
use pocketmine\math\Vector3;

abstract class CustomProjectileItem extends ProjectileItem {
	
	/**
	 * CustomProjectileItem Constructor.
	 * @param Int $id
	 * @param String $name
	 * @param Array $lore
	 * @param Array $enchantments
	 * @param Int $meta
	 */
	public function __construct(?Int $id, ?String $name, ?Array $lore = [], ?Array $enchantments = [], Int $meta = 0){
		$this->setCustomName($name);
		$this->setLore($lore);
		if(!empty($enchantments)){
			foreach($enchantments as $enchant){
				$this->addEnchantment($enchant);
			}
		}
		parent::__construct($id, $meta);
	}
	
	/**
	 * @return String
	 */
	public function getProjectileEntityType() : String {
		return "";
	}
	
	/**
	 * @return float
	 */
	public function getThrowForce() : float {
		return 0;
	}
}

?>