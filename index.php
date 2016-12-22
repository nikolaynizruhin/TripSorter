<?php

require('TripSorter.php');

$cards = json_decode(file_get_contents('php://input'), true);
// $cards = require('cards.php');

$tripSorter = new TripSorter($cards);
$tripSorter->sort();
$cards = $tripSorter->formatting();

header('Content-Type: application/json');
echo json_encode($cards);
