<?php
include "includes/db.php";
include "includes/auth.php";


requireLogin();

$user_id = $_SESSION["user_id"];

$sql = "SELECT 
            wm.id AS wanted_id,
            m.id AS movie_id,
            m.title,
            m.year,
            m.genre,
            m.duration,
            m.country,
            m.rating,
            wm.created_at
        FROM wanted_movies wm
        JOIN movies m ON wm.movie_id = m.id
        WHERE wm.user_id = ?
        ORDER BY wm.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["remove_from_videoteka"])) {
    $wanted_id = (int)($_POST["wanted_id"] ?? 0);

    if ($wanted_id > 0) {
        $deleteSql = "DELETE FROM wanted_movies WHERE id = ? AND user_id = ?";
        $deleteStmt = mysqli_prepare($conn, $deleteSql);
        mysqli_stmt_bind_param($deleteStmt, "ii", $wanted_id, $user_id);
        mysqli_stmt_execute($deleteStmt);
    }

    header("Location: moja_videoteka.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moja videoteka</title>
    <link rel="stylesheet" href="public/style/style.css">
</head>
<body>

<header>
    <h1>Moja videoteka</h1>
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
                    <li><a href="moja_videoteka.php">Moja videoteka</a></li>
                    <li><a href="logout.php">Odjava</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>

<main>
    <h2>Filmovi koje ste dodali</h2>

    <div class="table-container">
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
                </tr>
            </thead>

            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($movie = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($movie["movie_id"]); ?></td>
                            <td><?php echo htmlspecialchars($movie["title"]); ?></td>
                            <td><?php echo htmlspecialchars($movie["year"]); ?></td>
                            <td><?php echo htmlspecialchars($movie["genre"]); ?></td>
                            <td><?php echo htmlspecialchars($movie["duration"]); ?> min</td>
                            <td><?php echo htmlspecialchars($movie["country"]); ?></td>
                            <td><?php echo htmlspecialchars($movie["rating"]); ?></td>
                            <td class="akcije" style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                <form method="POST" action="moja_videoteka.php" onsubmit="return confirm('Želite li ukloniti ovaj film iz videoteke?');">
                                    <input type="hidden" name="remove_from_videoteka" value="1">
                                    <input type="hidden" name="wanted_id" value="<?php echo $movie["wanted_id"]; ?>">
                                    <button type="submit">Ukloni</button>
                                </form>

                                <?php if (isAdmin()): ?>
                                    <a href="admin_movie.php?edit_id=<?php echo $movie["movie_id"]; ?>">
                                        <button type="button">Uredi</button>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Još nemate dodanih filmova u videoteci.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<footer>
    <p>&copy; 2026. Web Programiranje. Sva prava pridržana.</p>
</footer>

</body>
</html>