<?php
$projeler = [
    [
        "baslik" => "Hava Durumu",
        "aciklama" => "Malatya anlık verisi.",
        "ikon" => "fa-cloud-sun-rain",
        "link" => "havadurumu.php",
        "renk_sinifi" => "hava"
    ],
    [
        "baslik" => "Piyasa Takibi",
        "aciklama" => "Döviz ve Altın kurları.",
        "ikon" => "fa-coins",
        "link" => "finans.php",
        "renk_sinifi" => "altin"
    ],
    [
        "baslik" => "Doğa Müzesi",
        "aciklama" => "Anadolu türleri.",
        "ikon" => "fa-paw",
        "link" => "hayvanlar.php",
        "renk_sinifi" => "hayvan"
    ],
    [
        "baslik" => "Sinema Odası",
        "aciklama" => "Video ve Müzik keyfi.",
        "ikon" => "fa-film",
        "link" => "medya.php",
        "renk_sinifi" => "sinema"
    ]
];
?>
<?php
session_start();
if (!isset($_SESSION['giris_yapildi']) || $_SESSION['giris_yapildi'] !== true) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Üs - PHP</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="ozel-imlec"></div>

    <?php include 'navbar.php'; ?>

    <header class="hero">
        <div class="hero-icerik">
            <h1>Geleceği <span class="yazilan-yazi">Tasarlıyorum</span></h1>
            <p>Web geliştirme yolculuğumdaki projelerin, deneylerin ve araçların toplandığı dijital arşiv.</p>
            <a href="#projeler" class="kesfet-btn">Projeleri Keşfet ↓</a>
        </div>
    </header>

    <section id="projeler" class="projeler-alani">
        <h2 class="bolum-basligi">Kontrol Paneli</h2>
        
        <div class="merkez-izgara">
    
            <?php foreach ($projeler as $proje): ?>
                <div class="kutu <?php echo $proje['renk_sinifi']; ?>" onclick="window.location.href='<?php echo $proje['link']; ?>'">
                    <div class="ikon-kapsayici"><i class="fa-solid <?php echo $proje['ikon']; ?>"></i></div>
                    <div class="yazi-alani">
                        <h3><?php echo $proje['baslik']; ?></h3>
                        <p><?php echo $proje['aciklama']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </section>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Kod Üssü. Tüm hakları saklıdır.</p>
        <div class="sosyal-ikonlar">
            <i class="fa-brands fa-github"></i>
            <i class="fa-brands fa-linkedin"></i>
            <i class="fa-brands fa-instagram"></i>
        </div>
    </footer>
</body>
<script>
        const imlec = document.querySelector('.ozel-imlec');
        
        document.addEventListener('mousemove', (e) => {
            imlec.style.left = e.clientX + 'px';
            imlec.style.top = e.clientY + 'px';
        });

        document.addEventListener('mousedown', () => imlec.classList.add('tikla'));
        document.addEventListener('mouseup', () => imlec.classList.remove('tikla'));

        const tiklanabilirler = document.querySelectorAll('a, button, .kutu, .mini-oyun-widget');
        tiklanabilirler.forEach(eleman => {
            eleman.addEventListener('mouseenter', () => imlec.classList.add('buyu'));
            eleman.addEventListener('mouseleave', () => imlec.classList.remove('buyu'));
        });
    </script>
</html>