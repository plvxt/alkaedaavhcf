<?php

namespace alkaedaav\player;

use alkaedaav\{Loader, Factions};

use pocketmine\level\Position;
use alkaedaav\enchantments\CustomEnchantment;

use pocketmine\math\Vector3;
use pocketmine\level\Level;

use pocketmine\utils\{Binary, Internet, Config, TextFormat as TE, TextFormat};
use pocketmine\item\{Item, ItemIds};
use pocketmine\entity\{Effect, EffectInstance};

use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\CommandData;
use pocketmine\network\mcpe\protocol\types\CommandParameter;
use pocketmine\network\mcpe\protocol\types\CommandEnum;
use pocketmine\network\mcpe\protocol\types\CommandEnumConstraint;
use pocketmine\network\mcpe\protocol\ScriptCustomEventPacket;

class Player extends \pocketmine\Player {

    const LEADER = "Leader", CO_LEADER = "Co_Leader", MEMBER = "Member";

    const FACTION_CHAT = "Faction", PUBLIC_CHAT = "Public";

    /** @var Int */
    protected $bardEnergy = 0, $archerEnergy = 0;

    /** @var Int */
    protected $combatTagTime = 0;

    /** @var Int */
    protected $enderPearlTime = 0;

    /** @var Int */
    protected $stormBreakerTime = 0;

    /** @var Int  */
    protected $potionCounterTime = 0;

    /** @var Int */
    protected $antiTrapperTime = 0;
    
    protected $berserkTime = 0;
    
    protected $bardTime = 0;
    
    protected $rogueTime = 0;

    /** @var Int */
    protected $eggTime = 0;
    
    /** @var Int */
    protected $archerTagTime = 0;
    
    /** @var Int */
    protected $goldenAppleTime = 0;

    /** @var Int */
    protected $playerClaimCost = 0;
    
    protected $mageEnergy = 0;

    /** @var Int */
    protected $movementTime = 0;

    /** @var Int */
    protected $teleportHomeTime = 0, $teleportStuckTime = 0, $logoutTime = 0;
    
    /** @var Int */
    protected $invincibilityTime = 0;

    /** @var Int */
    protected $specialItemTime = 0;
    /** @var bool */
    protected $godMode = false;

    /** @var bool */
    protected $combatTag = false;

    /** @var bool */
    protected $enderPearl = false, $prePearl = false;

    /** @var bool */
    protected $stormBreaker = false;

    /** @var bool  */
    protected $potionCounter = false;
    
    protected $berserkItem = false;
    
    protected $bardItem = false;
    
    protected $rogueItem = false;

    /** @var bool */
    protected $antiTrapper = false, $antiTrapperTarget = false;

    /** @var bool */
    protected $egg = false;
    
    /** @var bool */
    protected $archerTag = false;
    
    /** @var bool */
    protected $goldenApple = false;

    /** @var bool */
    protected $autoFeed = false;

    /** @var bool */
    protected $canInteract = false;
    
    /** @var bool */
    protected $viewingMap = false;

    /** @var bool */
    protected $invitation = false;
    
    /** @var bool */
    protected $invincibility = false;

    /** @var bool */
    protected $specialItem = false;

    /** @var bool */
    protected $focus = false;

    /** @var bool */
    protected $teleportHome = false, $teleportStuck = false, $logout = false;
    
    /** @var String */
    protected $rank = null;

    /** @var String */
    protected $prefix = null;

    /** @var String */
    protected $chat = null;
    
    /** @var String */
    protected $currentInvite = null;
    
    /** @var String */
    protected $currentRegion = null;

    /** @var String */
    protected $focusFaction = null;

    /** @var Vector3 */
    protected $ninjaPosition = null;

    /** @var Vector3 */
    protected $prePearlPosition = null;

    /** @var array[] */
    protected $armorEffects = [];

    /** @var int */
    protected $loggerbaittime;

    /** @var Vector3|null */
    protected $ninjashear_position = null;

    /** @var int */
    protected $last_use_ninjashear_time = 0;

    /** @var int */
    protected $ninja_hits = 0;

    /** @var int */
    protected $last_use_katana_ability_time = 0;

    /** @var bool */
    protected $katana_ability = false;

    /** @var bool */
    protected $super_katana_ability = false; // Uwu

    /**
     * @param Int $currentTick
     * @return bool
     */
    public function onUpdate(Int $currentTick) : bool {
        $items = $this->getArmorInventory()->getContents();
        foreach($items as $slot => $item){
            foreach($item->getEnchantments() as $enchantment){
                if($enchantment->getType() instanceof CustomEnchantment){
                    $this->addEffect($enchantment->getType()->getEffectsByEnchantment());
                }
            }
        }
        if($this->isAutoFeed()) $this->setFood(20);
        return parent::onUpdate($currentTick);
    }
    
