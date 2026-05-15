<?php
include "includes/db.php";
include "includes/auth.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if ($username === "" || $email === "" || $password === "" || $confirm_password === "") {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must have at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $checkSql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $checkStmt = mysqli_prepare($conn, $checkSql);

        if ($checkStmt) {
            mysqli_stmt_bind_param($checkStmt, "ss", $username, $email);
            mysqli_stmt_execute($checkStmt);
            $checkResult = mysqli_stmt_get_result($checkStmt);

            if (mysqli_num_rows($checkResult) > 0) {
                $error = "Username or email already exists.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $insertSql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')";
                $insertStmt = mysqli_prepare($conn, $insertSql);

                if ($insertStmt) {
                    mysqli_stmt_bind_param($insertStmt, "sss", $username, $email, $hashedPassword);

                    if (mysqli_stmt_execute($insertStmt)) {
                        $success = "Registration successful. You can now log in.";
                    } else {
                        $error = "Registration failed.";
                    }

                    mysqli_stmt_close($insertStmt);
                } else {
                    $error = "Database insert error.";
                }
            }

            mysqli_stmt_close($checkStmt);
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
    <title>Registracija | Portal za filmove</title>
    <link rel="stylesheet" href="public/style/style.css">
    <link rel="stylesheet" href="public/style/style_login.css">
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
    <section class="form-section">
        <h2>Registracija</h2>

        <?php if ($error !== ""): ?>
            <p class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </p>
        <?php endif; ?>

        <?php if ($success !== ""): ?>
            <p class="success-message">
                <?php echo htmlspecialchars($success); ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <label for="username">Korisničko ime:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Lozinka:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Potvrdi lozinku:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Registriraj se</button>

            <p class="form-link">
                Već imaš račun? <a href="login.php">Prijavi se</a>.
            </p>
        </form>
    </section>
</main>

<footer>
    <p>&copy; 2026. Web Programiranje. Sva prava pridržana.</p>
</footer>

</body>
</html>