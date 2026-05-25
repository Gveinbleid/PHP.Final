<?php
$bildirim = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $isim = htmlspecialchars($_POST["isim"]);
    $konu = htmlspecialchars($_POST["konu"]);
    $mesaj = htmlspecialchars($_POST["mesaj"]);

    if (!empty($isim) && !empty($konu) && !empty($mesaj)) {
        $bildirim = "<div style='color: #4cd137; text-align: center; margin-bottom: 15px; font-weight: bold;'>
                        Sinyal Alındı, Kaptan $isim! <br>
                        '$konu' konulu mesajın veri tabanımıza (şimdilik havaya) ulaştı.
                     </div>";
    } else {
        $bildirim = "<div style='color: #ff4757; text-align: center; margin-bottom: 15px; font-weight: bold;'>
                        Eksik parametre! Lütfen tüm alanları doldur.
                     </div>";
    }
}
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
    <title>İletişim Hattı - PHP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0b0c10;
            font-family: 'Segoe UI', sans-serif;
            color: #66fcf1;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            overflow: hidden;
            cursor: none;
        }

        .ozel-imlec {
            position: fixed;
            width: 20px; height: 20px;
            border: 2px solid #66fcf1;
            border-radius: 50%;
            pointer-events: none;
            transform: translate(-50%, -50%);
            transition: width 0.2s, height 0.2s, background-color 0.2s;
            z-index: 9999;
            box-shadow: 0 0 10px #66fcf1;
        }
        .ozel-imlec.tikla { width: 10px; height: 10px; background-color: #66fcf1; }
        .ozel-imlec.buyu { width: 50px; height: 50px; background-color: rgba(102, 252, 241, 0.1); border-color: white; }

        .arka-plan {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(102, 252, 241, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(102, 252, 241, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: -1;
        }

        .iletisim-kutusu {
            background: rgba(31, 40, 51, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(102, 252, 241, 0.2);
            width: 350px;
            border: 1px solid #45a29e;
            position: relative;
        }

        .geri-don {
            position: absolute;
            top: -50px;
            left: 0;
            color: #c5c6c7;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: 0.3s;
        }
        .geri-don:hover { color: #66fcf1; }

        h2 { text-align: center; margin-bottom: 30px; letter-spacing: 2px; }

        .giris-grubu {
            position: relative;
            margin-bottom: 25px;
        }

        input, textarea {
            width: 100%;
            padding: 10px 0;
            background: transparent;
            border: none;
            border-bottom: 2px solid #45a29e;
            color: white;
            outline: none;
            font-size: 16px;
            resize: none;
        }

        label {
            position: absolute;
            top: 10px;
            left: 0;
            color: #888;
            pointer-events: none;
            transition: 0.3s ease;
        }

        input:focus ~ label,
        input:valid ~ label,
        textarea:focus ~ label,
        textarea:valid ~ label {
            top: -20px;
            font-size: 12px;
            color: #66fcf1;
        }

        .cizgi {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #66fcf1;
            transition: 0.3s ease;
            box-shadow: 0 0 10px #66fcf1;
        }

        input:focus ~ .cizgi,
        textarea:focus ~ .cizgi {
            width: 100%;
        }

        .gonder-btn {
            width: 100%;
            padding: 12px;
            background: transparent;
            border: 2px solid #66fcf1;
            color: #66fcf1;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .gonder-btn:hover {
            background: #66fcf1;
            color: #0b0c10;
            box-shadow: 0 0 20px rgba(102, 252, 241, 0.6);
        }
    </style>
</head>
<body>

    <div class="ozel-imlec"></div>
    <div class="arka-plan"></div>

    <div class="iletisim-kutusu">
        <a href="anasayfa.php" class="geri-don"><i class="fa-solid fa-arrow-left"></i> Vazgeç</a>

        <h2>BANA ULAŞ</h2>

        <?php echo $bildirim; ?>

        <form method="POST" action="">
            <div class="giris-grubu">
                <input type="text" name="isim" required>
                <label>Kod Adın (İsim)</label>
                <div class="cizgi"></div>
            </div>

            <div class="giris-grubu">
                <input type="text" name="konu" required>
                <label>Konu</label>
                <div class="cizgi"></div>
            </div>

            <div class="giris-grubu">
                <textarea rows="4" name="mesaj" required></textarea>
                <label>Mesajın</label>
                <div class="cizgi"></div>
            </div>

            <button type="submit" class="gonder-btn">
                <i class="fa-solid fa-paper-plane"></i> Gönder
            </button>
        </form>
    </div>

    <script>
        const imlec = document.querySelector('.ozel-imlec');
        document.addEventListener('mousemove', (e) => {
            imlec.style.left = e.clientX + 'px';
            imlec.style.top = e.clientY + 'px';
        });
        document.addEventListener('mousedown', () => imlec.classList.add('tikla'));
        document.addEventListener('mouseup', () => imlec.classList.remove('tikla'));

        const tiklanabilirler = document.querySelectorAll('button, a, input, textarea');
        tiklanabilirler.forEach(eleman => {
            eleman.addEventListener('mouseenter', () => imlec.classList.add('buyu'));
            eleman.addEventListener('mouseleave', () => imlec.classList.remove('buyu'));
        });
    </script>
</body>
</html>