    /**
     * @param String $server
     * @return bool
     */
    public function transferToServer(?String $server) : bool {
    	$pk = new ScriptCustomEventPacket();
        $pk->eventName = "bungeecord:main";
		$pk->eventData = Binary::writeShort(strlen("Connect")) . "Connect" . Binary::writeShort(strlen($server)) . $server;
        $this->sendDataPacket($pk);
        return true;
    }
    
    public function setRank(?String $rank = null){
    	$this->rank = $rank;
    }
    
    /**
     * @return String
     */
    public function getRank() : String {
    	return $this->rank === null ? "Guest" : $this->rank;
    }

    /**
     * @param String $prefix
     */
    public function setPrefix(String $prefix = null){
        $this->prefix = $prefix;
    }

    /**
     * @return String|null
     */
    public function getPrefix() : ?String {
        return $this->prefix;
    }

    /**
     * @return Int|null
     */
    public function getBardEnergy() : ?Int {
        return $this->bardEnergy;
    }

    /**
     * @param Int $bardEnergy
     */
    public function setBardEnergy(Int $bardEnergy){
        $this->bardEnergy = $bardEnergy;
    }
    
    /**
     * @return Int|null
     */
    public function getArcherEnergy() : ?Int {
        return $this->archerEnergy;
    }

    /**
     * @param Int $archerEnergy
     */
    public function setArcherEnergy(Int $archerEnergy){
        $this->archerEnergy = $archerEnergy;
    }
    public function getMageEnergy(): ?int
    {
        return $this->mageEnergy;
    }

    /**
     * @param int $mageEnergy
     */
    public function setMageEnergy(int $mageEnergy)
    {
        $this->mageEnergy = $mageEnergy;
    }
    /**
     * @param Int $itemId|null
     * @return Int
     */
    public function getBardEnergyCost(Int $itemId = null) : Int {
    	$energyCost = null;
    	switch($itemId){
    		case ItemIds::SUGAR:
    		$energyCost = 20;
    		break;
    		case ItemIds::IRON_INGOT:
    		$energyCost = 30;
    		break;
    		case ItemIds::BLAZE_POWDER:
    		$energyCost = 40;
    		break;
    		case ItemIds::GHAST_TEAR:
    		$energyCost = 35;
    		break;
    		case ItemIds::FEATHER:
    		$energyCost = 30;
    		break;
    		case ItemIds::DYE:
    		$energyCost = 30;
    		break;
    		case ItemIds::MAGMA_CREAM:
    		$energyCost = 25;
    		break;
    		case ItemIds::SPIDER_EYE:
    		$energyCost = 40;
    		break;
    	}
    	return $energyCost;
    }
    
    public function getMageEnergyCost(int $itemId = null) : ?int {
        $energyCost = null;
        switch ($itemId) {
            case ItemIds::SEEDS:
            $energyCost = 35;
            break;
            case ItemIds::COAL:
            $energyCost = 25;
            break;
            case ItemIds::SPIDER_EYE:
            $energyCost = 40;
            break;
            case ItemIds::ROTTEN_FLESH:
            $energyCost = 40;
            break;
            case ItemIds::GOLD_NUGGET:
            $energyCost = 35;
            break;
            case ItemIds::DYE:
            $energyCost = 30;
            break;
        }
        return $energyCost;
    }

    /**
     * @return bool
     */
    public function isGodMode() : bool {
        return $this->godMode;
    }

    /**
     * @param bool $godMode
     */
    public function setGodMode(bool $godMode){
        $this->godMode = $godMode;
    }

    /**
     * @return bool
     */
    public function isCombatTag() : bool {
        return $this->combatTag;
    }

    /**
     * @param bool $combatTag
     */
    public function setCombatTag(bool $combatTag){
        $this->combatTag = $combatTag;
    }
    
    /**
     * @param Int $combatTagTime
     */
    public function setCombatTagTime(Int $combatTagTime){
    	$this->combatTagTime = $combatTagTime;
    }
    
    /**
     * @return Int
     */
    public function getCombatTagTime() : Int {
    	return $this->combatTagTime;
    }

    /**
     * @return bool
     */
    public function isEnderPearl() : bool {
        return $this->enderPearl;
    }
    
    /**
     * @param bool $enderPearl
     */
    public function setEnderPearl(bool $enderPearl){
        $this->enderPearl = $enderPearl;
    }
    
    /**
     * @param Int $enderPearlTime
     */
    public function setEnderPearlTime(Int $enderPearlTime){
    	$this->enderPearlTime = $enderPearlTime;
    }
    
