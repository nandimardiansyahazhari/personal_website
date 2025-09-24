<?php
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: index.php');
    exit;
}

// Pastikan folder data ada
if (!is_dir('../data')) mkdir('../data', 0777, true);

// Proses form jika dikirim
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['type'] === 'blog') {
        $blog = [
            'id' => uniqid(),
            'title' => trim($_POST['title']),
            'thumbnail' => trim($_POST['image']),
            'content' => trim($_POST['content']),
            'date' => date('Y-m-d')
        ];

        // Baca data lama
        $blogFile = '../data/blog.json';
        $blogs = file_exists($blogFile) ? json_decode(file_get_contents($blogFile), true) : [];
        array_unshift($blogs, $blog); // tambah di awal
        file_put_contents($blogFile, json_encode($blogs, JSON_PRETTY_PRINT));
        $message = "Blog berhasil ditambahkan!";

    } elseif ($_POST['type'] === 'certificate') {
        $cert = [
            'id' => uniqid(),
            'title' => trim($_POST['title']),
            'file' => trim($_POST['file']),
            'date' => date('Y-m-d')
        ];

        $certFile = '../data/certificates.json';
        $certs = file_exists($certFile) ? json_decode(file_get_contents($certFile), true) : [];
        array_unshift($certs, $cert);
        file_put_contents($certFile, json_encode($certs, JSON_PRETTY_PRINT));
        $message = "Sertifikat berhasil ditambahkan!";
    }
}


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f8f9fa; color: #333; padding: 2rem; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h1 { text-align: center; margin-bottom: 1.5rem; color: #2c3e50; }
        .logout { float: right; background: #e74c3c; color: white; text-decoration: none; padding: 0.4rem 0.8rem; border-radius: 6px; }
        .logout:hover { background: #c0392b; }
        .card { margin: 1.5rem 0; padding: 1.5rem; border: 1px solid #eee; border-radius: 10px; }
        h2 { margin-bottom: 1rem; color: #3498db; }
        .form-group { margin: 1rem 0; }
        label { display: block; margin-bottom: 0.4rem; font-weight: 600; }
        input, textarea { width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 6px; }
        textarea { min-height: 120px; }
        button { background: #3498db; color: white; border: none; padding: 0.7rem 1.5rem; border-radius: 6px; cursor: pointer; }
        button:hover { background: #2980b9; }
        .message { background: #d4edda; color: #155724; padding: 0.75rem; border-radius: 6px; margin-bottom: 1.5rem; }
        .instructions { background: #e3f2fd; padding: 1rem; border-radius: 6px; margin: 1.5rem 0; font-size: 0.95rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <a href="?logout=1" class="logout">Logout</a>

        <?php if (isset($_GET['logout'])): ?>
            <?php session_destroy(); header('Location: index.php'); exit; ?>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="instructions">
            <p><strong>Catatan:</strong></p>
            <ul>
                <li>Upload gambar ke <code>assets/images/</code> dan PDF ke <code>certificates/</code> <strong>secara manual</strong> (via FTP/cPanel/file manager).</li>
                <li>Masukkan <strong>URL relatif</strong> file, contoh:
                    <br> Gambar: <code>/assets/images/blog1.jpg</code>
                    <br> PDF: <code>/certificates/sertifikat-web.pdf</code>
                </li>
            </ul>
        </div>

        <!-- Form Blog -->
        <div class="card">
            <h2>Tambah Blog</h2>
            <form method="POST">
                <input type="hidden" name="type" value="blog">
                <div class="form-group">
                    <label>Judul Blog</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>URL Gambar (opsional)</label>
                    <input type="text" name="image" placeholder="/assets/images/nama-gambar.jpg">
                </div>
                <div class="form-group">
                    <label>Isi Blog</label>
                    <textarea name="content" required></textarea>
                </div>
                <button type="submit">Simpan Blog</button>
            </form>
        </div>

        <!-- Form Sertifikat -->
        <div class="card">
            <h2>Tambah Sertifikat</h2>
            <form method="POST">
                <input type="hidden" name="type" value="certificate">
                <div class="form-group">
                    <label>Nama Sertifikat</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>URL File PDF</label>
                    <input type="text" name="file" placeholder="/certificates/nama-sertifikat.pdf" required>
                </div>
                <button type="submit">Simpan Sertifikat</button>
            </form>
        </div>
    </div>
</body>
</html>