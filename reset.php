<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $filePath = 'scores.json';

    if (file_exists($filePath)) {
        file_put_contents($filePath, json_encode([]));

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
