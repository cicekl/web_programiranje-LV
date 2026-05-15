<?php
include "includes/db.php";
include "includes/auth.php";

requireAdmin();

$formMessage = "";
$editMovie = null;

if (isset($_GET["edit_id"])) {
    $edit_id = (int)$_GET["edit_id"];

    $editSql = "SELECT id, title, year, genre, duration, country, rating FROM movies WHERE id = ?";
    $editStmt = mysqli_prepare($conn, $editSql);
    mysqli_stmt_bind_param($editStmt, "i", $edit_id);
    mysqli_stmt_execute($editStmt);
    $editResult = mysqli_stmt_get_result($editStmt);
    $editMovie = mysqli_fetch_assoc($editResult);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save_movie"])) {
    $movie_id = (int)($_POST["movie_id"] ?? 0);
    $titleInput = trim($_POST["title"] ?? "");
    $yearInput = (int)($_POST["year"] ?? 0);
    $genreInput = trim($_POST["genre"] ?? "");
    $durationInput = (int)($_POST["duration"] ?? 0);
    $countryInput = trim($_POST["country"] ?? "");
    $ratingInput = (float)($_POST["rating"] ?? 0);

    if (
        $titleInput === "" ||
        $genreInput === "" ||
        $countryInput === "" ||
        $yearInput < 1888 ||
        $yearInput > 2026 ||
        $durationInput < 1 ||
        $durationInput > 500 ||
        $ratingInput < 0 ||
        $ratingInput > 10
    ) {
        $formMessage = "Podaci nisu ispravni. Provjerite sva polja.";
    } else {
        if ($movie_id > 0) {
            $updateSql = "UPDATE movies 
                          SET title = ?, year = ?, genre = ?, duration = ?, country = ?, rating = ?
                          WHERE id = ?";
            $updateStmt = mysqli_prepare($conn, $updateSql);
            mysqli_stmt_bind_param(
                $updateStmt,
                "sisisdi",
                $titleInput,
                $yearInput,
                $genreInput,
                $durationInput,
                $countryInput,
                $ratingInput,
                $movie_id
            );
            mysqli_stmt_execute($updateStmt);
        } else {
            $insertSql = "INSERT INTO movies (title, year, genre, duration, country, rating)
                          VALUES (?, ?, ?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($conn, $insertSql);
            mysqli_stmt_bind_param(
                $insertStmt,
                "sisisd",
                $titleInput,
                $yearInput,
                $genreInput,
                $durationInput,
                $countryInput,
                $ratingInput
            );
            mysqli_stmt_execute($insertStmt);
        }

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Author: Lorena Čiček, Category: Movies">
    <link rel="stylesheet" href="public/style/style.css">
    <link rel="stylesheet" href="public/style/style_admin.css">
    <title><?php echo $editMovie ? "Uredi film" : "Dodaj film"; ?></title>
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
                    <li><a href="grafikon.html">Grafikon</a></li>
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
    <?php endif; ?>

    <div class="table-img-container">
        <div class="table-container">

            <section class="admin-form-section">

                <div class="afs-header">
                    <div class="afs-title-wrap">
                        <div class="afs-icon">🎬</div>
                        <div>
                            <p class="afs-title"><?php echo $editMovie ? "Uredi film" : "Dodaj novi film"; ?></p>
                            <p class="afs-subtitle">Sva polja su obavezna</p>
                        </div>
                    </div>
                    <span class="admin-badge">Admin</span>
                </div>

                <div class="afs-body">

                    <?php if ($formMessage !== ""): ?>
                        <p class="error-message">
                            <span>⚠</span>
                            <?php echo htmlspecialchars($formMessage); ?>
                        </p>
                    <?php endif; ?>

                    <form method="POST" action="admin_movie.php<?php echo $editMovie ? '?edit_id=' . $editMovie["id"] : ''; ?>" class="admin-movie-form">
                        <input type="hidden" name="save_movie" value="1">

                        <?php if ($editMovie): ?>
                            <input type="hidden" name="movie_id" value="<?php echo $editMovie["id"]; ?>">
                        <?php endif; ?>

                        <div class="form-group span2">
                            <label for="title">Naslov <span class="req">*</span></label>
                            <div class="input-wrap">
                                <span class="input-icon">✎</span>
                                <input 
                                    type="text" 
                                    id="title" 
                                    name="title" 
                                    required 
                                    placeholder="npr. Inception"
                                    value="<?php echo htmlspecialchars($editMovie["title"] ?? ""); ?>"
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="year">Godina <span class="req">*</span></label>
                            <div class="input-wrap">
                                <span class="input-icon">📅</span>
                                <input 
                                    type="number" 
                                    id="year" 
                                    name="year" 
                                    min="1888" 
                                    max="2026" 
                                    required 
                                    placeholder="npr. 2010"
                                    value="<?php echo htmlspecialchars($editMovie["year"] ?? ""); ?>"
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="genre">Žanr <span class="req">*</span></label>
                            <select id="genre" name="genre" required>
                                <option value="">Odaberi žanr</option>
                                <?php
                                $genres = ["Drama", "Komedija", "Akcija", "Horor", "Triler"];
                                foreach ($genres as $g):
                                ?>
                                    <option value="<?php echo $g; ?>"
                                        <?php if (($editMovie["genre"] ?? "") === $g) echo "selected"; ?>>
                                        <?php echo $g; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="duration">Trajanje <span class="req">*</span></label>
                            <div class="input-wrap">
                                <span class="input-icon">⏱</span>
                                <input 
                                    type="number" 
                                    id="duration" 
                                    name="duration" 
                                    min="1" 
                                    max="500" 
                                    required 
                                    placeholder="npr. 148"
                                    value="<?php echo htmlspecialchars($editMovie["duration"] ?? ""); ?>"
                                >
                            </div>
                            <span class="field-hint">u minutama</span>
                        </div>

                        <div class="form-group">
                            <label for="country">Zemlja <span class="req">*</span></label>
                            <div class="input-wrap">
                                <span class="input-icon">📍</span>
                                <input 
                                    type="text" 
                                    id="country" 
                                    name="country" 
                                    required 
                                    placeholder="npr. USA"
                                    value="<?php echo htmlspecialchars($editMovie["country"] ?? ""); ?>"
                                >
                            </div>
                        </div>

                        <div class="form-divider"></div>

                        <div class="form-group">
                            <label for="rating">Ocjena <span class="req">*</span></label>
                            <div class="input-wrap">
                                <span class="input-icon">★</span>
                                <input 
                                    type="number" 
                                    id="rating" 
                                    name="rating" 
                                    min="0" 
                                    max="10" 
                                    step="0.1" 
                                    required 
                                    placeholder="npr. 8.8"
                                    value="<?php echo htmlspecialchars($editMovie["rating"] ?? ""); ?>"
                                >
                            </div>
                            <span class="field-hint">od 0.0 do 10.0</span>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <?php echo $editMovie ? "✓ Spremi promjene" : "+ Dodaj film"; ?>
                            </button>

                            <a class="cancel-edit" href="index.php">✕ Odustani</a>
                        </div>
                    </form>

                </div>
            </section>

        </div>
    </div>
</main>

<footer>
    <p>&copy; 2026. Web Programiranje. Sva prava pridržana.</p>
</footer>

</body>
</html>