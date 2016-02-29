<?php
include('Player.php');
include('Deck.php');
include('Hand.php');


$numberOfPlayers = $argv[1];

$players = array();

echo("Creating players...\n");
for($i = 0; $i <= (int)$numberOfPlayers; $i++) {
	array_push($players, new Player(new Hand()));
}

echo("Creating deck...\n");
$deck = new Deck();

echo("Shuffling deck...\n");
$deck->shuffle();

//echo "shuffled deck: \n";
// var_dump($deck);

//deal the cards to each player
for($i = 0; $i < count($players); $i++) {
	for($j = 0; $j < 2; $j++) {
		$players[$i]->hand->acceptCard($deck->dealCard());
	}
}

//deal 5 cards to the community
$community = array();

for($i = 0; $i < 5; $i++) {
	array_push($community, $deck->dealCard());
}

//determine best hand for each player

$playerCount = 1;

foreach($players as $player) {
	$player->hand->determineBestHand($community);

	echo "Player " . $playerCount . " hand: \n";

	$player->hand->printHand();

	$playerCount++;
}


?>