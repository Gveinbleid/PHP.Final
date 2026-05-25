<?php
session_start();
if (!isset($_SESSION['giris_yapildi'])) {
    header("Location: index.php");
    exit();
}

$dosya_yolu = 'skorlar.json';
$skorlar = file_exists($dosya_yolu) ? json_decode(file_get_contents($dosya_yolu), true) : [];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Liderlik Tablosu</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0b0c10; color: white; padding-top: 100px; }
        .tablo-kutu { max-width: 600px; margin: 0 auto; background: #1f2833; padding: 30px; border-radius: 10px; border: 2px solid #e056fd; box-shadow: 0 0 20px rgba(224, 86, 253, 0.3); }
        .skor-satiri { display: flex; justify-content: space-between; align-items: center; background: #0b0c10; padding: 15px; margin-bottom: 10px; border-left: 4px solid #00d2d3; border-radius: 5px; }
        .derece { font-size: 24px; font-weight: bold; color: #f1c40f; width: 40px; }
        .kral { border-left-color: #f1c40f; box-shadow: 0 0 10px rgba(241, 196, 15, 0.2); }
    </style>
</head>
<body>

    <div class="ozel-imlec"></div>

    <?php include 'navbar.php'; ?>

    <div class="tablo-kutu">
        <h2 style="color: #e056fd; text-align: center; margin-top:0;"><i class="fa-solid fa-trophy"></i> Neon Uzay Liderleri</h2>
        <p style="color: #8892b0; text-align: center;">En yüksek skoru yapan ilk 10 pilot.</p>
        <hr style="border-color: #333; margin-bottom: 20px;">

        <?php if (empty($skorlar)): ?>
            <p style="text-align: center;">Henüz kimse uzaya açılmadı. İlk rekoru sen kır!</p>
        <?php else: ?>
            <?php 
            $sira = 1;
            foreach ($skorlar as $kayit): 
                $ekstra_sinif = ($sira == 1) ? 'kral' : '';
            ?>
                <div class="skor-satiri <?php echo $ekstra_sinif; ?>">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <span class="derece">#<?php echo $sira; ?></span>
                        <strong style="color: #66fcf1; font-size: 18px;"><?php echo htmlspecialchars($kayit['isim']); ?></strong>
                    </div>
                    <div style="font-size: 20px; color: #e056fd; font-weight: bold;">
                        <?php echo $kayit['skor']; ?> Puan
                    </div>
                </div>
            <?php 
                $sira++;
                if($sira > 10) break; // Sadece ilk 10'u göster
            endforeach; 
            ?>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="oyun.php" style="background: #e056fd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Oyuna Dön</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const imlec = document.querySelector('.ozel-imlec');
            
            if (imlec) {
                // Fareyi takip etme
                document.addEventListener('mousemove', (e) => {
                    imlec.style.left = e.clientX + 'px';
                    imlec.style.top = e.clientY + 'px';
                });

                // Tıklama animasyonu
                document.addEventListener('mousedown', () => imlec.classList.add('tikla'));
                document.addEventListener('mouseup', () => imlec.classList.remove('tikla'));

                // Butonların ve linklerin üzerine gelince büyüme animasyonu
                const tiklanabilirler = document.querySelectorAll('button, a, .tablo-kutu');
                tiklanabilirler.forEach(eleman => {
                    eleman.addEventListener('mouseenter', () => imlec.classList.add('buyu'));
                    eleman.addEventListener('mouseleave', () => imlec.classList.remove('buyu'));
                });
            } else {
                console.error("İmleç div'i bulunamadı, HTML yapısını kontrol et.");
            }
        });
    </script>
</body>
</html>