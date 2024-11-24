<?php

declare(strict_types=1);

namespace alkaedaav\kit\utils;

use libs\muqsit\invmenu\InvMenu;
use libs\muqsit\invmenu\transaction\InvMenuTransaction;
use libs\muqsit\invmenu\transaction\InvMenuTransactionResult;
use pocketmine\item\Item;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use alkaedaav\Loader;
use alkaedaav\player\Player;
use alkaedaav\kit\KitManager;
use alkaedaav\utils\Time;

final class KitUtils
{

    /**
     * @param string $data
     * @return Item
     */
    private static function converterItem(string $data): Item
    {
        $data = explode(':', $data);
        return Item::get((int) $data[0], (int) $data[1]);
    }
    
    /**
     * @param Player $player
     * @param string $kitName
     * @param Item $item
     * @return Item
     */
    private static function editItem(Player $player, string $kitName, Item $item): Item
    {
        $data = Loader::getInstance()->getConfig()->get('kits');
        $kit = KitManager::getKit($kitName);
        
        $item->setCustomName($kit->getNameFormat());
        $item->setLore(array_map(function (mixed $text) use ($player, $kitName) {
            $player_cooldown_text = $player->getTimeKitRemaining($kitName) > time() ? Time::getTime($player->getTimeKitRemaining($kitName)) : 'N/D';
            $kit_cooldown_text = Time::getTime(time() + (10 * 60));
            $new_text = str_replace(['{kit_cooldown}', '{player_cooldown}'], [$kit_cooldown_text, $player_cooldown_text], $text);
            return TextFormat::colorize($new_text);
        }, $data['item.kit.lore']));
        $namedtag = $item->getNamedTag();
        $namedtag->setString('kit_name', $kitName);
        $item->setNamedTag($namedtag);
        return $item;
    }

    /**
     * @param Player $player
     */
    public static function openMenuKits(Player $player): void
    {
        $data = Loader::getInstance()->getConfig()->get('kits');
        $data_menu = (new Config(Loader::getInstance()->getDataFolder() . 'backup/kit_menu.json', Config::JSON))->getAll();

        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $menu->setName(TextFormat::colorize($data['menu.name']));
        
        for ($i = 0; $i < 54; $i++) {
            if (!isset($data_menu[$i]))
                $menu->getInventory()->setItem($i, self::converterItem($data['item.slot.empty']));
            else {
                $kit = KitManager::getKit($data_menu[$i]);
                
                if ($kit != null)
                    $menu->getInventory()->setItem($i, self::editItem($player, $data_menu[$i], ($kit->getRepresentativeItem() != null ? $kit->getRepresentativeItem() : Item::get(310))));
            }
        }
        
        $menu->setListener(function (InvMenuTransaction $transaction): InvMenuTransactionResult {
            $player = $transaction->getPlayer();
            $item = $transaction->getItemClicked();
            
            if ($item->getNamedTag()->hasTag('kit_name')) {
                $kit = KitManager::getKit($item->getNamedTag()->getString('kit_name'));
                $player->removeWindow($transaction->getAction()->getInventory());
                
                if (!$player->hasPermission($kit->getPermission())) {
                    $player->sendMessage(TextFormat::RED . 'You have not permissions to select kit!');
                    return $transaction->discard();
                }
                
                if (!$player->isGodMode()) {
                    if ($player->getTimeKitRemaining($kit->getName()) > time()) {
                        $player->sendMessage(str_replace(["&", "{time}"], [">", Time::getTime($player->getTimeKitRemaining($kit->getName()))], Loader::getConfiguration("messages")->get("function_cooldown")));
                        return $transaction->discard();
                    }
                    $player->resetKitTime($kit->getName());
                }
                
                foreach ($kit->getItems() as $slot => $item) {
                    if (!$player->getInventory()->canAddItem($item))
                        $player->dropItem($item);
                    else
                        $player->getInventory()->addItem($item);
                }
                $armor = $kit->getArmorItems();
                
                for ($i = 0; $i < 4; $i++) {
                    if (isset($armor[$i])) {
                        if ($player->getArmorInventory()->getItem($i)->isNull())
                            $player->getArmorInventory()->setItem($i, $armor[$i]);
                        else {
                            if ($player->getInventory()->canAddItem($armor[$i]))
                                $player->getInventory()->addItem($armor[$i]);
                            else
                                $player->dropItem($armor[$i]);
                        }
                    }
                }
            }
            return $transaction->discard();
        });
        $menu->send($player);
    }
    
    /**
     * @param Player $player
     */
    public static function openMenuEditKits(Player $player): void
    {
        $config_menu = new Config(Loader::getInstance()->getDataFolder() . 'backup/kit_menu.json', Config::JSON);
        $data_menu = $config_menu->getAll();

        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $menu->setName(TextFormat::colorize('&e[KIT] Edit the organization'));
        
        foreach ($data_menu as $slot => $kitName) {
            $kit = KitManager::getKit($kitName);
            
            if ($kit != null) $menu->getInventory()->setItem($slot, self::editItem($player, $kitName, ($kit->getRepresentativeItem() != null ? $kit->getRepresentativeItem() : Item::get(310))));
        }
        
        $menu->setListener(function (InvMenuTransaction $transaction): InvMenuTransactionResult {
            $action = $transaction->getAction();
            //$inventory = $transaction->getTransaction()->getInventory();
            $item = $transaction->getItemClickedWith();
            
            if (!$item->isNull() && !$item->getNamedTag()->hasTag('kit_name'))
                return $transaction->discard();
            return $transaction->continue();
        });
        $menu->setInventoryCloseListener(function (Player $player, $inventory) use ($config_menu): void {
            $data = [];
            $contents = $inventory->getContents();
            
            foreach ($contents as $slot => $item) {
                $kit_name = $item->getNamedTag()->getString('kit_name');
                
                if (KitManager::getKit($kit_name) != null)
                    $data[$slot] = $kit_name;
            }
            $config_menu->setAll($data);
            $config_menu->save();
            
            $player->sendMessage(TextFormat::colorize('&aYou have successfully edited the kit organization'));
        });
        $menu->send($player);
    }
}