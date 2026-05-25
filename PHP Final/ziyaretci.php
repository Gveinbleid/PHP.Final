<?php
$dosya_yolu = 'yorumlar.json';

if (!file_exists($dosya_yolu)) {
    file_put_contents($dosya_yolu, '[]');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isim = htmlspecialchars($_POST["isim"]);
    $mesaj = htmlspecialchars($_POST["mesaj"]);
    
    if (!empty($isim) && !empty($mesaj)) {
        
        $mevcut_yorumlar = json_decode(file_get_contents($dosya_yolu), true);
        
        $yeni_yorum = [
            "isim" => $isim,
            "mesaj" => $mesaj,
            "tarih" => date("d.m.Y H:i") 
        ];
        
        array_unshift($mevcut_yorumlar, $yeni_yorum);
        
        file_put_contents($dosya_yolu, json_encode($mevcut_yorumlar, JSON_PRETTY_PRINT));
        
        header("Location: ziyaretci.php");
        exit();
    }
}

$yorumlar = json_decode(file_get_contents($dosya_yolu), true);
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
    <title>Ziyaretçi Defteri - PHP</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #0b0c10; cursor: none; margin: 0; padding-top: 100px; color: white; }
        .konteyner { max-width: 800px; margin: 0 auto; padding: 20px; }
        
        .yorum-formu { background: #1f2833; padding: 30px; border-radius: 15px; border: 1px solid #45a29e; margin-bottom: 40px; }
        .yorum-formu h2 { color: #66fcf1; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #45a29e; padding-bottom: 10px; }
        .form-grup { margin-bottom: 15px; }
        .form-grup input, .form-grup textarea { width: 100%; padding: 12px; background: #0b0c10; border: 1px solid #45a29e; color: white; border-radius: 5px; outline: none; }
        .form-grup input:focus, .form-grup textarea:focus { box-shadow: 0 0 10px rgba(102, 252, 241, 0.3); }
        .btn-gonder { background: transparent; border: 2px solid #66fcf1; color: #66fcf1; padding: 10px 25px; font-weight: bold; border-radius: 5px; cursor: pointer; transition: 0.3s; }
        .btn-gonder:hover { background: #66fcf1; color: #0b0c10; }

        .yorum-kutusu { background: #1f2833; padding: 20px; border-radius: 10px; margin-bottom: 15px; border-left: 4px solid #66fcf1; transition: 0.3s; }
        .yorum-kutusu:hover { transform: translateX(5px); }
        .yorum-baslik { display: flex; justify-content: space-between; margin-bottom: 10px; color: #8892b0; font-size: 14px; }
        .yorum-isim { font-weight: bold; color: #66fcf1; font-size: 16px; }
        .yorum-icerik { color: #c5c6c7; line-height: 1.5; }
        .bos-mesaj { text-align: center; color: #8892b0; padding: 20px; font-style: italic; }
    </style>
</head>
<body>

    <div class="ozel-imlec"></div>
    
    <?php include 'navbar.php'; ?>

    <div class="konteyner">
        
        <div class="yorum-formu">
            <h2><i class="fa-solid fa-pen-nib"></i> Ziyaretçi Defteri</h2>
            <p style="color: #8892b0; font-size: 14px; margin-bottom: 20px;">Site hakkındaki düşüncelerini buraya bırakabilirsin. Veriler JSON formatında saklanmaktadır.</p>
            
            <form method="POST" action="">
                <div class="form-grup">
                    <input type="text" name="isim" placeholder="Adın veya Rumuzun" required>
                </div>
                <div class="form-grup">
                    <textarea name="mesaj" rows="4" placeholder="Mesajın..." required></textarea>
                </div>
                <button type="submit" class="btn-gonder"><i class="fa-solid fa-paper-plane"></i> Yorumu Gönder</button>
            </form>
        </div>

        <div class="yorum-listesi">
            <h3 style="color: white; margin-bottom: 20px;">Son Yorumlar (<span style="color: #66fcf1;"><?php echo count($yorumlar); ?></span>)</h3>
            
            <?php if (empty($yorumlar)): ?>
                <div class="bos-mesaj">Henüz hiç yorum yapılmamış. İlk yorumu sen yap!</div>
            <?php else: ?>
                
                <?php foreach ($yorumlar as $yorum): ?>
                    <div class="yorum-kutusu">
                        <div class="yorum-baslik">
                            <span class="yorum-isim"><i class="fa-solid fa-user"></i> <?php echo $yorum['isim']; ?></span>
                            <span class="yorum-tarih"><i class="fa-solid fa-clock"></i> <?php echo $yorum['tarih']; ?></span>
                        </div>
                        <div class="yorum-icerik">
                            <?php echo nl2br($yorum['mesaj']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>

    </div>

    <script>
        const imlec = document.querySelector('.ozel-imlec');
        document.addEventListener('mousemove', (e) => {
            imlec.style.left = e.clientX + 'px';
            imlec.style.top = e.clientY + 'px';
        });
        document.addEventListener('mousedown', () => imlec.classList.add('tikla'));
        document.addEventListener('mouseup', () => imlec.classList.remove('tikla'));
    </script>
</body>
</html>