    /**
     * @return Int
     */
    public function getEnderPearlTime() : Int {
    	return $this->enderPearlTime;
    }

    /**
     * @return bool
     */
    public function isStormBreaker() : bool {
        return $this->stormBreaker;
    }
    
    /**
     * @param bool $stormBreaker
     */
    public function setStormBreaker(bool $stormBreaker){
        $this->stormBreaker = $stormBreaker;
    }
    
    /**
     * @param Int $stormBreakerTime
     */
    public function setStormBreakerTime(Int $stormBreakerTime){
    	$this->stormBreakerTime = $stormBreakerTime;
    }
    
    /**
     * @return Int
     */
    public function getStormBreakerTime() : Int {
    	return $this->stormBreakerTime;
    }

    /**
     * @return bool
     */
    public function isAntiTrapperTarget() : bool {
        return $this->antiTrapperTarget;
    }

    /**
     * @param bool $antiTrapperTarget
     */
    public function setAntiTrapperTarget(bool $antiTrapperTarget){
        $this->antiTrapperTarget = $antiTrapperTarget;
    }

    /**
     * @return bool
     */
    public function isAntiTrapper() : bool {
        return $this->antiTrapper;
    }
    
    /**
     * @param bool $antiTrapper
     */
    public function setAntiTrapper(bool $antiTrapper){
        $this->antiTrapper = $antiTrapper;
    }
    
    /**
     * @param Int $antiTrapperTime
     */
    public function setAntiTrapperTime(Int $antiTrapperTime){
    	$this->antiTrapperTime = $antiTrapperTime;
    }
    
    /**
     * @return Int
     */
    public function getAntiTrapperTime() : Int {
    	return $this->antiTrapperTime;
    }
    
    /**
     * @return bool
     */
    public function isArcherTag() : bool {
    	return $this->archerTag;
    }
    
    /**
     * @param bool $archerTag
     */
    public function setArcherTag(bool $archerTag){
    	$this->archerTag = $archerTag;
    }
    
    /**
     * @param Int $archerTagTime
     */
    public function setArcherTagTime(Int $archerTagTime){
    	$this->archerTagTime = $archerTagTime;
    }
    
    /**
     * @return Int
     */
    public function getArcherTagTime() : Int {
    	return $this->archerTagTime;
    }

    /**
     * @return bool
     */
    public function isEgg() : bool {
        return $this->egg;
    }

    /**
     * @param bool $egg
     */
    public function setEgg(bool $egg){
        $this->egg = $egg;
    }

    /**
     * @param Int $eggTime
     */
    public function setEggTime(Int $eggTime){
        $this->eggTime = $eggTime;
    }

    /**
     * @return Int
     */
    public function getEggTime() : Int {
        return $this->eggTime;
    }

    /**
     * @return bool
     */
    public function isSpecialItem() : bool {
        return $this->specialItem;
    }

    /**
     * @param bool $specialItem
     */
    public function setSpecialItem(bool $specialItem){
        $this->specialItem = $specialItem;
    }

    /**
     * @param Int $specialItemTime
     */
    public function setSpecialItemTime(Int $specialItemTime){
        $this->specialItemTime = $specialItemTime;
    }

    /**
     * @return Int
     */
    public function getSpecialItemTime() : Int {
        return $this->specialItemTime;
    }
    /**
     * @return Int
     */
     public function isBerserkItem() : bool {
        return $this->berserkItem;
    }
    
    /**
     * @param bool $antiTrapper
     */
    public function setBerserkItem(bool $berserkItem){
        $this->berserkItem = $berserkItem;
    }
    
    /**
     * @param Int $antiTrapperTime
     */
    public function setBerserkTime(Int $berserkTime){
    	$this->berserkTime = $berserkTime;
    }
    
    /**
     * @return Int
     */
    public function getBerserkTime() : Int {
    	return $this->berserkTime;
    }
    /**
     * @return Int
     */
     public function isBardItem() : bool {
        return $this->bardItem;
    }
    
    /**
     * @param bool $antiTrapper
     */
    public function setBardItem(bool $bardItem){
        $this->bardItem = $bardItem;
    }
    
    /**
     * @param Int $antiTrapperTime
     */
    public function setBardTime(Int $bardTime){
    	$this->bardTime = $bardTime;
    }
    
    /**
     * @return Int
     */
    public function getBardTime() : Int {
    	return $this->bardTime;
    }
    
    public function isRogueItem() : bool {
        return $this->rogueItem;
    }
    
    /**
     * @param bool $antiTrapper
     */
    public function setRogueItem(bool $rogueItem){
        $this->rogueItem = $rogueItem;
    }
    
