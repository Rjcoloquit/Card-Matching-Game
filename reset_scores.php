<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents('scores.json', json_encode([]));
    echo "Leaderboard reset successfully!";
}
?>
