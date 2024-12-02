<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Matching Game</title>
    <link rel="stylesheet" href="game.css">
</head>
<body>

<div class="main-container">
    <h1>Card Matching Game</h1>
    
    <p class="welcome-message">Welcome to the Card Matching Game! Enter your name to start playing.</p>
    
    <form action="game.php" method="POST" class="name-form">
        <input type="text" name="playerName" placeholder="Enter your name" required class="name-input">
        <button type="submit" class="start-btn">Start Game</button>
    </form>
    
    <div class="leaderboard-container">
        <h2>Leaderboard</h2>
        <ul class="leaderboard-list">
        </ul>
        <button id="resetLeaderboard" class="reset-btn">Reset Leaderboard</button>
    </div>
</div>

<script>
    fetch('scores.json')
        .then(response => response.json())
        .then(scores => {
            const leaderboardList = document.querySelector('.leaderboard-list');
            leaderboardList.innerHTML = '';
            scores.sort((a, b) => a.time - b.time);
            scores.forEach(score => {
                const listItem = document.createElement('li');
                listItem.textContent = `${score.name} - ${score.time}s - ${score.timestamp}`;
                leaderboardList.appendChild(listItem);
            });
        });

    document.getElementById('resetLeaderboard').addEventListener('click', () => {
        if (confirm('Are you sure you want to reset the leaderboard?')) {
            fetch('reset_scores.php', {
                method: 'POST'
            }).then(() => {
                alert('Leaderboard reset successfully!');
                window.location.reload();
            });
        }
    });
</script>

</body>
</html>
