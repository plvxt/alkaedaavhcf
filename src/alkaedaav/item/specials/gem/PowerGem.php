<?php

declare(strict_types=1);


namespace alkaedaav\item\specials\gem;


use pocketmine\item\Item;
use alkaedaav\item\specials\Custom;

class PowerGem extends Custom {

    public function __construct(?int $id, ?string $name, ?array $lore = [], ?array $enchantments = [], int $meta = 0) {
        parent::__construct($id, $name, $lore, $enchantments, $meta);
    }

}