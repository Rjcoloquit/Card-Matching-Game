<?php
$data = json_decode(file_get_contents('scores.json'), true);
$score = json_decode(file_get_contents('php://input'), true);
$data[] = $score;

usort($data, function($a, $b) {
    return $a['time'] - $b['time'];
});

file_put_contents('scores.json', json_encode($data));
?>
