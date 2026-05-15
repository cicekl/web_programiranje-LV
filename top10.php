<?php
include "includes/auth.php";

$folderPath = __DIR__ . "/public/images/";
$folderUrl = "public/images/";

$allowedExtensions = ["jpg", "jpeg", "png", "webp", "gif"];

$images = [];

if (is_dir($folderPath)) {
    $files = scandir($folderPath);

    foreach ($files as $file) {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (in_array($extension, $allowedExtensions)) {
            $images[] = $file;
        }
    }
}

$images = array_slice($images, 0, 10);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 10 filmova</title>
    <link rel="stylesheet" href="public/style/style_slike.css">
</head>

<body>

<header>
    <h1>Top 10 filmova</h1>
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

<section class="galerija" aria-label="Top 10 filmova">

    <div class="galerija_slike">

        <?php if (!empty($images)): ?>
            <?php foreach ($images as $index => $image): ?>
               <?php
                    $imageUrl = $folderUrl . $image;
                    $title = "Film " . ($index + 1);
                ?>

                <figure class="galerija_slika">
                    <a href="#img<?php echo $index + 1; ?>">
                        <img 
                            src="<?php echo htmlspecialchars($imageUrl); ?>" 
                            alt="<?php echo htmlspecialchars($title); ?>" 
                            loading="lazy"
                        >
                    </a>

                    <figcaption>
                        <?php echo htmlspecialchars($title); ?>
                    </figcaption>
                </figure>

                <div id="img<?php echo $index + 1; ?>" class="lightbox">
                    <a href="#" class="close" aria-label="Zatvori sliku">×</a>
                    <img 
                        src="<?php echo htmlspecialchars($imageUrl); ?>" 
                        alt="<?php echo htmlspecialchars($title); ?>"
                    >
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nema slika u folderu.</p>
        <?php endif; ?>

    </div>
</section>

<footer>
    <p>&copy; 2026. Web Programiranje. Sva prava pridržana.</p>
</footer>

</body>
</html>