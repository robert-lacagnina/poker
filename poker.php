<?php
include('Player.php');
include('Deck.php');
include('Hand.php');


$numberOfPlayers = $argv[1];

$players = array();

echo("Creating players...\n");
for($i = 0; $i < $numberOfPlayers; $i++) {
	array_push($players, new Player(new Hand()));
}

echo("Creating deck...\n");
$deck = new Deck();

echo("Shuffling deck...\n");
$deck->shuffle();

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
foreach($players as $player) {
	$player->hand->determineBestHand($community);
}

$playerCount = 1;

//break ties and determine winner
$scores = array();
foreach($players as $player) {
	echo "Player " . $playerCount . " results: \n";
	echo $player->hand->bestHandType . "\n";
	$player->hand->printHand();

	$scores[$playerCount] = $player->hand->points;
	//array_push($scores, $player->hand->points);

	$playerCount++;
}

asort($scores);

$winner = $scores[count($scores)];
echo "Player " . ($winner + 1) . " wins!\n";


?>