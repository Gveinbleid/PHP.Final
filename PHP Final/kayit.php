<?php
session_start();
$mesaj = "";

if (isset($_SESSION['giris_yapildi']) && $_SESSION['giris_yapildi'] === true) {
    header("Location: anasayfa.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kadi = htmlspecialchars($_POST["kullanici_adi"]);
    $sifre = htmlspecialchars($_POST["sifre"]);
    
    if (!empty($kadi) && !empty($sifre)) {
        $dosya_yolu = 'kullanicilar.json';
        
        if (!file_exists($dosya_yolu)) {
            file_put_contents($dosya_yolu, '[]');
        }
        
        $kullanicilar = json_decode(file_get_contents($dosya_yolu), true);
        
        $kullanici_var = false;
        foreach ($kullanicilar as $user) {
            if ($user['kullanici_adi'] === $kadi) {
                $kullanici_var = true;
                break;
            }
        }
        
        if ($kullanici_var) {
            $mesaj = "<div style='background: #e74c3c; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>Bu kod adı zaten alınmış!</div>";
        } else {
            $kullanicilar[] = [
                "kullanici_adi" => $kadi,
                "sifre" => $sifre,
                "rol" => "uye"
            ];
            file_put_contents($dosya_yolu, json_encode($kullanicilar, JSON_PRETTY_PRINT));
            $mesaj = "<div style='background: #2ecc71; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>Harika! Kayıt Başarılı. <br> Artık giriş yapabilirsin.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sisteme Kayıt Ol</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="robot-ekrani">
    <div class="ozel-imlec"></div>

    <div class="kapsayici">
        
        <div class="giris-kutusu" style="text-align: center; border: 2px solid #3498db; box-shadow: 0 0 20px rgba(52, 152, 219, 0.5);">
            <h2 style="color: #3498db;"><i class="fa-solid fa-user-plus"></i> Yeni Kayıt</h2>
            
            <?php echo $mesaj; ?>

            <form method="POST" action="">
                <input type="text" name="kullanici_adi" placeholder="Yeni Kod Adı Belirle" required style="width: 100%; margin-bottom: 15px; padding: 12px;">
                <input type="password" name="sifre" placeholder="Şifre Belirle" required style="width: 100%; margin-bottom: 15px; padding: 12px;">
                
                <button type="submit" style="width: 100%; background-color: #3498db; padding: 15px; font-size: 16px; margin-bottom: 15px;">Kayıt Ol</button>
            </form>
            
            <a href="index.php" style="color: #888; text-decoration: none; font-size: 14px;">← Zaten hesabın var mı? Giriş yap</a>
        </div>
    </div>

    <script>
        const imlec = document.querySelector('.ozel-imlec');
        document.addEventListener('mousemove', (e) => {
            imlec.style.left = e.clientX + 'px';
            imlec.style.top = e.clientY + 'px';
        });
    </script>
</body>
</html>