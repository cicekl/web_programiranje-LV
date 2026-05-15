<?php
include "includes/db.php";
include "includes/auth.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $error = "Please enter both username and password.";
    } else {
        $sql = "SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $username);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            if ($user && password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"] = $user["role"];

                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid username/email or password.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Database query error.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava | Portal za filmove</title>
    <link rel="stylesheet" href="public/style/style.css">
    <link rel="stylesheet" href="public/style/style_login.css">
</head>
<body>

<header>
        <h1>Portal za filmove</h1>
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
    <section class="form-section">
        <h2>Prijava</h2>

        <?php if ($error !== ""): ?>
            <p class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="username">Korisničko ime ili email:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Lozinka:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Prijavi se</button>

            <p class="form-link">
                Nemaš račun? <a href="register.php">Registriraj se</a>.
            </p>
        </form>
    </section>
</main>

<footer>
    <p>&copy; 2026. Web Programiranje. Sva prava pridržana.</p>
</footer>

</body>
</html>