<?php

namespace alkaedaav\packages;

class Package {

    /** @var array[] */
    protected $items = [];

    /**
     * Package Constructor.
     * @param array $items
     */
    public function __construct(Array $items){
        $this->items = $items;
    }

    /**
	 * @return array[]
	 */
	public function getItems() : Array {
		return $this->items ?? [];
	}

	/**
	 * @param array $items
	 */
	public function setItems(Array $items){
		$this->items = $items;
	}
}

?>