<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: anasayfa.php");
    exit();
}

$dosya_yolu = 'yorumlar.json';
$yorumlar = [];

if (file_exists($dosya_yolu)) {
    $yorumlar = json_decode(file_get_contents($dosya_yolu), true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sil_id"])) {
    $silinecek_index = $_POST["sil_id"];
    
    unset($yorumlar[$silinecek_index]);
    
    $yorumlar = array_values($yorumlar);
    
    file_put_contents($dosya_yolu, json_encode($yorumlar, JSON_PRETTY_PRINT));
    
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yönetim Paneli</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0b0c10; color: white; padding-top: 100px; }
        .admin-kutu { max-width: 800px; margin: 0 auto; background: #1f2833; padding: 30px; border-radius: 10px; border: 2px solid #f1c40f; }
        .yorum-satiri { display: flex; justify-content: space-between; align-items: center; background: #0b0c10; padding: 15px; margin-bottom: 10px; border-left: 4px solid #e74c3c; border-radius: 5px; }
        .sil-btn { background: #e74c3c; color: white; border: none; padding: 8px 15px; cursor: pointer; border-radius: 5px; font-weight: bold; }
        .sil-btn:hover { background: #c0392b; }
    </style>
</head>
<body>
    <div class="ozel-imlec"></div>
    <?php include 'navbar.php'; ?>

    <div class="admin-kutu">
        <h2 style="color: #f1c40f; margin-top:0;"><i class="fa-solid fa-lock"></i> Sistem Yönetim Paneli</h2>
        <p style="color: #8892b0;">Buradan Ziyaretçi Defterindeki uygunsuz mesajları silebilirsiniz.</p>
        
        <hr style="border-color: #333; margin-bottom: 20px;">

        <?php if (empty($yorumlar)): ?>
            <p>Sistemde henüz hiç yorum bulunmuyor.</p>
        <?php else: ?>
            
            <?php foreach ($yorumlar as $index => $yorum): ?>
                <div class="yorum-satiri">
                    <div>
                        <strong style="color: #66fcf1;"><?php echo $yorum['isim']; ?></strong> 
                        <span style="color: #888; font-size: 12px;">(<?php echo $yorum['tarih']; ?>)</span>
                        <br>
                        <span><?php echo htmlspecialchars($yorum['mesaj']); ?></span>
                    </div>
                    
                    <form method="POST" action="" style="margin: 0;">
                        <input type="hidden" name="sil_id" value="<?php echo $index; ?>">
                        <button type="submit" class="sil-btn"><i class="fa-solid fa-trash"></i> Sil</button>
                    </form>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
    <script>
        const imlec = document.querySelector('.ozel-imlec');
        
        document.addEventListener('mousemove', (e) => {
            imlec.style.left = e.clientX + 'px';
            imlec.style.top = e.clientY + 'px';
        });

        document.addEventListener('mousedown', () => imlec.classList.add('tikla'));
        document.addEventListener('mouseup', () => imlec.classList.remove('tikla'));

        const tiklanabilirler = document.querySelectorAll('button, a');
        tiklanabilirler.forEach(eleman => {
            eleman.addEventListener('mouseenter', () => imlec.classList.add('buyu'));
            eleman.addEventListener('mouseleave', () => imlec.classList.remove('buyu'));
        });
    </script>
</body>
</html>