<?php 
class Hand {
	public static $handTypes = array(
		"High Card",
		"One Pair",
		"Two Pair",
		"Three of a Kind",
		"Straight",
		"Flush",
		"Full House",
		"Four of a Kind",
		"Straight Flush",
		"Royal Flush"
		);

	public $points;

	public $cards;

	public $highCardForHand;

	public $bestHandType;

	public $bestHandCards;

	public $isFlush;

	public $isStraight;

	public function __construct() {
		$this->cards = array();
		$this->isFlush = true;
		$this->isStraight = true;
		$this->points = 0;
		$this->bestHandType = Hand::$handTypes[0];
	}

	public function acceptCard($card) {
		array_push($this->cards, $card);
	}

	//sort the hand and determine what the high card is
	private function determineHighCard(&$myCards) {
		usort($myCards, function ($a, $b) {

			if ($a["face"] == $b["face"]) {
        		return 0;
    		}

    		return ($a["face"] < $b["face"]) ? -1 : 1;
		});

		return $myCards[count($myCards) - 1];	
	}

	public function determineBestHand($communityCards) {
		$picked = $this->cards;

		//try all possible hands from community cards
		for($i = 0; $i < count($communityCards) - 2; $i++){
			for($j = $i + 1; $j < count($communityCards) - 1; $j++) {
				for($k = $j + 1; $k < count($communityCards); $k++) {
					array_push($picked, $communityCards[$i]);
					array_push($picked, $communityCards[$j]);
					array_push($picked, $communityCards[$k]);

					$this->determineHandType($picked);

					$picked = $this->cards;
				}
			}
		}

		// var_dump($this->cards);
		// var_dump($this->bestHandType);	
	}

	//figure out what kind of hand it is and set $bestHandType if it is better that current hand
	private function determineHandType($myCards) {
		$highCard = $this->determineHighCard($myCards);

		 // var_dump($myCards);
		 // echo "High card: \n";
		 // var_dump($highCard);

		$baseSuit = $myCards[0]["suit"];

		//var_dump($baseSuit);

		$currentCard = $myCards[0];

		$newPoints = 0;

		//count inital card
		$cardCount = array($currentCard['face'] => 1);

		for($i = 1; $i < count($myCards); $i++) {

			//determine if it's a flush of any kind
			if($myCards[$i]["suit"] === $baseSuit && $this->isFlush === true) {
				$this->isFlush = true;
			}
			else {
				$this->isFlush = false;
			}

			//count the number of occurences of a certain card face 
			if(!array_key_exists($myCards[$i]['face'], $cardCount)) {
				$cardCount[$myCards[$i]['face']] = 1;
			}
			else {
				$cardCount[$myCards[$i]['face']]++;
			}

			//determine if it's a straight
			if($currentCard["face"] - $myCards[$i]["face"] === 1 && $this->isStraight === true) {
				$this->isStraight = true;
			}
			else {
				$this->isStraight = false;
			}
		}

		//check for pairs, trips, quads, two pair, full house

		//$numPairs = array_count_values($cardCount)[2];

		//full house
		if(in_array(3, $cardCount) && in_array(2, $cardCount)) {
			$newPoints = 6;
		}
		//quads
		else if(in_array(4, $cardCount)) {
			$newPoints = 7;
		}
		//trips
		else if(in_array(3, $cardCount)) {
			$newPoints = 3;
		}
		else if(isset(array_count_values($cardCount)[2])) {
			//two pair
			if(array_count_values($cardCount)[2] > 1) {
				$newPoints = 2;
			}
			//one pair
			else {
				$newPoints = 1;
			}

		}

		if($this->isFlush && $this->isStraight) {
			
			//check if royal flush
			if($myCards[0]["face"] == "10") {
				$newPoints = 9;
			}
			//straight flush
			else {
				$newPoints = 8;
			}
		}
		else if ($this->isFlush) {
			$newPoints = 5;
		}
		else if ($this->isStraight) {
			$newPoints = 4;
		}

		//check if hand is better than last combination
		if($this->points <= $newPoints) {
			$this->bestHandType = Hand::$handTypes[$newPoints];
			$this->points = $newPoints;
			$this->highCardForHand = $highCard;
			$this->cards = $myCards;
		}

		// var_dump($this->bestHandType);
		// echo "high card: \n";
		// $this->printCards(array(($this->highCardForHand)));
		// echo "\n";
		// $this->printHand();
	}

	public function printHand() {
		$this->printCards($this->cards);
	}

	private function printCards($cards) {
		foreach($cards as $card) {
			switch ($card['face']) {
				case 11:
					echo "Jack of " . $card["suit"] . "\n"; 
					break;

				case 12:
					echo "Queen of " . $card["suit"] . "\n"; 
					break;

				case 13:
					echo "King of " . $card["suit"] . "\n"; 
					break;

				case 14:
					echo "Ace of " . $card["suit"] . "\n"; 
					break;

				default:
					echo $card["face"] . " of " . $card["suit"] . "\n"; 
					break;
			}
		}

		echo "\n";
	}
}


?>