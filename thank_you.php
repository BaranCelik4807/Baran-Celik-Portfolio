<?php
// thank_you.php
session_start();

// Eğer form_submitted flag'i yoksa veya false ise index.php'ye yönlendir
if (empty($_SESSION['form_submitted'])) {
    header('Location: index.php');
    exit;
}

// Flag'i bir kez kullandıktan sonra temizle
unset($_SESSION['form_submitted']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Teşekkürler</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
    :root {
      --bg: #f0f4f8;
      --glass-bg: rgba(255,255,255,0.6);
      --glass-blur: 12px;
      --primary: #4f46e5;
      --accent: #ec4899;
      --text: #1f2937;
      --transition: 0.4s ease;
      --radius: 16px;
      --max-w: 1200px;
    }
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: 'Inter', sans-serif;
      background: var(--bg);
      color: var(--text);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      text-align: center;
      padding: 1rem;
    }
    .thank-container {
      background: var(--glass-bg);
      backdrop-filter: blur(var(--glass-blur));
      border-radius: var(--radius);
      padding: 3rem 2rem;
      max-width: 600px;
      width: 100%;
      box-shadow: 0 8px 32px rgba(0,0,0,0.05);
    }
    h1 {
      font-size: 2.5rem;
      color: var(--primary);
      margin-bottom: 1rem;
    }
    p {
      font-size: 1.1rem;
      margin-bottom: 2rem;
      line-height: 1.5;
    }
    .btn {
      display: inline-block;
      padding: 0.75rem 1.5rem;
      background: var(--primary);
      color: #fff;
      border-radius: var(--radius);
      font-weight: 600;
      text-decoration: none;
      transition: transform var(--transition), box-shadow var(--transition);
    }
    .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="thank-container">
    <h1>Teşekkürler!</h1>
    <p>Mesajınız bize ulaştı. En kısa sürede geri döneceğiz.</p>
    <a href="index.php" class="btn">Anasayfaya Dön</a>
  </div>
</body>
</html>
