<?php
class Deck {
	
	public static $suits = array (
		"Spades",
		"Clubs",
		"Hearts",
		"Dimonds"
		);

	public $isShuffled;

	public $deck;

	public function __construct() {
		$this->isShuffled = false;

		$this->deck = array();

		//build a sorted deck by suit
		for($i = 2; $i < 15; $i++) {
			foreach(Deck::$suits as $suit)
			array_push($this->deck, array("face" => $i, "suit" => $suit));
		}
	}

	//shuffle the deck array
	public function shuffle() {
		shuffle($this->deck);
		$this->isShuffled = true;
	}

	public function dealCard() {
		return array_shift($this->deck);
	}
}


?>