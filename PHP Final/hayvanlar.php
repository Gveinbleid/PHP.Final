<?php
$hayvanlar = [
    [
        "isim" => "Çizgili Sırtlan",
        "resim" => "resimler/sirtlan.jpg",
        "placeholder" => "https://placehold.co/300x450/5e4b35/white?text=Cizgili+Sirtlan",
        "bilgi" => "Anadolu'nun kayalıklarında yaşar. Gece aktif olması nedeniyle 'hayalet' lakabını almıştır. Dikleşebilen yelesi ve çizgili kürküyle tanınır; nesli tehlikededir."
    ],
    [
        "isim" => "Kızıl Geyik",
        "resim" => "resimler/geyik.jpg",
        "placeholder" => "https://placehold.co/300x450/ecf0f1/black?text=Kizil+Geyik",
        "bilgi" => "Ormanların tacına sahip olan bu tür, Anadolu'daki en büyük geyiktir. Görkemli boynuzları nedeniyle geçmişte yok olmanın eşiğine gelse de koruma altındadır."
    ],
    [
        "isim" => "Anadolu Parsı",
        "resim" => "resimler/leo.jpg",
        "placeholder" => "https://placehold.co/300x450/d35400/white?text=Anadolu+Parsi",
        "bilgi" => "Anadolu'nun efsanesi. Yıllarca neslinin tükendiği sanılsa da yapılan araştırmalar hala var olduğunu göstermektedir. Ekosistemin süper avcısıdır."
    ],
    [
        "isim" => "Boz Ayı",
        "resim" => "resimler/ayi.jpg",
        "placeholder" => "https://placehold.co/300x450/c0392b/white?text=Boz+Ayi",
        "bilgi" => "Anadolu'nun en büyük memelisi ve yırtıcısıdır. Karadeniz gibi sık ormanlık bölgelerde yaşayan, aşırı zeki ve hepçil bir türdür."
    ],
    [
        "isim" => "Karakulak",
        "resim" => "resimler/karacal.jpg",
        "placeholder" => "https://placehold.co/300x450/bdc3c7/black?text=Karakulak",
        "bilgi" => "Adını kulağındaki siyah kıllardan alan bu kedi, 3 metre yükseğe zıplayabilme yeteneğiyle bilinir. Bozkır ekosisteminin önemli bir parçasıdır."
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
    <title>Anadolu'nun Egzotik Yaban Hayatı - PHP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="hayvanlar.css">
</head>
<body>

    <div class="ozel-imlec"></div>

    <?php include 'navbar.php'; ?>

    <header class="hero-bolumu">
        <div class="hero-baslik">
            <h1>Anadolu'nun Egzotik Hayvanları</h1>
            <p>Anadolu coğrafyasındaki eşsiz endemik türleri keşfedin.</p>
        </div>

        <div class="sahne">
            <div class="slider" id="sliderHalkasi">
                
                <?php 
                $sayac = 1;
                foreach ($hayvanlar as $hayvan): 
                ?>
                    <div class="kart" style="--i:<?php echo $sayac; ?>;">
                        <img src="<?php echo $hayvan['resim']; ?>" 
                             onerror="this.src='<?php echo $hayvan['placeholder']; ?>'" 
                             alt="<?php echo $hayvan['isim']; ?>">
                        <div class="bilgi">
                            <h3><?php echo $hayvan['isim']; ?></h3>
                            <p><?php echo $hayvan['bilgi']; ?></p>
                        </div>
                    </div>
                <?php 
                    $sayac++;
                endforeach; 
                ?>

            </div>
        </div>

        <div class="kontrol-panel">
            <button class="nav-btn" id="solBtn"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="nav-btn" id="sagBtn"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </header>

    <section class="icerik-bolumu">
        <div class="icerik-container">
            <h2>Mirasımızı Koruyoruz</h2>
            <p>
                Bu bölümün temel amacı, bölgemize özgü bu canlılar hakkında farkındalık yaratmaktır. 
                Sistemimizde şu an toplam <strong><?php echo count($hayvanlar); ?></strong> farklı tür listelenmektedir.
            </p>
        </div>
    </section>

    <script>
        const slider = document.getElementById('sliderHalkasi');
        const solBtn = document.getElementById('solBtn');
        const sagBtn = document.getElementById('sagBtn');
        let mevcutAci = 0;
        const donusMiktari = 72; // 360 / 5 hayvan olduğu için

        sagBtn.addEventListener('click', () => {
            mevcutAci -= donusMiktari;
            slider.style.transform = `rotateX(-5deg) rotateY(${mevcutAci}deg)`;
        });

        solBtn.addEventListener('click', () => {
            mevcutAci += donusMiktari;
            slider.style.transform = `rotateX(-5deg) rotateY(${mevcutAci}deg)`;
        });

        const imlec = document.querySelector('.ozel-imlec');
        document.addEventListener('mousemove', (e) => {
            imlec.style.left = e.clientX + 'px';
            imlec.style.top = e.clientY + 'px';
        });
    </script>

</body>
</html>