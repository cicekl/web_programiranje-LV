<?php
include "includes/db.php";
include "includes/auth.php";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_to_videoteka"])) {
    requireLogin();

    $user_id = $_SESSION["user_id"];
    $movie_id = (int)($_POST["movie_id"] ?? 0);

    if ($movie_id > 0) {
        $checkSql = "SELECT id FROM wanted_movies WHERE user_id = ? AND movie_id = ?";
        $checkStmt = mysqli_prepare($conn, $checkSql);
        mysqli_stmt_bind_param($checkStmt, "ii", $user_id, $movie_id);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);

        if (mysqli_num_rows($checkResult) === 0) {
            $insertSql = "INSERT INTO wanted_movies (user_id, movie_id) VALUES (?, ?)";
            $insertStmt = mysqli_prepare($conn, $insertSql);
            mysqli_stmt_bind_param($insertStmt, "ii", $user_id, $movie_id);
            mysqli_stmt_execute($insertStmt);
        }
    }

    header("Location: moja_videoteka.php");
    exit;
}

$title = trim($_GET["title"] ?? "");
$genre = trim($_GET["genre"] ?? "");
$year = trim($_GET["year"] ?? "");
$min_rating = trim($_GET["min_rating"] ?? "0");

$sql = "SELECT id, title, year, genre, duration, country, rating FROM movies WHERE 1=1";
$params = [];
$types = "";

if ($title !== "") {
    $sql .= " AND title LIKE ?";
    $params[] = "%" . $title . "%";
    $types .= "s";
}

if ($genre !== "") {
    $sql .= " AND genre = ?";
    $params[] = $genre;
    $types .= "s";
}

if ($year !== "") {
    $sql .= " AND year >= ?";
    $params[] = (int)$year;
    $types .= "i";
}

if ($min_rating !== "") {
    $sql .= " AND rating >= ?";
    $params[] = (float)$min_rating;
    $types .= "d";
}

$sql .= " ORDER BY rating DESC";

$stmt = mysqli_prepare($conn, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Author: Lorena Čiček, Category: Movies">
    <link rel="stylesheet" href="public/style/style.css">
    <title>Filmovi</title>
</head>

<body>
<header>
    <h1>Portal za filmove</h1>
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
    <?php if (isLoggedIn()): ?>
        <p>Prijavljeni ste kao <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>.</p>
    <?php else: ?>
        <p>Za dodavanje filmova u osobnu videoteku trebate se prijaviti.</p>
    <?php endif; ?>

    <div class="table-img-container">
        <div class="table-container">
            <aside class="slika-centar">
                <img class="img-cinema" src="public/images/cinema-picture.jpg" alt="Picture of cinema">
            </aside>

            <section id="filteri">
                <h2>Filtriranje filmova</h2>

                <form method="GET" action="index.php">
                    <input 
                        type="text" 
                        name="title" 
                        placeholder="Pretraži po naslovu"
                        value="<?php echo htmlspecialchars($title); ?>"
                    >

                    <select name="genre">
                        <option value="">Svi žanrovi</option>
                        <option value="Drama" <?php if ($genre === "Drama") echo "selected"; ?>>Drama</option>
                        <option value="Komedija" <?php if ($genre === "Komedija") echo "selected"; ?>>Komedija</option>
                        <option value="Akcija" <?php if ($genre === "Akcija") echo "selected"; ?>>Akcija</option>
                        <option value="Horor" <?php if ($genre === "Horor") echo "selected"; ?>>Horor</option>
                        <option value="Triler" <?php if ($genre === "Triler") echo "selected"; ?>>Triler</option>
                    </select>

                    <input 
                        type="number" 
                        name="year" 
                        placeholder="Godina od"
                        value="<?php echo htmlspecialchars($year); ?>"
                    >

                    <label>
                        Minimalna ocjena:
                        <input 
                            type="range" 
                            name="min_rating" 
                            min="0" 
                            max="10" 
                            step="0.1" 
                            value="<?php echo htmlspecialchars($min_rating); ?>"
                            oninput="document.getElementById('ocjena-value').textContent = this.value"
                        >
                        <span id="ocjena-value"><?php echo htmlspecialchars($min_rating); ?></span>
                    </label>

                    <button type="submit">Filtriraj</button>
                    <a href="index.php">Reset</a>
                </form>
            </section>

            <?php if (isAdmin()): ?>
                <div class="admin-add-button">
                    <a href="admin_movie.php">
                        <button type="button">+ Dodaj film</button>
                    </a>
                </div>
            <?php endif; ?>

            <table id="filmovi-tablica">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Naslov</th>
                        <th>Godina</th>
                        <th>Žanr</th>
                        <th>Trajanje</th>
                        <th>Zemlja</th>
                        <th>Ocjena</th>
                        <th>Akcija</th>
                        <?php if (isAdmin()): ?>
                            <th>Admin</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($movie = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($movie["id"]); ?></td>
                                <td><?php echo htmlspecialchars($movie["title"]); ?></td>
                                <td><?php echo htmlspecialchars($movie["year"]); ?></td>
                                <td><?php echo htmlspecialchars($movie["genre"]); ?></td>
                                <td><?php echo htmlspecialchars($movie["duration"]); ?> min</td>
                                <td><?php echo htmlspecialchars($movie["country"]); ?></td>
                                <td><?php echo htmlspecialchars($movie["rating"]); ?></td>
                                <td>
                                    <?php if (isLoggedIn()): ?>
                                        <form method="POST" action="index.php">
                                            <input type="hidden" name="add_to_videoteka" value="1">
                                            <input type="hidden" name="movie_id" value="<?php echo $movie["id"]; ?>">
                                            <button type="submit">Dodaj</button>
                                        </form>
                                    <?php else: ?>
                                        <a href="login.php">Prijava</a>
                                    <?php endif; ?>
                                </td>

                               <?php if (isAdmin()): ?>
                                    <td class="akcije" style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                        <a href="admin_movie.php?edit_id=<?php echo $movie["id"]; ?>">
                                            <button type="button">Uredi</button>
                                        </a>

                                        <form method="POST" action="delete_movie.php" onsubmit="return confirm('Jeste li sigurni da želite obrisati ovaj film?');">
                                            <input type="hidden" name="movie_id" value="<?php echo $movie["id"]; ?>">
                                            <button type="submit">Obriši</button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">Nema filmova za prikaz.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <aside id="kosarica">
                <h2>Moja videoteka</h2>
                <p>Odabrani filmovi trajno se spremaju u bazu podataka.</p>
                <?php if (isLoggedIn()): ?>
                    <a href="moja_videoteka.php">Pogledaj moju videoteku</a>
                <?php else: ?>
                    <a href="login.php">Prijavi se za dodavanje filmova</a>
                <?php endif; ?>
            </aside>
        </div>
    </div>

    <section aria-labelledby="glavna-sekcija">
        <h2 id="glavna-sekcija">Ovo je glavna sekcija</h2>
        <p>HTML5 omogućava semantičku strukturu koja poboljšava pristupačnost i SEO.</p>
    </section>

    <article aria-labelledby="vijesti">
        <h2 id="vijesti">Najnovije vijesti</h2>
        <p>Ovdje se nalazi članak s važnim informacijama.</p>
    </article>
</main>

<footer>
    <p>&copy; 2026. Web Programiranje. Sva prava pridržana.</p>
</footer>

</body>
</html>