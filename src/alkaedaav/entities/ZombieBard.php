<?php

namespace alkaedaav\entities; 

use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Entity;
use pocketmine\entity\hostile\Zombie;
use pocketmine\item\ItemFactory;
use alkaedaav\player\Player;
use pocketmine\Server;

class ZombieBard extends Zombie
{
	
	/** @var string|null */
	private $player;
	
	/** @var array */
	private $effectsHold = [
		Effect::REGENERATION => [5 * 20, 2],
		Effect::STRENGTH => [5 * 20, 1],
		Effect::DAMAGE_RESISTANCE => [5 * 10, 2]
	];
	
	/** @var int */
	private $duration = 20;
	
	public function __construct(Level $level, CompoundTag $nbt, string $player = null)
	{
		$this->player = $player;
		parent::__construct($level, $nbt);
	}
	
	private function getBardFunction(): void
	{
		$player = Server::getInstance()->getPlayer($this->player);
		
		if ($player instanceof Player) {
			if ($player->distance($this) <= 10) {
				$player->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 5 * 20, 0));
				$player->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 5 * 20, 0));
				$player->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 5 * 20, 0));
				$player->addEffect(new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 5 * 20, 0));
			}
		}
	}
	
	private function holdEffect(): void
	{
		$player = Server::getInstance()->getPlayer($this->player);
		
		if ($player instanceof Player) {
			if ($player->distance($this) <= 10) {
				$random = [Effect::REGENERATION, Effect::STRENGTH, Effect::DAMAGE_RESISTANCE];
				$effectId = $random[mt_rand(0, 2)];
				$data = $this->effectsHold[$effectId];
				
				$player->addEffect(new EffectInstance(Effect::getEffect($effectId), (int) $data[0], (int) $data[1]));
			}
		}
	}
	
	protected function initEntity(): void
	{
		parent::initEntity();
		#$player = Server::getInstance()->getPlayer($this->player);
		
		if ($this->player == null) $this->close();
		
		# Set armor
		$this->getArmorInventory()->setContents([
			0 => ItemFactory::get(314),
			1 => ItemFactory::get(315),
			2 => ItemFactory::get(316),
			3 => ItemFactory::get(317)
		]);
		
		# Tags
		$this->setNameTag("§f".$this->player."\n"."§c§lZOMBIE-BARD§r"."\n"."§aHealth: ".$this->getHealth());
		$this->setNameTagVisible(true);
		$this->setNameTagAlwaysVisible(true);
		
		# Effects
		$this->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 99999, 1));
        $this->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 99999, 1));
	}
	
	public function onUpdate(int $currentTick): bool
	{
		
		if ($this->player == null) {
			$this->close();
			return false;
		}
		
		if ($currentTick % 20 == 0) {
			$this->duration--;
			$this->getBardFunction();
			
			if ($this->duration == 0)
				$this->close();
		}
		
		if ($currentTick % 120 == 0)
			$this->holdEffect();
		return parent::onUpdate($currentTick);
	}
	
    public function getDrops(): array
    {
        return [];
    }
    
    public function getXpDropAmount(): int
    {
		return 0;
    }
}
