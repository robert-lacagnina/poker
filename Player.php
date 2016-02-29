<?php
class Player {
	public $hand;

	public $playerNumber;

	private $_points;

	public function __construct($hand, $number){
		$this->hand = $hand;
		$this->playerNumber = $number;
	}
}

?>