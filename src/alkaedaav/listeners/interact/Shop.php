<?php

namespace alkaedaav\listeners\interact;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\Task\delayedtask\ShopRestoreDelayed;
use alkaedaav\shop\ShopManager;

use alkaedaav\utils\Translator;

use pocketmine\event\Listener;
use pocketmine\math\Vector3;

use pocketmine\tile\Sign;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\item\{ItemFactory, Item};

use pocketmine\event\block\{BlockBreakEvent, SignChangeEvent};
use pocketmine\block\{SignPost, WallSign};
use pocketmine\event\player\PlayerInteractEvent;

class Shop implements Listener {
	
	const BUY = "BUY", SELL = "SELL";
	
	/**
	 *  Shop Constructor.
	 */
	public function __construct(){
		
	}
	
	/**
	 * @param PlayerInteractEvent $event
	 * @return void
	 */
	public function onInteractEvent(PlayerInteractEvent $event) : void {
		$player = $event->getPlayer();
		$block = $event->getBlock();
		if($block instanceof SignPost||$block instanceof WallSign){
			if(ShopManager::isShop(Translator::vector3ToString($block))){
				$shop = ShopManager::getShop(Translator::vector3ToString($block));
				if($shop->getType() === self::SELL){
					if($player->getInventory()->contains($item = Item::get($shop->getId(), $shop->getDamage(), $shop->getAmount()))){
						$player->getInventory()->removeItem($item);
						$player->addBalance($shop->getPrice());

						$player->sendMessage(str_replace(["&", "{itemName}"], ["§", $item->getName()], Loader::getConfiguration("messages")->get("shop_successfully_sold_the_item")));
					}else{
						$player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("shop_dont_have_that_item")));
					}
				}
				if($shop->getType() === self::BUY){
					if($player->getBalance() < $shop->getPrice()){
						$player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("shop_dont_have_money_to_buy_the_item")));
						$event->setCancelled(true);
						return;
					}
					if(!$player->getInventory()->canAddItem(ItemFactory::get($shop->getId(), $shop->getDamage()))){
						$player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("shop_inventory_is_full")));
						$event->setCancelled(true);
						return;
					}
					$item = Item::get($shop->getId(), $shop->getDamage(), $shop->getAmount());
					$player->getInventory()->addItem($item);
					$player->reduceBalance($shop->getPrice());

					$player->sendMessage(str_replace(["&", "{itemName}"], ["§", $item->getName()], Loader::getConfiguration("messages")->get("shop_correctly_bought_the_item")));
				}
			}
		}
	}
	
	/**
	 * @param BlockBreakEvent $event
	 * @return void
	 */
	public function onBlockBreak(BlockBreakEvent $event) : void {
		$player = $event->getPlayer();
		$block = $event->getBlock();
		if(ShopManager::isShop(Translator::vector3ToString($block))){
			if($player->isOp()){
				ShopManager::removeShop(Translator::vector3ToString($block));
				$player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("shop_remove_correctly")));
			}
		}
	}
	
	/**
	 * @param SignChangeEvent $event
	 * @return void
	 */
	public function onSignChangeEvent(SignChangeEvent $event) : void {
		$player = $event->getPlayer();
        $block = $event->getBlock();
		if($player->isOp()){
			//if($event->getLine(0) == "" || $event->getLine(1) == "" || $event->getLine(2) == "" || $event->getLine(3) == "") return;
			if(strtolower($event->getLine(0)) === "buy"){
				if ($event->getLine(1) == "" || $event->getLine(2) == "" || $event->getLine(3) == "") return;
				$item = Item::fromString($event->getLine(1));
				if(!is_numeric($event->getLine(2)) && !is_numeric($event->getLine(3))){
					$player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("shop_has_an_error")));
					return;
				}

			    $shopData = [
			        "shop_type" => self::BUY,
                    "shop_id" => $item->getId(),
                    "shop_damage" => $item->getDamage(),
                    "shop_amount" => $event->getLine(2),
                    "shop_price" => $event->getLine(3),
                    "position" => Translator::vector3ToString($block),
                ];
				ShopManager::createShop($shopData);
				$event->setLine(0, TE::GREEN."[Shop]");
				$event->setLine(1, TE::BLACK.$item->getName());
				$event->setLine(2, TE::BLACK."x".$event->getLine(2));
				$event->setLine(3, TE::BLACK."$".$event->getLine(3));

				$player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("shop_create_correctly")));
				
			}elseif(strtolower($event->getLine(0)) === "sell"){
				if ($event->getLine(1) == "" || $event->getLine(2) == "" || $event->getLine(3) == "") return;
				$item = Item::fromString($event->getLine(1));
				
				if(!is_numeric($event->getLine(2)) && !is_numeric($event->getLine(3))){
					$player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("shop_has_an_error")));
					return;
				}

                $shopData = [
                    "shop_type" => self::SELL,
                    "shop_id" => $item->getId(),
                    "shop_damage" => $item->getDamage(),
                    "shop_amount" => $event->getLine(2),
                    "shop_price" => $event->getLine(3),
                    "position" => Translator::vector3ToString($block),
                ];
                ShopManager::createShop($shopData);
				$event->setLine(0, TE::RED."[Sell]");
				$event->setLine(1, TE::BLACK.$item->getName());
				$event->setLine(2, TE::BLACK."x".$event->getLine(2));
				$event->setLine(3, TE::BLACK."$".$event->getLine(3));

				$player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("shop_create_correctly")));
			}
		}
	}
}

?>