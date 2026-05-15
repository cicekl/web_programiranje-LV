<?php
include "includes/auth.php";
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies Chart</title>
    <link rel="stylesheet" href="public/style/grafikon_style.css">
</head>

<body>

<header>
    <h1>Filmovi</h1>
</header>

<header>
    <nav aria-labelledby="primarna-navigacija" role="navigation">
        <ul>
            <li class="dropdown">
                <button class="nav-toggle" aria-label="Otvori navigaciju" type="button">
                    ☰
                </button>

                <ul class="dropdown-content">
                    <li><a href="index.php">Početna</a></li>
                    <li><a href="grafikon.php">Grafikon</a></li>
                    <li><a href="galerija.php">Slike</a></li>
                    <li><a href="top10.php">Top 10</a></li>

                    <?php if (isLoggedIn()): ?>
                        <li><a href="moja_videoteka.php">Moja videoteka</a></li>
                        <li><a href="logout.php">Odjava</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Prijava</a></li>
                        <li><a href="register.php">Registracija</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>
    </nav>
</header>

<main>
    <h1>Filmovi po žanru</h1>

    <div class="simple-bar-chart">
        <div class="item" style="--val: 32; --clr: var(--drama)">
            <div class="label">Drama</div>
            <div class="value">32%</div>
        </div>

        <div class="item" style="--val: 26; --clr: var(--komedija)">
            <div class="label">Komedija</div>
            <div class="value">26%</div>
        </div>

        <div class="item" style="--val: 10; --clr: var(--akcija)">
            <div class="label">Akcija</div>
            <div class="value">10%</div>
        </div>

        <div class="item" style="--val: 32; --clr: var(--ostali)">
            <div class="label">Ostali</div>
            <div class="value">32%</div>
        </div>
    </div>
</main>

<footer>
    <p>&copy; 2026. Web Programiranje. Sva prava pridržana.</p>
</footer>

</body>
</html>