<?php
$varliklar = [
    [
        "isim" => "Gram Altın",
        "kod" => "XAU/TRY",
        "id" => "altinFiyat",
        "ikon_class" => "altin-ikon",
        "fa_ikon" => "fa-coins"
    ],
    [
        "isim" => "ABD Doları",
        "kod" => "USD/TRY",
        "id" => "dolarFiyat",
        "ikon_class" => "dolar-ikon",
        "fa_ikon" => "fa-dollar-sign"
    ],
    [
        "isim" => "Euro",
        "kod" => "EUR/TRY",
        "id" => "euroFiyat",
        "ikon_class" => "euro-ikon",
        "fa_ikon" => "fa-euro-sign"
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
    <title>Piyasa Verileri - PHP</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #1e272e;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            cursor: none;
            overflow: hidden;
        }

        .finans-karti {
            background-color: #2f3640;
            width: 350px;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            border: 1px solid #353b48;
            position: relative;
        }

        .geri-don {
            position: absolute;
            top: 20px; left: 20px;
            color: #bdc3c7;
            text-decoration: none;
            font-size: 20px;
            transition: 0.3s;
            z-index: 10;
        }

        h2 { text-align: center; margin-bottom: 30px; color: #f5f6fa; border-bottom: 2px solid #e1b12c; padding-bottom: 15px; }

        .varlik-listesi { display: flex; flex-direction: column; gap: 20px; }

        .varlik {
            display: flex; align-items: center; justify-content: space-between;
            background-color: #353b48; padding: 15px; border-radius: 12px; transition: 0.3s;
        }

        .ikon-kutu { width: 40px; height: 40px; border-radius: 50%; display: flex; justify-content: center; align-items: center; }
        .altin-ikon { background: rgba(251, 197, 49, 0.2); color: #fbc531; }
        .dolar-ikon { background: rgba(76, 209, 55, 0.2); color: #4cd137; }
        .euro-ikon  { background: rgba(0, 168, 255, 0.2); color: #00a8ff; }

        .fiyat { font-size: 18px; font-weight: bold; color: white; }
    </style>
</head>
<body>

    <div class="ozel-imlec"></div>
    
    <?php include 'navbar.php'; ?>

    <div class="finans-karti">
        <a href="anasayfa.php" class="geri-don"><i class="fa-solid fa-arrow-left"></i></a>

        <h2>Piyasa Durumu</h2>

        <div class="varlik-listesi">
            
            <?php foreach ($varliklar as $v): ?>
                <div class="varlik">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div class="ikon-kutu <?php echo $v['ikon_class']; ?>">
                            <i class="fa-solid <?php echo $v['fa_ikon']; ?>"></i>
                        </div>
                        <div>
                            <div style="font-weight: bold;"><?php echo $v['isim']; ?></div>
                            <div style="font-size: 12px; color: #7f8c8d;"><?php echo $v['kod']; ?></div>
                        </div>
                    </div>
                    <div class="fiyat" id="<?php echo $v['id']; ?>">...</div>
                </div>
            <?php endforeach; ?>

        </div>

        <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #7f8c8d;">
            Son Güncelleme: <span id="saat"><?php echo date("H:i:s"); ?></span>
        </div>
    </div>

    <script>
        const apiUrl = "https://api.exchangerate-api.com/v4/latest/USD";

        async function verileriGetir() {
            try {
                const cevap = await fetch(apiUrl);
                const veri = await cevap.json();
                const dolarKuru = veri.rates.TRY;
                
                document.getElementById('dolarFiyat').innerText = dolarKuru.toFixed(2) + " ₺";
                document.getElementById('euroFiyat').innerText = (dolarKuru / veri.rates.EUR).toFixed(2) + " ₺";
                document.getElementById('altinFiyat').innerText = (dolarKuru * 87.5).toFixed(0) + " ₺";
            } catch (hata) { console.log(hata); }
        }
        verileriGetir();

        const imlec = document.querySelector('.ozel-imlec');
        document.addEventListener('mousemove', (e) => {
            imlec.style.left = e.clientX + 'px';
            imlec.style.top = e.clientY + 'px';
        });
    </script>
</body>
</html>