<?php
session_start();
$hata_mesaji = "";
$kizgin_mod = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $girilen_kadi = $_POST["kullanici_adi"];
    $girilen_sifre = $_POST["sifre"];
    
    if ($girilen_kadi === "admin" && $girilen_sifre === "admin123") {
        $_SESSION['giris_yapildi'] = true;
        $_SESSION['kullanici_adi'] = "Admin";
        $_SESSION['rol'] = "admin"; 
        header("Location: anasayfa.php"); 
        exit();
    }
    
    $dosya_yolu = 'kullanicilar.json';
    $giris_basarili = false;

    if (file_exists($dosya_yolu)) {
        $kullanicilar = json_decode(file_get_contents($dosya_yolu), true);
        
        foreach ($kullanicilar as $user) {
            if ($user['kullanici_adi'] === $girilen_kadi && $user['sifre'] === $girilen_sifre) {
                $_SESSION['giris_yapildi'] = true;
                $_SESSION['kullanici_adi'] = $user['kullanici_adi'];
                $_SESSION['rol'] = $user['rol']; 
                $giris_basarili = true;
                break;
            }
        }
    }

    if ($giris_basarili) {
        header("Location: anasayfa.php"); 
        exit();
    } else {
        $hata_mesaji = "Hatalı Kullanıcı Adı veya Şifre!";
        $kizgin_mod = true;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="robot-ekrani">

    <div class="ozel-imlec"></div>

    <div class="kapsayici">
        
        <div class="robot-ordusu">
            <div class="robot-kafa <?php if($kizgin_mod) echo 'kizgin'; ?>" style="background-color: #ecf0f1;">
                <div class="goz"><div class="goz-bebegi"></div></div>
                <div class="goz"><div class="goz-bebegi"></div></div>
            </div>

            <div class="robot-kafa <?php if($kizgin_mod) echo 'kizgin'; ?>" style="background-color: #bdc3c7;">
                <div class="goz"><div class="goz-bebegi"></div></div>
                <div class="goz"><div class="goz-bebegi"></div></div>
            </div>

            <div class="robot-kafa <?php if($kizgin_mod) echo 'kizgin'; ?>" style="background-color: #ecf0f1;">
                <div class="goz"><div class="goz-bebegi"></div></div>
                <div class="goz"><div class="goz-bebegi"></div></div>
            </div>
        </div>

        <form class="giris-kutusu" method="POST" action="">
            <h2>Güvenlik Paneli</h2>
            
            <?php if ($hata_mesaji != ""): ?>
                <p style="color: #e74c3c; text-align: center; font-weight: bold; margin: 0;">
                    <?php echo $hata_mesaji; ?>
                </p>
            <?php endif; ?>

            <input type="text" name="kullanici_adi" id="kullaniciAdiInput" placeholder="Kullanıcı Adı" required>
            <input type="password" name="sifre" placeholder="Şifre" id="sifreAlani" required>
            
            <button type="submit" id="girisButonu">Giriş
                <a href="kayit.php" style="display:block; text-align:center; margin-top:10px; color:#3498db;">Kayıt Ol</a>
            </button>
        </form>

    </div>

    <script>
        const imlec = document.querySelector('.ozel-imlec');
        const gozler = document.querySelectorAll('.goz');
        const sifreKutusu = document.getElementById('sifreAlani');
        const kullaniciAdiKutusu = document.getElementById('kullaniciAdiInput');
        let gozlerKapaliMi = false;

        document.addEventListener('mousemove', (e) => {
            imlec.style.left = e.clientX + 'px';
            imlec.style.top = e.clientY + 'px';
        });
        document.addEventListener('mousedown', () => imlec.classList.add('tikla'));
        document.addEventListener('mouseup', () => imlec.classList.remove('tikla'));

        const tiklanabilirler = document.querySelectorAll('button, input');
        tiklanabilirler.forEach(eleman => {
            eleman.addEventListener('mouseenter', () => imlec.classList.add('buyu'));
            eleman.addEventListener('mouseleave', () => imlec.classList.remove('buyu'));
        });

        document.addEventListener('mousemove', function(e) {
            if (gozlerKapaliMi) return;
            gozler.forEach(function(goz) {
                let gozKonumu = goz.getBoundingClientRect();
                let gozX = gozKonumu.left + (gozKonumu.width / 2);
                let gozY = gozKonumu.top + (gozKonumu.height / 2);
                let radyan = Math.atan2(e.pageX - gozX, e.pageY - gozY);
                let rotasyon = (radyan * (180 / Math.PI) * -1) + 90;
                goz.style.transform = `rotate(${rotasyon}deg)`;
            });
        });

        sifreKutusu.addEventListener('focus', function() {
            gozlerKapaliMi = true;
            gozler.forEach(goz => {
                goz.classList.add('kapali');
                goz.style.transform = 'rotate(0deg)';
            });
        });

        sifreKutusu.addEventListener('blur', function() {
            gozlerKapaliMi = false;
            gozler.forEach(goz => goz.classList.remove('kapali'));
        });

        kullaniciAdiKutusu.addEventListener('focus', function() {
            gozler.forEach(goz => goz.classList.add('sasirmis'));
        });

        kullaniciAdiKutusu.addEventListener('blur', function() {
            gozler.forEach(goz => goz.classList.remove('sasirmis'));
        });

    </script>
</body>
</html>