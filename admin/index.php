<?php
session_start();

// GANTI DENGAN PASSWORD ANDA
$ADMIN_PASSWORD = '123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['password'] === $ADMIN_PASSWORD) {
        $_SESSION['logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; margin-bottom: 1.5rem; }
        input { width: 100%; padding: 0.75rem; margin: 0.5rem 0; border: 1px solid #ccc; border-radius: 6px; }
        button { width: 100%; padding: 0.75rem; background: #4CAF50; color: white; border: none; border-radius: 6px; cursor: pointer; }
        .error { color: #e74c3c; text-align: center; margin-top: 1rem; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Admin Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="password" name="password" placeholder="Password" required autofocus>
            <button type="submit">Masuk</button>
        </form>
    </div>
</body>
</html>