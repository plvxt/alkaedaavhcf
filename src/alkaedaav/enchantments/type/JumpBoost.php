<?php

namespace alkaedaav\enchantments\type;

use pocketmine\utils\TextFormat as TE;

use alkaedaav\enchantments\CustomEnchantment;

use pocketmine\entity\{Effect, EffectInstance};

use pocketmine\item\enchantment\Enchantment;

class JumpBoost extends CustomEnchantment {

    /**
     * JumpBoost Constructor.
     */
    public function __construct(){
        parent::__construct($this->getId(), $this->getName(), self::RARITY_COMMON, self::SLOT_ARMOR, self::SLOT_NONE, 2);
    }

    /**
     * @return Int
     */
    public function getId() : Int {
        return 40;
    }

    /**
     * @return String
     */
    public function getName() : String {
        return "Jump Boost";
    }
    
    /**
     * @return String
     */
    public function getNameWithFormat() : String {
    	return TE::RESET.TE::GREEN."Jump Boost I";
    }

    /**
     * @return EffectInstance
     */
    public function getEffectsByEnchantment() : EffectInstance {
        return new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 60, ($this->getMaxLevel() - 1));
    }
    
    /**
     * @return Int
     */
    public function getEnchantmentPrice() : Int {
    	return 15000;
   }
}

?>