    /**
     * @param Int $antiTrapperTime
     */
    public function setRogueTime(Int $rogueTime){
    	$this->rogueTime = $rogueTime;
    }
    
    /**
     * @return Int
     */
    public function getRogueTime() : Int {
    	return $this->rogueTime;
    }
    /**
     * @return bool
     */
    public function isPotionCounter() : bool {
        return $this->potionCounter;
    }

    /**
     * @param bool $potionCounter
     */
    public function setPotionCounter(bool $potionCounter){
        $this->potionCounter = $potionCounter;
    }

    /**
     * @param Int $potionCounterTime
     */
    public function setPotionCounterTime(Int $potionCounterTime){
        $this->potionCounterTime = $potionCounterTime;
    }

    /**
     * @return Int
     */
    public function getPotionCounterTime() : Int {
        return $this->potionCounterTime;
    }
    
    /**
     * @return bool
     */
    public function isGoldenGapple() : bool {
    	return $this->goldenApple;
    }
    
    /**
     * @param bool $goldenApple
     */
    public function setGoldenApple(bool $goldenApple){
    	$this->goldenApple = $goldenApple;
    }
    
    /**
     * @param Int $goldenAppleTime
     */
    public function setGoldenAppleTime(Int $goldenAppleTime){
    	$this->goldenAppleTime = $goldenAppleTime;
    }
    
    /**
     * @return Int
     */
    public function getGoldenAppleTime() : Int {
    	return $this->goldenAppleTime;
    }

    /**
     * @return bool
     */
    public function isAutoFeed() : bool {
        return $this->autoFeed;
    }

    /**
     * @param bool $autoFeed
     */
    public function setAutoFeed(bool $autoFeed){
        $this->autoFeed = $autoFeed;
    }

    /**
     * @return bool
     */
    public function isTeleportingHome() : bool {
        return $this->teleportHome;
    }

    /**
     * @param bool $teleportHome
     */
    public function setTeleportingHome(bool $teleportHome){
        $this->teleportHome = $teleportHome;
    }

    /**
     * @param Int $teleportHomeTime
     */
    public function setTeleportingHomeTime(Int $teleportHomeTime){
        $this->teleportHomeTime = $teleportHomeTime;
    }

    /**
     * @return Int
     */
    public function getTeleportingHomeTime() : Int {
        return $this->teleportHomeTime;
    }
    
    /**
     * @return bool
     */
    public function isLogout() : bool {
        return $this->logout;
    }

    /**
     * @param bool $logout
     */
    public function setLogout(bool $logout){
        $this->logout = $logout;
    }

    /**
     * @param Int $logoutTime
     */
    public function setLogoutTime(Int $logoutTime){
        $this->logoutTime = $logoutTime;
    }

    /**
     * @return Int
     */
    public function getLogoutTime() : Int {
        return $this->logoutTime;
    }
    
    /**
     * @return bool
     */
    public function isTeleportingStuck() : bool {
        return $this->teleportStuck;
    }

    /**
     * @param bool $teleportStuck
     */
    public function setTeleportingStuck(bool $teleportStuck){
        $this->teleportStuck = $teleportStuck;
    }

    /**
     * @param Int $teleportStuckTime
     */
    public function setTeleportingStuckTime(Int $teleportStuckTime){
        $this->teleportStuckTime = $teleportStuckTime;
    }

    /**
     * @return Int
     */
    public function getTeleportingStuckTime() : Int {
        return $this->teleportStuckTime;
    }
    
    /**
     * @return bool
     */
    public function isInvincibility() : bool {
    	return $this->invincibility;
    }
    
    /**
     * @param bool $invincibility
     */
    public function setInvincibility(bool $invincibility){
    	$this->invincibility = $invincibility;
    }
    
    /**
     * @param Int $invincibilityTime
     */
    public function setInvincibilityTime(Int $invincibilityTime){
    	$this->invincibilityTime = $invincibilityTime;
    }
    
    /**
     * @return Int
     */
    public function getInvincibilityTime() : Int {
    	return $this->invincibilityTime;
    }

    /**
     * @return bool
     */
    public function isViewingMap() : bool {
    	return $this->viewingMap;
    }
    
    /**
     * @param bool $viewingMap
     */
    public function setViewingMap(bool $viewingMap){
    	$this->viewingMap = $viewingMap;
    }

    /**
     * @param mixed $movementTime
     */
    public function setMovementTime($movementTime){
        $this->movementTime = $movementTime;
    }

    /**
     * @return bool
     */
    public function isMovementTime() : bool {
        return (time() - $this->movementTime) < 0;
    }

    /**
     * @return bool
     */
    public function isInteract() : bool {
        return $this->canInteract;
    }

