<?php
// Session'ı başlatmazsan $_SESSION kullanamazsın
session_start();

// DB bağlantısını hazırla
require_once 'db_connection.php';

// Hata mesajı için değişken
$error = '';

// Form gönderimi kontrolü
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {
    // Formdan gelen verileri temizle
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $message) {
        // Sorguyu hazırla
        $stmt = $conn->prepare(
            "INSERT INTO messages (name, email, message) VALUES (?, ?, ?)"
        );
        $stmt->bind_param('sss', $name, $email, $message);

        // Sorguyu çalıştır
        if ($stmt->execute()) {
            // Başarılıysa flag koy ve thank_you'a yönlendir
            $_SESSION['form_submitted'] = true;
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Baran Çelik | Portfolio</title>
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
      scroll-behavior: smooth;
      overflow-x: hidden;
    }
    body.lang-tr .lang-en, body.lang-en .lang-tr { display: none; }

    /* NAVBAR */
    nav {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 100;
      background: var(--glass-bg);
      backdrop-filter: blur(var(--glass-blur));
      transition: background var(--transition);
    }
    nav.scrolled { background: rgba(255,255,255,0.9); }
    .nav-container {
      max-width: var(--max-w);
      margin: auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem;
      gap: 1rem;
    }
    .logo { font-size:1.5rem; font-weight:800; color: var(--primary); text-decoration: none; }
    .links, .lang-switch {
      list-style:none;
      display:flex;
      gap:1rem;
      align-items: center;
    }
    .links a, .lang-switch button {
      background: none;
      border:none;
      font:inherit;
      cursor:pointer;
      color: var(--text);
      padding:0.5rem;
      transition: color var(--transition);
    }
    .links a:hover, .lang-switch button:hover { color: var(--accent); }
    .lang-switch button { font-size:1.25rem; }

    /* Modern Hamburger */
    .hamburger {
      display:none;
      width: 30px;
      height: 24px;
      position: relative;
      cursor: pointer;
      transition: transform var(--transition);
    }
    .hamburger span {
      position: absolute;
      height: 3px;
      width: 100%;
      background: var(--text);
      border-radius: 3px;
      transition: all var(--transition);
    }
    .hamburger span:nth-child(1) { top: 0; }
    .hamburger span:nth-child(2) { top: 50%; transform: translateY(-50%); }
    .hamburger span:nth-child(3) { bottom: 0; }
    .hamburger.active span:nth-child(1) { transform: translateY(11px) rotate(45deg); }
    .hamburger.active span:nth-child(2) { opacity: 0; }
    .hamburger.active span:nth-child(3) { transform: translateY(-11px) rotate(-45deg); }

    /* SECTIONS */
    section { padding: 6rem 1rem 3rem; position: relative; }
    .container { max-width: var(--max-w); margin: auto; }
    h1, h2, h3 { margin-bottom:1rem; line-height:1.2; }
    .section-title {
      text-align:center;
      font-size:2.5rem;
      font-weight:700;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      -webkit-background-clip: text;
      color: transparent;
    }
    .hero {
      min-height:100vh;
      display:flex;
      flex-direction: column;
      align-items:center;
      justify-content:center;
      background: linear-gradient(135deg, #eef2ff, #fce7f3);
      text-align:center;
      padding: 4rem 1rem 2rem;
    }
    .profile-photo {
      width:150px;
      height:150px;
      border-radius:50%;
      border:4px solid var(--primary);
      margin-bottom:1rem;
      object-fit:cover;
    }
    .hero h1 { font-size:2.5rem; color: var(--primary); }
    .hero p { font-size:1rem; margin-bottom:1.5rem; line-height:1.4; }
    .btn {
      display:inline-block;
      padding:0.75rem 1.5rem;
      background:var(--primary);
      color:#fff;
      border-radius:var(--radius);
      font-weight:600;
      cursor:pointer;
      transition: transform var(--transition), box-shadow var(--transition);
    }
    .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .glass {
      background: var(--glass-bg);
      backdrop-filter: blur(var(--glass-blur));
      border-radius: var(--radius);
      padding:2rem;
      box-shadow: 0 8px 32px rgba(0,0,0,0.05);
    }
    .reveal {
      opacity:0;
      transform: translateY(20px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }
    .reveal.active { opacity:1; transform:none; }

    /* ABOUT */
    .lead { max-width:800px; margin:1rem auto; font-size:1.1rem; text-align:center; line-height:1.6; }

    /* CV */
    .cv-cta {
  background: linear-gradient(135deg, #8e44ad, #3498db);
  color: #fff;
  padding: 1.5rem;
  border-radius: var(--radius);
  text-align: center;
  margin: 2rem 0;
  box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}
.cv-cta h3 {
  font-size: 1.75rem;
  margin-bottom: 0.75rem;
}
.cv-cta .btn {
  background: #fff;
  color: var(--accent);
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  border-radius: var(--radius);
}
.cv-cta .btn:hover {
  background: rgba(255,255,255,0.9);
}
    .cv-grid {
      display:grid; grid-template-columns: repeat(auto-fit,minmax(250px,1fr)); gap:2rem;
    }
    .cv-grid h3 { color: var(--accent); margin-bottom:0.5rem; }
    .cv-grid ul { list-style: disc inside; }
    .cv-grid p, .cv-grid li { font-size:0.95rem; line-height:1.5; }

    /* Projects */
    .projects-grid {
      display:grid; grid-template-columns: repeat(auto-fit,minmax(280px,1fr));
      gap:2rem;
    }
    .card {
      background:#fff; border-radius:var(--radius); overflow:hidden;
      box-shadow:0 8px 24px rgba(0,0,0,0.07);
      transition: transform var(--transition), box-shadow var(--transition);
    }
    .card:hover {
      transform: translateY(-5px); box-shadow:0 16px 40px rgba(0,0,0,0.12);
    }
    .card img { width:100%; height:180px; object-fit:cover; }
    .card-body { padding:1.5rem; }
    .card-body h3 { font-size:1.5rem; color:var(--primary); margin-bottom:0.5rem; }
    .card-body h4 { font-size:1rem; color:var(--accent); margin-bottom:1rem; font-weight:600; }
    .card-body p { font-size:0.95rem; margin-bottom:1rem; }
    .link-btn {
      display:inline-block; color:var(--accent); text-decoration:underline; font-weight:600;
      transition: color var(--transition);
    }
    .link-btn:hover { color:var(--primary); }

    /* Code */
    .btn-outline {
      padding:0.5rem 1.25rem; border:2px solid var(--primary);
      background:transparent; color:var(--primary); border-radius:var(--radius);
      transition: background var(--transition), color var(--transition);
    }
    .btn-outline:hover { background:var(--primary); color:#fff; }

    /* Contact */
    .contact-form {
      max-width:600px; margin:0 auto; display:flex; flex-direction:column; gap:1rem;
    }
    .contact-form input, .contact-form textarea {
      padding:0.75rem; border:1px solid #ccc; border-radius:var(--radius);
      font-size:1rem; transition: border-color var(--transition);
    }
    .contact-form input:focus, .contact-form textarea:focus {
      border-color: var(--accent); outline:none;
    }

    /* Footer */
    footer {
      background: var(--primary); color:#fff; padding:2rem 1rem;
    }
    .footer-container {
      max-width:var(--max-w); margin:auto; display:flex;
      flex-wrap:wrap; align-items:center; justify-content:space-between;
      gap:1rem;
    }
    .socials a {
      color:#fff; margin-left:1rem; text-decoration:none;
      transition: color var(--transition);
    }
    .socials a:hover { color:var(--accent); }

    /* Certifications */
    .cert-grid {
      display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:2rem; margin-top:2rem;
    }
    .cert {
      background:#fff; border-radius:var(--radius); padding:1.5rem;
      box-shadow:0 8px 24px rgba(0,0,0,0.07);
    }
    .cert h4 { font-size:1.25rem; color:var(--primary); margin-bottom:0.5rem; }
    .cert p { font-size:0.95rem; margin-bottom:0.5rem; }
    .cert span { font-size:0.85rem; color:#555; }

    /* Responsive */
    @media(max-width:768px) {
      .hamburger { display:flex; }
      .links { display:none; position:absolute; top:64px; right:1rem; flex-direction:column; background:#fff; padding:1rem; border-radius:var(--radius); box-shadow:0 8px 24px rgba(0,0,0,0.1); }
      .links.open { display:flex; }
      .lang-switch { display:flex; position:absolute; top:64px; left:1rem; flex-direction:row; background:#fff; padding:0.5rem; border-radius:var(--radius); box-shadow:0 8px 24px rgba(0,0,0,0.1); }
    }
  </style>
</head>
<body class="lang-tr">

  <nav id="navbar">
    <div class="nav-container">
      <a href="#home" class="logo">celikbaran.com.tr</a>
      <ul class="lang-switch" id="lang-switch">
        <button data-lang="tr" aria-label="Türkçe">🇹🇷</button>
        <button data-lang="en" aria-label="English">🇬🇧</button>
      </ul>
      <div class="hamburger" id="burger"><span></span><span></span><span></span></div>
      <ul class="links" id="nav-links">
        <li><a href="#home"><span class="lang-tr">Anasayfa</span><span class="lang-en">Home</span></a></li>
        <li><a href="#about"><span class="lang-tr">Hakkımda</span><span class="lang-en">About</span></a></li>
        <li><a href="#cv"><span class="lang-tr">Özgeçmiş</span><span class="lang-en">CV</span></a></li>
        <li><a href="#projects"><span class="lang-tr">Projeler</span><span class="lang-en">Projects</span></a></li>
        <li><a href="#certs"><span class="lang-tr">Sertifikalar</span><span class="lang-en">Certifications</span></a></li>
        <li><a href="#code"><span class="lang-tr">Kodlar</span><span class="lang-en">Code</span></a></li>
        <li><a href="#contact"><span class="lang-tr">İletişim</span><span class="lang-en">Contact</span></a></li>
      </ul>
    </div>
  </nav>

 <section id="home" class="hero reveal">
    <div class="container">
      <img class="profile-photo" src="img/profile.jpg" alt="Baran Çelik" />
      <h1>Baran Çelik</h1>
<p class="lead">
  <span class="lang-tr">
    2004 doğumluyum, 20 yaşındayım. Alanya Alaaddin Keykubat Üniversitesi Bilgisayar Mühendisliği 3. sınıf öğrencisiyim. Genç yaşımda birçok deneyim edindim: üniversite toplulukları kurmak, projeler geliştirmek, ekip kurmak ve yönetmek gibi sorumluluklar üstlendim. TÜBİTAK ve TEKNOFEST gibi yarışmalarda proje geliştirip takım kaptanlığı yaptım. Hâlihazırda Alanya Alaaddin Keykubat Üniversitesi Su Sporları ve Dalış Topluluğu'nun kurucu başkanlığını yürütüyorum. Ayrıca Cmas 1 yıldız profesyonel dalıcıyım.
  </span><br><br>
  <span class="lang-en">
    I'm a 20-year-old Computer Engineering student (3rd year) at Alanya Alaaddin Keykubat University. Despite my young age, I've gained experience in founding student communities, leading projects, managing teams, and developing products for competitions such as TÜBİTAK and TEKNOFEST. Currently, I serve as the founding president of the University's Water Sports and Diving Club. and I'm also a 1-star professional diver.
  </span>
</p>

      <p class="lead">
        <span class="lang-tr">İdealim: Ülkemizin kalkınması için, fırsatım ve gücüm yeterse şahıs şirketi açıp devam etmek.</span>
        <span class="lang-en">My ideal: To contribute to my country’s development by founding my own company, if I have the opportunity and capability.</span>
      </p>
      <button class="btn" onclick="location.href='#projects'">
        <span class="lang-tr">Projelerime Göz At</span><span class="lang-en">View My Projects</span>
      </button>
    </div>
  </section>

  <section id="about" class="section reveal">
    <div class="container glass">
      <h2 class="section-title"><span class="lang-tr">Hakkımda</span><span class="lang-en">About Me</span></h2>
      <p class="lead">
        <span class="lang-tr">Yazılım geliştirme, veri bilimi ve 3D modelleme alanlarında deneyimliyim. Python (NumPy, Pandas, scikit-learn, Matplotlib), C#, PHP, JavaScript teknolojilerinde projeler geliştirdim. İngilizcem B2 seviyesinde.</span>
        <span class="lang-en">I have experience in software development, data science, and 3D modeling. Built projects using Python (NumPy, Pandas, scikit-learn, Matplotlib), C#, PHP, JavaScript. English level: B2.</span>
      </p>
    </div>
  </section>

  <section id="cv" class="section reveal">
    <div class="container glass">
        <div class="cv-cta">
      <h3>
        <span class="lang-tr">Özgeçmişimi PDF Olarak İndir</span>
        <span class="lang-en">Download My CV as PDF</span>
      </h3>
      <a href="pdf/Baran_Celik_CV.pdf" download class="btn">
        <span class="lang-tr">Şimdi İndir</span>
        <span class="lang-en">Download Now</span>
      </a>
    </div>
      <h2 class="section-title"><span class="lang-tr">Özgeçmiş</span><span class="lang-en">Curriculum Vitae</span></h2>
      <div class="cv-grid">
        <div>
          <h3><span class="lang-tr">Eğitim</span><span class="lang-en">Education</span></h3>
          <p>
            <span class="lang-tr">Fen Lisesi (2018–2022)<br>Alanya ALKÜ — Bilgisayar Mühendisliği (2022–Devam)</span>
            <span class="lang-en">Science High School (2018–2022)<br>Alanya ALKU — BSc Computer Engineering (2022–Present)</span>
          </p>
        </div>
        <div>
          <h3><span class="lang-tr">Deneyim & Projeler</span><span class="lang-en">Experience & Projects</span></h3>
          <ul>
            <li><span class="lang-tr">Notoryum.site – Kurucu & Backend Developer (2025–Devam)</span><span class="lang-en">Notoryum.site – Founder & Backend Dev (2025–Present)</span></li>
            <li><span class="lang-tr">BCEChat.wuaze.com – Kurucu & Backend Developer (2023)</span><span class="lang-en">bcehat.wuaze.com – Founder & Backend Dev  (2023)</span></li>
            <li><span class="lang-tr">KasliTravelAgency.com – Frontend Dev (2024–2025)</span><span class="lang-en">KasliTravelAgency.com – Full stack Dev (2024–2025)</span></li>
            <li><span class="lang-tr">TravelAgency (new) – Kod klasöründe hazır</span><span class="lang-en">TravelAgency (new) – Code ready in folder</span></li>
          </ul>
        </div>
        <div>
          <h3><span class="lang-tr">Yetenekler</span><span class="lang-en">Skills</span></h3>
          <p>Python (NumPy, Pandas, scikit-learn, Matplotlib), C#, PHP, JavaScript (ES6+), HTML5 & CSS3, AOS & Animations, Git & GitHub</p>
        </div>
      </div>
    </div>
  </section>

  <section id="projects" class="section reveal">
    <div class="container">
      <h2 class="section-title"><span class="lang-tr">Projeler</span><span class="lang-en">Projects</span></h2>
      <div class="projects-grid">
        <!-- Notoryum -->
        <div class="card reveal">
          <img src="img/notoryum.png" alt="Notoryum">
          <div class="card-body">
            <h3>Notoryum</h3>
            <h4><span class="lang-tr">Üniversite notlarını al & sat</span><span class="lang-en">Buy & sell uni notes</span></h4>
            <p><span class="lang-tr">Öğrenciler, notlarını satarak maddi kazanç elde edebilir, aynı zamanda başkalarından akademik içerikler satın alabilirler. Ayrıca, ödev veya tez yaptırmak isteyen kullanıcılar ilan verebilir ve birebir özelleştirilmiş mesajlaşma arayüzü ile iletişim kurabilirler.</span><span class="lang-en">Students can sell their notes to earn money and purchase academic content from others. Additionally, users can post ads for assignments or thesis work and communicate through a personalized messaging interface.</span></p>

            <a href="https://notoryum.site" class="link-btn" target="_blank">notoryum.site</a>
          </div>
        </div>
        <!-- bceChat -->
        <div class="card reveal">
          <img src="img/bce.png" alt="bcechat">
          <div class="card-body">
            <h3>bcehat</h3>
            <h4><span class="lang-tr">Basit mesajlaşma uygulaması</span><span class="lang-en">Simple chat app</span></h4>
            <p><span class="lang-tr">PHP + JS tabanlı, hızlı ve hafif mesajlaşma platformu.</span><span class="lang-en">Lightweight chat platform built with PHP & JS.</span></p>
            <a href="http://bcechat.wuaze.com" class="link-btn" target="_blank">bcechat.wuaze.com</a>
            <br>
            <a href="https://github.com/BaranCelik4807/BceChat.git" class="link-btn" target="_blank"> For Code Repos</a>   
          </div>
        </div>
        <!-- KasliTravelAgency -->
        <div class="card reveal">
          <img src="img/kta.jpg" alt="Kasli Travel">
          <div class="card-body">
            <h3>KasliTravelAgency</h3>
            <h4><span class="lang-tr">Turizm acentesi web sitesi</span><span class="lang-en">Travel agency site</span></h4>
            <p><span class="lang-tr">2024’de geliştirdiğim, sonradan başka bir kurum tarafından yenilenen şuan bende sadece geliştirme aşaması kaynak kodlarının bulunduğu bir turizm acentesi web sitesidir.</span><span class="lang-en">It is a tourism agency website that I developed in 2024, later renewed by another institution, and currently I only have the source codes of the development phase.</span></p>
            <a href="https://kaslitravelagency.com" class="link-btn" target="_blank">kaslitravelagency.com</a>  
            <br>
            <a href="https://github.com/BaranCelik4807/KasliTravelAgency.git" class="link-btn" target="_blank"> For Code Repos</a>       
          </div>
        </div>
        <!-- Baran Çelik Portföy -->
        <div class="card reveal">
        <img src="img/portfolio.jpg" alt="Baran Çelik Portfolio">
        <div class="card-body">
            <h3>Baran Çelik Portföy</h3>
            <h4><span class="lang-tr">Kişisel portföy sitesi</span><span class="lang-en">Personal portfolio website</span></h4>
            <p><span class="lang-tr">Bu, 2025 yılında geliştirdiğim ve tüm projelerimi sergileyen kişisel portföy sitemdir. Web geliştirme, tasarım ve yazılım becerilerimi burada sergileyerek yaptığım projeleri tanıtıyorum.</span><span class="lang-en">This is my personal portfolio website that I developed in 2025 to showcase all my projects. Here, I demonstrate my web development, design, and software skills while presenting the projects I have worked on.</span></p>
            <a href="https://celikbaran.com.tr" class="link-btn" target="_blank">celikbaran.com.tr</a>  
            <br>
            <a href="https://github.com/BaranCelik4807/Portfolio.git" class="link-btn" target="_blank"> For Code Repos</a>       
        </div>
        </div>


        <!-- Virtual Shopping Booth -->
        <div class="card reveal">
          <img src="img/virtual_shopping_booth.png" alt="Virtual Shopping Booth">
          <div class="card-body">
            <h3>Virtual Shopping Booth</h3>
            <h4><span class="lang-tr">Online vitrin deneyimi</span><span class="lang-en">Online showcase experience</span></h4>
            <p>
              <span class="lang-tr">3D Kabin projesinin temelini oluşturur. Kullanıcıdan alınan 8 farklı açıdan yüz fotoğrafı ile AI destekli 3D kafa modeli oluşturulur. Bu model, Unity üzerinden sanal bir alışveriş kabinine entegre edilir. Amaç, kişiye özel avatarla kıyafet deneyimi sağlamaktır.</span>
              <span class="lang-en">This is the foundation of the 3D Kabin project. Using 8 facial photos from different angles, an AI-generated 3D head model is created and integrated into a virtual fitting room built with Unity. The goal is to let users try clothes on their personalized avatars.</span>
            </p>
            <a href="restore_page.php" class="link-btn" target="_blank">
              <span class="lang-tr">Sunumu İndir (PDF)</span><span class="lang-en">Download Presentation (PDF)</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

<section id="certs" class="section reveal">
  <div class="container glass text-center">
    <h2 class="section-title">
      <span class="lang-tr">Sertifikalar</span>
      <span class="lang-en">Certifications</span>
    </h2>
    <div class="cert-grid">
      <!-- Mevcut Sertifika -->
      <div class="cert reveal">
        <h4>Siber Vatan 2024 Eğitim Geliştirme Projesi</h4>
        <p>
          <span class="lang-tr">Teknoloji ve Sanayi Bakanlığı tarafından desteklenen programda, seçilen 1000 öğrenciden biri olarak siber güvenlik eğitimi aldım.</span>
          <span class="lang-en">Selected among 1000 students for the Cyber Homeland 2024 program supported by the Ministry of Industry and Technology, received cybersecurity training.</span>
        </p>
        <span>2024</span>
      </div>
    <!-- Yeni Sertifikalar -->
    <div class="cert reveal">
    <h4>
        <span class="lang-tr">Beyaz Şapkalı Hacker ve Temel Linux Eğitimi</span>
        <span class="lang-en">White Hat Hacker and Basic Linux Training</span>
    </h4>
    <a href="certificas/sibervatan1.pdf" target="_blank" rel="noopener noreferrer">
        <span class="lang-tr">Sertifikayı Görüntüle (PDF)</span>
        <span class="lang-en">View Certificate (PDF)</span>
    </a>
    <span>2024</span>
    </div>
    <div class="cert reveal">
    <h4>
        <span class="lang-tr">Sızma Testi</span>
        <span class="lang-en">Penetration Testing</span>
    </h4>
    <a href="certificas/sibervatan2.pdf" target="_blank" rel="noopener noreferrer">
        <span class="lang-tr">Sertifikayı Görüntüle (PDF)</span>
        <span class="lang-en">View Certificate (PDF)</span>
    </a>
    <span>2024</span>
    </div>
    <div class="cert reveal">
    <h4>
        <span class="lang-tr">Zararlı Yazılım Analizi ve Tersine Mühendislik</span>
        <span class="lang-en">Malware Analysis and Reverse Engineering</span>
    </h4>
    <a href="certificas/sibervatan3.pdf" target="_blank" rel="noopener noreferrer">
        <span class="lang-tr">Sertifikayı Görüntüle (PDF)</span>
        <span class="lang-en">View Certificate (PDF)</span>
    </a>
    <span>2024</span>
    </div>
    <div class="cert reveal">
    <h4>
        <span class="lang-tr">Uygulamalı Mobil Hacking</span>
        <span class="lang-en">Applied Mobile Hacking</span>
    </h4>
    <a href="certificas/raconf24.pdf" target="_blank" rel="noopener noreferrer">
        <span class="lang-tr">Sertifikayı Görüntüle (PDF)</span>
        <span class="lang-en">View Certificate (PDF)</span>
    </a>
    <span>2024</span>
    </div>

    </div>
  </div>
</section>

  <section id="code" class="section reveal">
    <div class="container glass text-center">
      <h2 class="section-title"><span class="lang-tr">Kaynak Kodları</span><span class="lang-en">Code Repository</span></h2>
      <p><span class="lang-tr">Projelerin tamamı aşağıdaki klasörde:</span><span class="lang-en">All code available in the folder below:</span></p>
      <br>
      <a href="https://github.com/BaranCelik4807?tab=repositories" class="btn-outline">/repos/</a>
    </div>
  </section>

  <section id="contact" class="section reveal">
    <div class="container glass">
      <h2 class="section-title"><span class="lang-tr">İletişim</span><span class="lang-en">Contact</span></h2>

      <?php if ($error): ?>
        <p style="color: #e53e3e; text-align:center; margin-bottom:1rem;">
          <?= $error ?>
        </p>
      <?php endif; ?>

      <form class="contact-form" action="index.php#contact" method="post">
        <!-- hidden input ile bu formun index.php tarafından işlendiğini belli ediyoruz -->
        <input type="hidden" name="contact_form" value="1">
        
        <input type="text" name="name" placeholder="Adınız / Your Name" required>
        <input type="email" name="email" placeholder="E-posta / Your Email" required>
        <textarea name="message" rows="5" placeholder="Mesajınız / Your Message" required></textarea>
        <button type="submit" class="btn">
          <span class="lang-tr">Gönder</span><span class="lang-en">Send</span>
        </button>
      </form>
    </div>
  </section>

  <footer class="reveal">
    <div class="footer-container">
      <p>© <?= date('Y') ?> Baran Çelik</p>
      <div class="socials">
        <a href="https://github.com/BaranCelik4807" target="_blank">GitHub</a>
        <a href="https://www.linkedin.com/in/baran-%C3%A7elik-aa573225b/" target="_blank">LinkedIn</a>
        <a href="mailto:celikbaran4865@gmail.com">Email</a>
      </div>
    </div>
  </footer>
  <script>
    // Hamburger toggle
    const burger = document.getElementById('burger');
    const navLinks = document.getElementById('nav-links');
    const langSwitch = document.getElementById('lang-switch');
    burger.addEventListener('click', () => {
      navLinks.classList.toggle('open');
      langSwitch.classList.toggle('open');
      burger.classList.toggle('active');
    });
    // Language toggle
    langSwitch.querySelectorAll('button').forEach(btn => {
      btn.addEventListener('click', () => {
        document.body.classList.toggle('lang-tr', btn.dataset.lang==='tr');
        document.body.classList.toggle('lang-en', btn.dataset.lang==='en');
      });
    });
    // Scroll reveal
    const reveals = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('active'); io.unobserve(e.target); }
      });
    }, { threshold: 0.15 });
    reveals.forEach(r => io.observe(r));
    // Navbar scroll effect
    const nav = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
      nav.classList.toggle('scrolled', window.scrollY > 20);
    });
  </script>
</body>
</html>
