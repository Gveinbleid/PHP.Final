<?php
$sehir_bilgisi = [
    "isim" => "Malatya",
    "ulke" => "Türkiye",
    "enlem" => 38.3552,
    "boylam" => 38.3095
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
    <title><?php echo $sehir_bilgisi['isim']; ?> Hava Durumu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            color: white;
            cursor: none; 
            overflow: hidden;
        }

        .hava-karti {
            background: rgba(255, 255, 255, 0.2);
            padding: 40px;
            border-radius: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            width: 300px;
            position: relative;
        }

        .geri-don {
            position: absolute;
            top: 15px;
            left: 15px;
            color: white;
            text-decoration: none;
            font-size: 20px;
            background: rgba(0,0,0,0.2);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: 0.3s;
            z-index: 10;
        }
        .geri-don:hover { background: rgba(0,0,0,0.4); }

        h1 { margin: 0; font-size: 32px; text-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        h3 { margin: 5px 0 20px 0; font-weight: normal; opacity: 0.9; }
        .derece { font-size: 80px; font-weight: bold; margin: 10px 0; }
        .ikon-kutusu i { font-size: 100px; margin-bottom: 20px; }
        .detaylar { display: flex; justify-content: space-around; margin-top: 30px; background: rgba(0,0,0,0.2); padding: 15px; border-radius: 15px; }
    </style>
</head>
<body>

    <div class="ozel-imlec"></div>
    
    <?php include 'navbar.php'; ?>

    <div class="hava-karti">
        <a href="anasayfa.php" class="geri-don"><i class="fa-solid fa-arrow-left"></i></a>

        <h1><?php echo $sehir_bilgisi['isim']; ?></h1>
        <h3><?php echo $sehir_bilgisi['ulke']; ?></h3>

        <div class="ikon-kutusu">
            <i class="fa-solid fa-spinner fa-spin" id="havaIkonu"></i> 
        </div>

        <div class="derece"><span id="sicaklik">--</span>°C</div>
        <div id="durumYazisi">Veri Çekiliyor...</div>

        <div class="detaylar">
            <div class="detay">
                <i class="fa-solid fa-wind"></i>
                <p>Rüzgar</p>
                <p id="ruzgar">-- km/s</p>
            </div>
        </div>
    </div>

    <script>
        const lat = <?php echo $sehir_bilgisi['enlem']; ?>;
        const lon = <?php echo $sehir_bilgisi['boylam']; ?>;
        
        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`;

        async function havayiGetir() {
            try {
                const cevap = await fetch(url);
                const veri = await cevap.json();
                
                document.getElementById('sicaklik').innerText = Math.round(veri.current_weather.temperature);
                document.getElementById('ruzgar').innerText = veri.current_weather.windspeed + " km/s";
                
                const ikon = document.getElementById('havaIkonu');
                const durum = document.getElementById('durumYazisi');
                ikon.classList.remove('fa-spinner', 'fa-spin');

                const kod = veri.current_weather.weathercode;
                if (kod === 0) { ikon.className = "fa-solid fa-sun"; durum.innerText = "Güneşli"; }
                else if (kod <= 3) { ikon.className = "fa-solid fa-cloud-sun"; durum.innerText = "Parçalı Bulutlu"; }
                else { ikon.className = "fa-solid fa-cloud"; durum.innerText = "Kapalı"; }

            } catch (hata) {
                console.log("Hata:", hata);
            }
        }
        havayiGetir();

        const imlec = document.querySelector('.ozel-imlec');
        document.addEventListener('mousemove', (e) => {
            imlec.style.left = e.clientX + 'px';
            imlec.style.top = e.clientY + 'px';
        });
    </script>
</body>
</html>