    /**
     * @param bool $canInteract
     */
    public function setInteract(bool $canInteract){
        $this->canInteract = $canInteract;
    }
    
    /**
     * @return void
     */
    public function addTool() : void {
    	$item = Item::get(ItemIds::GOLD_HOE, 0, 1)->setCustomName(TE::DARK_PURPLE."Claim Tool")->setLore([TE::GRAY."Touch First Position, Touch Second Position!"]);
		$this->getInventory()->addItem($item);
    }
    
    /**
     * @return void
     */
    public function removeTool() : void {
    	$this->getInventory()->removeItem(Item::get(ItemIds::GOLD_HOE, 0, 1));
    }

    /**
     * @return Int
     */
    public function getClaimCost() : Int {
        return $this->playerClaimCost;
    }

    /**
     * @param Int $playerClaimCost
     */
    public function setClaimCost(Int $playerClaimCost){
        $this->playerClaimCost = $playerClaimCost;
    }

    /**
     * @param String $chat
     */
    public function setChat(String $chat){
        $this->chat = $chat;
    }

    /**
     * @return String
     */
    public function getChat() : ?String {
        return $this->chat === null ? "Public" : $this->chat;
    }

    /**
     * @return bool
     */
    public function isInvited() : bool {
        return $this->invitation;
    }

    /**
     * @param bool $invitation
     */
    public function setInvite(bool $invitation){
        $this->invitation = $invitation;
    }
    
    /**
     * @return String
     */
    public function getCurrentInvite() : String {
    	return $this->currentInvite;
    }
    
    /**
     * @param String $currentInvite
     */
    public function setCurrentInvite(String $currentInvite){
    	$this->currentInvite = $currentInvite;
    }

    /**
     * @return bool
     */
    public function isFocus() : bool {
        return $this->focus;
    }

    /**
     * @param bool $focus
     */
    public function setFocus(bool $focus){
        $this->focus = $focus;
    }

    /**
     * @param String $focusFaction
     */
    public function setFocusFaction(String $focusFaction){
        $this->focusFaction = $focusFaction;
    }

    /**
     * @return void
     */
    public function getFocusFaction() : String {
        return $this->focusFaction;
    }

    /**
     * @return String
     */
    public function getRegion() : String {
    	return $this->currentRegion === null ? "Unknown" : $this->currentRegion;
    }
    
    /**
     * @param String $currentRegion
     */
    public function setRegion(String $currentRegion){
    	$this->currentRegion = $currentRegion;
    }
    
    /**
     * @return String
     */
    public function getCurrentRegion() : String {
    	if(Factions::isSpawnRegion($this)){
    		return "Spawn";
    	}else{
    		return Factions::getRegionName($this) ?? "Warzone";
    	}
    }
    
    /**
     * @return Int
     */
    public function getLives() : Int {
        return PlayerBase::getData($this->getName())->get("lives") === null ? 0 : PlayerBase::getData($this->getName())->get("lives");
    }
    
    /**
     * @param Int $lives
     */
    public function setLives(Int $lives){
        PlayerBase::setData($this->getName(), "lives", $lives);
    }

    /**
     * @param Int $lives
     */
    public function reduceLives(Int $lives){
        PlayerBase::setData($this->getName(), "lives", $this->getLives() - $lives);
    }

    /**
     * @param Int $lives
     */
    public function addLives(Int $lives){
        PlayerBase::setData($this->getName(), "lives", $this->getLives() + $lives);
    }

    /**
     * @return Int
     */
    public function getBalance() : Int {
    	return PlayerBase::getData($this->getName())->get("balance") === null ? 0 : PlayerBase::getData($this->getName())->get("balance");
    }

    /**
     * @param Int $balance
     */
    public function setBalance(Int $balance){
    	PlayerBase::setData($this->getName(), "balance", $balance);
    }
    

    /**
     * @param Int $balance
     */
    public function reduceBalance(Int $balance){
        PlayerBase::setData($this->getName(), "balance", $this->getBalance() - $balance);
    }

    /**
     * @param Int $balance
     */
    public function addBalance(Int $balance){
        PlayerBase::setData($this->getName(), "balance", $this->getBalance() + $balance);
    }

    /**
     * @param Int $kills
     */
    public function setKills(Int $kills){
        PlayerBase::setData($this->getName(), "kills", $kills);
    }

    /**
     * @return Int
     */
    public function getKills() : Int {
        return PlayerBase::getData($this->getName())->get("kills") === null ? 0 : PlayerBase::getData($this->getName())->get("kills");
    }

    /**
     * @param Int $kills
     */
    public function reduceKills(Int $kills = 1){
        PlayerBase::setData($this->getName(), "kills", $this->getKills() - $kills);
    }
    
