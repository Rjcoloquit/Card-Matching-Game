<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['playerName'] = $_POST['playerName'];
}

$playerName = $_SESSION['playerName'] ?? 'Player';
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

<div class="game-container">
    <h1>Card Matching Game</h1>
    <p>Player: <?php echo htmlspecialchars($playerName); ?></p>
    <p id="timer" class="game-timer">Time: 0s</p>
    
    <div class="game-board" id="gameBoard">
    </div>

    <button id="finishGame" class="finish-btn" style="display:none;">Finish Game</button>
    <div id="message" class="message" style="display:none;">
        <p>Congratulations, you completed the game in <span id="finalTime">0</span> seconds!</p>
    </div>
</div>

<script src="game.js"></script>
<script>
    let gameStarted = false;
    let timerElement = document.getElementById("timer");
    let finishButton = document.getElementById("finishGame");
    let messageElement = document.getElementById("message");
    let finalTimeElement = document.getElementById("finalTime");

    const images = [
        'card1.jpg', 'card2.jpg', 'card3.jpg', 'card4.jpg', 'card5.jpg',
        'card6.jpg', 'card7.jpg', 'card8.jpg', 'card9.jpg', 'card10.jpg'
    ];
    const cards = [...images, ...images];
    let flippedCards = [];
    let matchCount = 0;
    let startTime, interval;

    function shuffle(array) {
        return array.sort(() => Math.random() - 0.5);
    }

    function startGame() {
        gameStarted = true;
        startTime = Date.now();
        interval = setInterval(updateTimer, 1000);
        const shuffledCards = shuffle(cards);

        const gameBoard = document.getElementById("gameBoard");
        gameBoard.innerHTML = '';  

        shuffledCards.forEach((card, index) => {
            const cardElement = document.createElement("div");
            cardElement.classList.add("card");
            cardElement.setAttribute("data-index", index);
            cardElement.setAttribute("data-card", card);
            cardElement.style.backgroundImage = 'url("images/card-back.jpg")';
            cardElement.addEventListener("click", flipCard);
            gameBoard.appendChild(cardElement);
        });
    }

    function updateTimer() {
        const timeElapsed = Math.floor((Date.now() - startTime) / 1000);
        timerElement.textContent = "Time: " + timeElapsed + "s";
    }

    function flipCard(event) {
        if (!gameStarted || flippedCards.length === 2) return;

        const card = event.target;
        if (flippedCards.includes(card)) return;

        card.style.backgroundImage = `url('images/${card.getAttribute('data-card')}')`;
        card.classList.add('flipped');
        flippedCards.push(card);

        if (flippedCards.length === 2) {
            checkMatch();
        }
    }

    function checkMatch() {
        const [card1, card2] = flippedCards;
        if (card1.getAttribute("data-card") === card2.getAttribute("data-card")) {
            matchCount++;
            card1.classList.add('matched');
            card2.classList.add('matched');
            if (matchCount === 10) {
                clearInterval(interval);
                finalTimeElement.textContent = Math.floor((Date.now() - startTime) / 1000);
                finishButton.style.display = "inline-block";
            }
        } else {
            setTimeout(() => {
                card1.style.backgroundImage = 'url("images/card-back.jpg")';
                card2.style.backgroundImage = 'url("images/card-back.jpg")';
                card1.classList.remove('flipped');
                card2.classList.remove('flipped');
            }, 1000);
        }
        flippedCards = [];
    }

    finishButton.addEventListener("click", () => {
        const timeTaken = Math.floor((Date.now() - startTime) / 1000);
        alert("You finished in " + timeTaken + " seconds!");

        const playerName = '<?php echo htmlspecialchars($playerName); ?>';
        const score = { name: playerName, time: timeTaken, timestamp: new Date().toLocaleString() };
        
        fetch('score.php', {
            method: 'POST',
            body: JSON.stringify(score),
            headers: { 'Content-Type': 'application/json' }
        }).then(() => {
            messageElement.style.display = 'block';
            setTimeout(() => {
                window.location.href = "index.php";
            }, 2000);
        });
    });

    window.onload = startGame;
</script>

</body>
</html>
