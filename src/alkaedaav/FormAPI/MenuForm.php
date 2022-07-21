<?php

namespace alkaedaav\FormAPI;

class MenuForm extends FormData {

	const IMAGE_TYPE_PATH = 0;
	const IMAGE_TYPE_URL = 1;

	/** @var String */
	protected $content = "";

	/** @var Array[] */
	protected $label = [];

	/**
	 * MenuForm Constructor.
	 * @param Callable $callable
	 */
	public function __construct(?callable $callable){
		parent::__construct($callable);
		$this->data["type"] = "form";
		$this->data["title"] = "";
		$this->data["content"] = $this->content;
	}

	/**
	 * @param $data
	 * @return void
	 */
	public function processDataForm(&$data) : void {
		$data = $this->label[$data] ?? null;
	}

	/**
	 * @param String $title
	 * @return void
	 */
	public function setTitle(String $title) : void {
		$this->data["title"] = $title;
	}

	/**
	 * @return String
	 */
	public function getTitle() : ?String {
		return $this->data["title"];
	}

	/**
	 * @return String
	 */
	public function getContent() : ?String {
		return $this->data["content"];
	}

	/**
	 * @param String $content
	 * @return void
	 */
	public function setContent(String $content) : void {
		$this->data["content"] = $content;
	}

	/**
	 * @param String $text
	 * @param Int $imageType
	 * @param String $imagePath
	 * @param String $labelMap
	 * @return void
	 */
	public function addButton(String $text, Int $imageType = -1, String $imagePath = "", ?String $labelMap = null) : void {
		$content = ["text" => $text];
		if($imageType !== -1){
			$content["image"]["type"] = $imageType === 0 ? "path" : "url";
			$content["image"]["data"] = $imagePath;
		}
		$this->data["buttons"][] = $content;
		$this->label[] = $labelMap ?? count($this->label);
	}
}