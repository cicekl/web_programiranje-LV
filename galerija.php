<?php
include "includes/db.php";
include "includes/auth.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["rate_image"])) {
    requireLogin();

    $id_korisnik = $_SESSION["user_id"];
    $id_slika = (int)($_POST["id_slika"] ?? 0);
    $ocjena = (int)($_POST["ocjena"] ?? 0);

    if ($id_slika > 0 && $ocjena >= 1 && $ocjena <= 5) {
        $sql = "INSERT INTO ocjene_slika (id_korisnik, id_slika, ocjena)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE ocjena = VALUES(ocjena)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $id_korisnik, $id_slika, $ocjena);
        mysqli_stmt_execute($stmt);
    }

    header("Location: galerija.php");
    exit;
}

$user_id = $_SESSION["user_id"] ?? 0;

$sql = "SELECT 
            s.id,
            s.naziv_datoteke,
            s.opis,
            s.putanja,
            COALESCE(AVG(o.ocjena), 0) AS prosjek,
            COUNT(o.id) AS broj_ocjena,
            MAX(CASE WHEN o.id_korisnik = ? THEN o.ocjena END) AS moja_ocjena
        FROM slike s
        LEFT JOIN ocjene_slika o ON s.id = o.id_slika
        GROUP BY s.id, s.naziv_datoteke, s.opis, s.putanja
        ORDER BY s.id ASC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["dodaj_sliku"])) {
    requireAdmin();

    $opis = trim($_POST["opis"] ?? "");
    $izvor = trim($_POST["izvor"] ?? "");

    if (isset($_FILES["slika"]) && $_FILES["slika"]["error"] === 0) {
        $naziv_datoteke = basename($_FILES["slika"]["name"]);
        $putanja = "public/images/" . $naziv_datoteke;
        $dozvoljeni_tipovi = ["image/jpeg", "image/png", "image/gif", "image/webp"];

        if (in_array($_FILES["slika"]["type"], $dozvoljeni_tipovi)) {
            if (move_uploaded_file($_FILES["slika"]["tmp_name"], $putanja)) {
                $sql = "INSERT INTO slike (naziv_datoteke, opis, putanja, izvor) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssss", $naziv_datoteke, $opis, $putanja, $izvor);
                mysqli_stmt_execute($stmt);
            }
        }
    }

    header("Location: galerija.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerija slika</title>
    <link rel="stylesheet" href="public/style/style_slike.css">
    <link rel="stylesheet" href="public/style/style_galerija.css">
</head>

<body>

<header>
    <h1>Galerija</h1>
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

<?php if (isAdmin()): ?>
    <section class="admin-upload">
        <h2>Dodaj sliku</h2>
        <form method="POST" action="galerija.php" enctype="multipart/form-data">
            <input type="hidden" name="dodaj_sliku" value="1">

            <label for="slika">Odaberi sliku:</label>
            <input type="file" id="slika" name="slika" accept="image/*" required>

            <label for="naziv_datoteke">Opis:</label>
            <input type="text" id="opis" name="opis" placeholder="Kratki opis slike">

            <label for="izvor">Izvor:</label>
            <input type="text" id="izvor" name="izvor" placeholder="npr. Unsplash">

            <button type="submit">Dodaj sliku</button>
        </form>
    </section>
<?php endif; ?>

<section class="galerija">

    <?php if (!isLoggedIn()): ?>
        <p>Za ocjenjivanje slika trebate se prijaviti.</p>
    <?php endif; ?>

    <div class="galerija_slike">

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($slika = mysqli_fetch_assoc($result)): ?>
                <figure class="galerija_slika">
                    <a href="#img<?php echo $slika["id"]; ?>">
                        <img 
                            src="<?php echo htmlspecialchars($slika["putanja"]); ?>" 
                            alt="<?php echo htmlspecialchars($slika["opis"] ?? $slika["naziv_datoteke"]); ?>"
                        >
                    </a>

                    <figcaption>
                        <?php echo htmlspecialchars($slika["opis"] ?: $slika["naziv_datoteke"]); ?>
                    </figcaption>

                    <p class="prosjek-ocjena">
                        Prosječna ocjena:
                        <strong><?php echo number_format((float)$slika["prosjek"], 1); ?></strong> / 5
                        <br>
                        <small>Broj ocjena: <?php echo $slika["broj_ocjena"]; ?></small>
                    </p>

                    <?php if (isLoggedIn()): ?>
                        <form method="POST" action="galerija.php" class="rating-form">
                            <input type="hidden" name="rate_image" value="1">
                            <input type="hidden" name="id_slika" value="<?php echo $slika["id"]; ?>">

                            <div class="stars">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input 
                                        type="radio" 
                                        id="star<?php echo $i; ?>-<?php echo $slika["id"]; ?>" 
                                        name="ocjena" 
                                        value="<?php echo $i; ?>"
                                        <?php if ((int)$slika["moja_ocjena"] === $i) echo "checked"; ?>
                                        required
                                    >
                                    <label for="star<?php echo $i; ?>-<?php echo $slika["id"]; ?>">★</label>
                                <?php endfor; ?>
                            </div>

                            <button type="submit">Ocijeni</button>
                        </form>
                    <?php endif; ?>
                </figure>

                <div id="img<?php echo $slika["id"]; ?>" class="lightbox">
                    <a href="#" class="close">×</a>
                    <img 
                        src="<?php echo htmlspecialchars($slika["putanja"]); ?>" 
                        alt="<?php echo htmlspecialchars($slika["opis"] ?? $slika["naziv_datoteke"]); ?>"
                    >
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nema slika za prikaz.</p>
        <?php endif; ?>

    </div>
</section>

<footer>
    <p>&copy; 2026. Web Programiranje. Sva prava pridržana.</p>
</footer>

</body>
</html>