    /**
     * @param Int $kills
     */
    public function addKills(Int $kills = 1){
    	PlayerBase::setData($this->getName(), "kills", $this->getKills() + $kills);
    }

    /**
     * @param String $kitName
     * @return Int
     */
    public function getTimeKitRemaining(String $kitName) : Int {
        return PlayerBase::getData($this->getName())->get($kitName);
    }

    /**
     * @param String $kitName
     */
    public function resetKitTime(String $kitName){
        PlayerBase::setData($this->getName(), $kitName, time() + (10 * 60));
    }

    /**
     * @return Int
     */
    public function getTimeBrewerRemaining() : Int {
        return PlayerBase::getData($this->getName())->get("brewer");
    }

    /**
     * @return void
     */
    public function resetBrewerTime() : void {
        PlayerBase::setData($this->getName(), "brewer", time() + (4 * 3600));
    }

    /**
     * @return Int
     */
    public function getTimeReclaimRemaining() : Int {
        return PlayerBase::getData($this->getName())->get("reclaim");
    }

    /**
     * @return void
     */
    public function resetReclaimTime() : void {
        PlayerBase::setData($this->getName(), "reclaim", time() + (1 * 86400));
    }

    /**
     * @return Int
     */
    public function getKothHostTimeRemaining() : Int {
        return PlayerBase::getData($this->getName())->get("koth_host");
    }

    /**
     * @return void
     */
    public function resetKothHostTime() : void {
        PlayerBase::setData($this->getName(), "koth_host", $this->getRank() === "Angelic" ? time() + (24 * 3600) : time() + (24 * 3600));
    }
    
    /**
     * @return bool
     */
    public function isBardClass() : bool {
    	if(!$this->isOnline()) return false;
		if($this->getArmorInventory()->getHelmet()->getId() === ItemIds::GOLD_HELMET && $this->getArmorInventory()->getChestplate()->getId() === ItemIds::GOLD_CHESTPLATE && $this->getArmorInventory()->getLeggings()->getId() === ItemIds::GOLD_LEGGINGS && $this->getArmorInventory()->getBoots()->getId() === ItemIds::GOLD_BOOTS){
			return true;
		}else{
			return false;
		}
		return false;
    }
    
    /**
     * @return bool
     */
    public function isArcherClass() : bool {
    	if(!$this->isOnline()) return false;
		if($this->getArmorInventory()->getHelmet()->getId() === ItemIds::LEATHER_HELMET && $this->getArmorInventory()->getChestplate()->getId() === ItemIds::LEATHER_CHESTPLATE && $this->getArmorInventory()->getLeggings()->getId() === ItemIds::LEATHER_LEGGINGS && $this->getArmorInventory()->getBoots()->getId() === ItemIds::LEATHER_BOOTS){
			return true;
		}else{
			return false;
		}
		return false;
    }
    
    /**
     * @return bool
     */
    public function isMinerClass() : bool {
    	if(!$this->isOnline()) return false;
		if($this->getArmorInventory()->getHelmet()->getId() === ItemIds::IRON_HELMET && $this->getArmorInventory()->getChestplate()->getId() === ItemIds::IRON_CHESTPLATE && $this->getArmorInventory()->getLeggings()->getId() === ItemIds::IRON_LEGGINGS && $this->getArmorInventory()->getBoots()->getId() === ItemIds::IRON_BOOTS){
			return true;
		}else{
			return false;
		}
		return false;
    }
    
 public function isMageClass() : bool {
    if (!$this->isOnline()) return false;
        if($this->getArmorInventory()->getHelmet()->getId() === ItemIds::GOLD_HELMET && $this->getArmorInventory()->getChestplate()->getId() === ItemIds::CHAINMAIL_CHESTPLATE && $this->getArmorInventory()->getLeggings()->getId() === ItemIds::CHAINMAIL_LEGGINGS && $this->getArmorInventory()->getBoots()->getId() === ItemIds::GOLD_BOOTS) {
            return true;
        }
        return false;
    }
    /**
     * @return bool
     */
    public function isRogueClass() : bool {
        if(!$this->isOnline()) return false;
		if($this->getArmorInventory()->getHelmet()->getId() === ItemIds::CHAINMAIL_HELMET && $this->getArmorInventory()->getChestplate()->getId() === ItemIds::CHAINMAIL_CHESTPLATE && $this->getArmorInventory()->getLeggings()->getId() === ItemIds::CHAINMAIL_LEGGINGS && $this->getArmorInventory()->getBoots()->getId() === ItemIds::CHAINMAIL_BOOTS){
			return true;
		}else{
			return false;
		}
		return false;
    }

