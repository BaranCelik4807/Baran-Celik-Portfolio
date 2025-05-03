<?php
// contact.php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verileri temizle
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $message) {
        $stmt = $conn->prepare(
            "INSERT INTO messages (name, email, message) VALUES (?, ?, ?)"
        );
        $stmt->bind_param('sss', $name, $email, $message);

        if ($stmt->execute()) {
            header('Location: thank_you.php');
            exit;
        } else {
            $error = "Veritabanı hatası: " . htmlspecialchars($stmt->error);
        }
    } else {
        $error = "Lütfen tüm alanları doldurun.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>İletişim</title>
</head>
<body>
  <?php if (!empty($error)): ?>
    <p style="color:red"><?= $error ?></p>
  <?php endif; ?>
  <form action="contact.php" method="post">
    <input type="text"    name="name"    placeholder="Adınız" required><br>
    <input type="email"   name="email"   placeholder="E-posta" required><br>
    <textarea name="message" placeholder="Mesajınız" required></textarea><br>
    <button type="submit">Gönder</button>
  </form>
</body>
</html>
