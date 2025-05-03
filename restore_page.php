<?php
// maintenance.php - Site bakım ve yönlendirme sayfası
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="refresh" content="5;url=index.php">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Site Bakımda</title>
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --bg: #f0f2f5;
      --card-bg: #ffffff;
      --primary: #4f46e5;
      --text: #1f2937;
      --radius: 12px;
      --transition: 0.3s;
    }
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Inter', sans-serif;
      background: var(--bg);
      color: var(--text);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .card {
      background: var(--card-bg);
      border-radius: var(--radius);
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      padding: 2rem;
      text-align: center;
      max-width: 360px;
      width: 90%;
    }
    .card .icon {
      font-size: 3rem;
      color: var(--primary);
      margin-bottom: 1rem;
    }
    .card h1 {
      font-size: 1.75rem;
      margin-bottom: 0.5rem;
    }
    .card p {
      margin-bottom: 1.5rem;
      line-height: 1.4;
    }
    .card .btn {
      display: inline-block;
      padding: 0.75rem 1.5rem;
      background: var(--primary);
      color: #fff;
      border: none;
      border-radius: var(--radius);
      text-decoration: none;
      transition: background var(--transition);
    }
    .card .btn:hover {
      background: #4338ca;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="icon"><i class="fas fa-tools"></i></div>
    <h1>Sayfa Güncellenme Aşamasında</h1>
    <p>Üzerinde çalışmalar devam ediyor. 5 saniye içinde ana sayfaya yönlendirileceksiniz.</p>
    <a href="index.php" class="btn">Ana Sayfaya Git</a>
  </div>
</body>
</html>