    public function isNinjaClass(): bool {
        if(!$this->isOnline()) {
            return false;
        }
        $armor_inventory = $this->getArmorInventory();
        if(
            $armor_inventory->getHelmet()->getId() === ItemIds::GOLD_HELMET and
            $armor_inventory->getChestplate()->getId() === ItemIds::LEATHER_CHESTPLATE and
            $armor_inventory->getLeggings()->getId() === ItemIds::LEATHER_LEGGINGS and
            $armor_inventory->getBoots()->getId() === ItemIds::GOLDEN_BOOTS
        ) {
            return true;
        }
        return false;
    }
    
    /**
     * @return void
     */
    public function checkClass() : void {
        if($this->isBardClass()){
            if(!isset($this->armorEffects[$this->getName()]["Bard"])){
                $this->armorEffects[$this->getName()]["Bard"] = $this;
            }
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 240, 1));
        }elseif($this->isArcherClass()){
        	if(!isset($this->armorEffects[$this->getName()]["Archer"])){
                $this->armorEffects[$this->getName()]["Archer"] = $this;
            }
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 240, 2));
        }elseif($this->isRogueClass()){
            if(!isset($this->armorEffects[$this->getName()]["Rogue"])){
                $this->armorEffects[$this->getName()]["Rogue"] = $this;
            }
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 240, 2));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 240, 0));
        }elseif($this->isMageClass()) {
          // TODO:
          if(!isset($this->armorEffects[$this->getName()]["Mage"])){
              $this->armorEffects[$this->getName()]["Mage"] = $this;
          }
          $this->addEffect(new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 240, 1));
          $this->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 240, 1));
          $this->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 240, 2));
        }elseif($this->isMinerClass()){
        	if(!isset($this->armorEffects[$this->getName()]["Miner"])){
                $this->armorEffects[$this->getName()]["Miner"] = $this;
            }
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::HASTE), 240, 2));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 240, 1));
            if($this->getY() < 40){
                $this->addEffect(new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 240, 1));
            }
        } elseif($this->isNinjaClass()) {
            if(!isset($this->armorEffects[$this->getName()]["Ninja"])) {
                $this->armorEffects[$this->getName()]["Ninja"] = $this;
            }
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 240, 1));
        } else {
            if(isset($this->armorEffects[$this->getName()]["Bard"])){
                $this->removeEffect(Effect::SPEED);
                $this->removeEffect(Effect::REGENERATION);
                unset($this->armorEffects[$this->getName()]["Bard"]);
            }
            if(isset($this->armorEffects[$this->getName()]["Archer"])){
                $this->removeEffect(Effect::SPEED);
                $this->removeEffect(Effect::REGENERATION);
                $this->removeEffect(Effect::FIRE_RESISTANCE);
                unset($this->armorEffects[$this->getName()]["Archer"]);
            }
            if(isset($this->armorEffects[$this->getName()]["Rogue"])){
                $this->removeEffect(Effect::SPEED);
                $this->removeEffect(Effect::REGENERATION);
                $this->removeEffect(Effect::FIRE_RESISTANCE);
                unset($this->armorEffects[$this->getName()]["Rogue"]);
            }
            if(isset($this->armorEffects[$this->getName()]["Miner"])){
                $this->removeEffect(Effect::HASTE);
                $this->removeEffect(Effect::NIGHT_VISION);
                $this->removeEffect(Effect::FIRE_RESISTANCE);
                unset($this->armorEffects[$this->getName()]["Miner"]);
            }
            if(isset($this->armorEffects[$this->getName()]["Ninja"])) {
                $this->removeEffect(Effect::RESISTANCE);
                $this->removeEffect(Effect::SPEED);
                unset($this->armorEffects[$this->getName()]["Ninja"]);
            }
        }
    }

    /**
	 * @return void
	 */
	public function changeWorld() : void {
        $levelName = Loader::getDefaultConfig("LevelManager")["levelEndName"];
        if(!Loader::getInstance()->getServer()->isLevelGenerated($levelName)){
            $this->sendMessage(TE::RED.$levelName." does not exist, you must talk to the developer or owner to fix this!");
            return;
        }
        if(!Loader::getInstance()->getServer()->isLevelLoaded($levelName)){
            Loader::getInstance()->getServer()->loadLevel($levelName);
        }
        if($this->getLevel()->getFolderName() === Loader::getInstance()->getServer()->getDefaultLevel()->getFolderName()){
            $this->teleport(Loader::getInstance()->getServer()->getLevelByName($levelName)->getSafeSpawn());
        }elseif($this->getLevel()->getFolderName() === $levelName){
            if(!Factions::isSpawn($levelName)){
                $this->sendMessage(TE::RED."Oops there seems to be a bug you should talk to the developer");
                return;
            }
            $this->teleport(Factions::getSpawnLocation($levelName));
        }
    }

    /**
     * @param Item $item
     * @param bool $conditional
     */
    public function dropItem(Item $item, bool $conditional = false) : bool {
        if(!$conditional){
            parent::dropItem($item);
        }else{
            if(!$this->spawned||!$this->isAlive()){
                return false;
            }
            if($item->isNull()){
                $this->server->getLogger()->debug($this->getName()." attempted to drop a null item (".$item.")");
                return true;
            }
            $this->level->dropItem($this->add(0, 1.0, 0), $item);
            return true;
        }
        return true;
    }

    /**
     * @return void
     */
    public function addPermissionsPlayer() : void {
        $permission = Loader::getInstance()->getPermission($this);
		if($this->getRank() === "Guest"){
            $permission->setPermission("free.kit", true);
		}
        if($this->getRank() === "Sr-Admin"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
        }
		if($this->getRank() === "Admin"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Jr-Admin"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "Sr-Mod"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Mod"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Trainee"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Lunar"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Cosmos"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		    if($this->getRank() === "Donador"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "God"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "NitroBooster"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "Astral"){
            $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Saturn"){
            $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "MiniYT"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "Twitch"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "YouTuber"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "Famous"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Partner"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
    }

    public function getLoggerBaitTime(): int {
        return $this->loggerbaittime;
    }

    public function setLoggerBaitTime(bool $loggerbaittime): void {
        $this->loggerbaittime = $loggerbaittime;
    }

    /**
     * @return void
     */
    public function removePermissionsPlayer() : void {
        unset(Loader::getInstance()->permission[$this->getName()]);
    }

    /**
     * @return void
     */
    public function showCoordinates() : void {
        $pk = new GameRulesChangedPacket();
        $pk->gameRules = ["showcoordinates" => [1, true, false]];
        $this->dataPacket($pk);
    }

    public function getNinjaShearPosition(): Vector3 {
        return $this->ninjashear_position;
    }

    public function setNinjaShearPosition(?Vector3 $ninjashear_position): void {
        $this->ninjashear_position = $ninjashear_position;
    }

    public function hasNinjaShearPosition(): bool {
        return $this->ninjashear_position !== null;
    }

    public function canUseNinjaShear(): bool {
        if($this->getNinjaShearTimeElapsed() >= Loader::getDefaultConfig("Cooldowns")["NinjaShear"]) {
            return true;
        }
        return false;
    }

    private function getNinjaShearTimeElapsed(): int {
        return time() - $this->last_use_ninjashear_time;
    }

    public function updateLastUseNinjaShearTime(): void {
        $this->last_use_ninjashear_time = time();
    }

    public function getNinjaShearTimeRemaining(): int {
        return Loader::getDefaultConfig("Cooldowns")["NinjaShear"] - $this->getNinjaShearTimeElapsed();
    }

    public function addNinjaHit(): void {
        if(!$this->canEnableKatanaAbility(true)) {
            return;
        }
        $this->ninja_hits++;
        if($this->ninja_hits >= 15) {
            $this->ninja_hits = 0;
            $this->last_use_katana_ability_time = time();
            $this->katana_ability = true;

            if($this->probability(10)) {
                $this->super_katana_ability = true;
            }
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 20 * 15));
            $this->sendTitle(TextFormat::GOLD . "Katana Ability", TextFormat::YELLOW . "Ninja cooldowns has been reduced");
            return;
        }
        $this->sendMessage(TextFormat::YELLOW . "You are missing " . (15 - $this->ninja_hits) . " hits");
    }

    public function hasKatanaAbility(): bool {
        return $this->katana_ability;
    }

    public function hasSuperKatanaAbility(): bool {
        return $this->super_katana_ability;
    }

    public function removeKatanaAbility(): void {
        $this->katana_ability = false;
        $this->super_katana_ability = false;

        $this->sendTitle(TextFormat::RED . "Katana Ability", TextFormat::YELLOW . "Ninja cooldowns has been increased");
    }

    public function canEnableKatanaAbility(bool $bruh = false): bool {
        if($bruh) {
            if($this->getKatanaAbilityTimeElapsed() >= 30) {
                return true;
            }
            return false;
        }
        if($this->getKatanaAbilityTimeElapsed() >= 15) {
            return true;
        }
        return false;
    }

    public function getKatanaAbilityTimeRemaining(): int {
        return 30 - $this->getKatanaAbilityTimeElapsed();
    }

    private function getKatanaAbilityTimeElapsed(): int {
        return time() - $this->last_use_katana_ability_time;
    }

    private function probability(int $probability): bool {
        $number = mt_rand(1, 100);
        if($number <= $probability) {
            return true;
        }
        return false;
    }

}