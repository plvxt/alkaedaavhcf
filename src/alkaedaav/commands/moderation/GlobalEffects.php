<?php

namespace alkaedaav\commands\moderation;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\entity\{Effect, EffectInstance};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class GlobalEffects extends PluginCommand {
	
	/**
	 * GlobalEffects Constructor.
	 */
	public function __construct(){
		parent::__construct("geffects", Loader::getInstance());
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if(!$sender->isOp()){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
		}
		$fire_resistance = new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 20 * 50000, 1);
        $speed = new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 50000, 1);
        $night_vision = new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 20 * 50000, 1);
        $invisibility = new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 20 * 50000, 1);
        $strength = new EffectInstance(Effect::getEffect(Effect::STRENGTH), 20 * 50000, 1);
		foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
			$player->addEffect($fire_resistance);
            $player->addEffect($speed);
            $player->addEffect($night_vision);
            $player->addEffect($invisibility);
            $player->addEffect($strength);
		}
		$sender->sendMessage(TE::GREEN."All effects for the ffa were given to a total of players ".TE::BOLD.TE::GOLD.count(Loader::getInstance()->getServer()->getOnlinePlayers()));
	}
}

?>