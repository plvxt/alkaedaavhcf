<?php

namespace alkaedaav\kit;

use pocketmine\item\Item;

class Kit
{

    /** @var Item[] */
    protected array $items = [];

    /** @var Item[] */
    protected array $armorItems = [];

    /** @var string */
    protected string $name;

    /** @var string */
    protected string $permission;

    /** @var string */
    protected string $nameFormat;

    /** @var Item|null */
    protected ?Item $representativeItem;

    /**
     * Kit constructor.
     * @param string $name
     * @param array $items
     * @param array $armorItems
     * @param string $permission
     * @param string $nameFormat
     * @param Item|null $representativeItem
     */
    public function __construct(string $name, array $items, array $armorItems, string $permission, string $nameFormat, ?Item $representativeItem = null)
    {
        $this->name = $name;
        $this->items = $items;
        $this->armorItems = $armorItems;
        $this->permission = $permission;
        $this->nameFormat = $nameFormat;
        $this->representativeItem = $representativeItem;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Item[] $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return Item[]
     */
    public function getArmorItems(): array
    {
        return $this->armorItems;
    }

    /**
     * @param Item[] $armorItems
     */
    public function setArmorItems(array $armorItems)
    {
        $this->armorItems = $armorItems;
    }

    /**
     * @return string
     */
    public function getPermission(): string
    {
        return $this->permission;
    }

    /**
     * @param string $permission
     */
    public function setPermission(string $permission)
    {
        $this->permission = $permission;
    }

    /**
     * @return string
     */
    public function getNameFormat(): string
    {
        return str_replace("&", "§", $this->nameFormat);
    }

    /**
     * @param string $nameFormat
     */
    public function setNameFormat(string $nameFormat)
    {
        $this->nameFormat = $nameFormat;
    }

    /**
     * @return Item|null
     */
    public function getRepresentativeItem(): ?Item
    {
        return $this->representativeItem;
    }

    /**
     * @param Item|null $representativeItem
     */
    public function setRepresentativeItem(?Item $representativeItem): void
    {
        $this->representativeItem = $representativeItem;
    }
}