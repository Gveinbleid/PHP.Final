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
    <title>Neon Uzay Savaşı - PHP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #000;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
            font-family: 'Segoe UI', sans-serif;
            overflow: hidden;
        }

        canvas {
            border: 2px solid #00d2d3;
            box-shadow: 0 0 20px #00d2d3;
            background: radial-gradient(circle, #1e272e 0%, #000 100%);
            border-radius: 10px;
            cursor: none;
        }

        .bilgi {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 20px;
            pointer-events: none;
            z-index: 5;
        }

        .geri-don {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #bdc3c7;
            text-decoration: none;
            border: 1px solid #555;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 10;
            cursor: pointer;
        }
        .geri-don:hover { background: white; color: black; }

        #oyunSonu {
            position: absolute;
            display: none;
            flex-direction: column;
            align-items: center;
            background: rgba(0,0,0,0.95);
            padding: 50px;
            border: 2px solid #ff4757;
            border-radius: 20px;
            text-align: center;
            z-index: 20;
            box-shadow: 0 0 50px #ff4757;
        }
        #oyunSonu h1 { color: #ff4757; font-size: 50px; margin: 0; }
        #oyunSonu p { font-size: 24px; margin: 20px 0; }
        #oyunSonu button {
            margin-top: 20px;
            padding: 15px 40px;
            font-size: 20px;
            background: #ff4757;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
        }
        #oyunSonu button:hover { background: white; color: #ff4757; }
    </style>
</head>
<body>

    <a href="anasayfa.php" class="geri-don">Çıkış</a>

    <div class="bilgi">
        Skor: <span id="skorTablosu">0</span>
    </div>

    <canvas id="sahne" width="800" height="600"></canvas>

    <div id="oyunSonu">
        <h1>OYUN BİTTİ!</h1>
        <p>Üssü Koruyamadın!</p>
        <p>Skorun: <span id="sonSkor">0</span></p>
        <button onclick="oyunuYenidenBaslat()">Tekrar Dene</button>
    </div>

    <script>
        const canvas = document.getElementById('sahne');
        const ctx = canvas.getContext('2d');
        const skorEl = document.getElementById('skorTablosu');
        const oyunSonuEkrani = document.getElementById('oyunSonu');
        const sonSkorEl = document.getElementById('sonSkor');

        let oyunDevamEdiyor = true;
        let skor = 0;
        let cerceveSayaci = 0;

        const oyuncu = {
            x: canvas.width / 2 - 25,
            y: canvas.height - 60,
            w: 50,
            h: 50,
            hiz: 7,
            renk: '#00d2d3'
        };

        let mermiler = [];
        let dusmanlar = [];

        const tuslar = {};
        document.addEventListener('keydown', (e) => tuslar[e.code] = true);
        document.addEventListener('keyup', (e) => tuslar[e.code] = false);

        function oyunDongusu() {
            if (!oyunDevamEdiyor) return;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            oyuncuyuHareketEttir();
            mermileriYonet();
            dusmanlariYonet();
            carpismalariKontrolEt();
            
            ctx.fillStyle = oyuncu.renk;
            ctx.beginPath();
            ctx.moveTo(oyuncu.x + oyuncu.w / 2, oyuncu.y);
            ctx.lineTo(oyuncu.x, oyuncu.y + oyuncu.h);
            ctx.lineTo(oyuncu.x + oyuncu.w, oyuncu.y + oyuncu.h);
            ctx.fill();

            cerceveSayaci++;
            requestAnimationFrame(oyunDongusu);
        }

        function oyuncuyuHareketEttir() {
            if ((tuslar['ArrowLeft'] || tuslar['KeyA']) && oyuncu.x > 0) {
                oyuncu.x -= oyuncu.hiz;
            }
            if ((tuslar['ArrowRight'] || tuslar['KeyD']) && oyuncu.x + oyuncu.w < canvas.width) {
                oyuncu.x += oyuncu.hiz;
            }
            if (tuslar['Space'] && cerceveSayaci % 10 === 0) {
                mermiler.push({
                    x: oyuncu.x + oyuncu.w / 2 - 2.5,
                    y: oyuncu.y,
                    w: 5,
                    h: 15,
                    renk: '#e056fd'
                });
            }
        }

        function mermileriYonet() {
            mermiler.forEach((mermi, index) => {
                mermi.y -= 10;
                
                ctx.fillStyle = mermi.renk;
                ctx.fillRect(mermi.x, mermi.y, mermi.w, mermi.h);

                if (mermi.y < 0) mermiler.splice(index, 1);
            });
        }

        function dusmanlariYonet() {
            if (cerceveSayaci % 40 === 0) {
                const boy = Math.random() * 30 + 30;
                const xKonum = Math.random() * (canvas.width - boy);
                
                dusmanlar.push({
                    x: xKonum,
                    y: -50,
                    w: boy,
                    h: boy,
                    hiz: Math.random() * 2 + 2,
                    renk: '#ff4757'
                });
            }

            dusmanlar.forEach((dusman, index) => {
                dusman.y += dusman.hiz;

                ctx.fillStyle = dusman.renk;
                ctx.fillRect(dusman.x, dusman.y, dusman.w, dusman.h);

                if (dusman.y + dusman.h >= canvas.height) {
                    oyunuBitir();
                }
            });
        }

        function carpismalariKontrolEt() {
            dusmanlar.forEach((dusman, dIndex) => {
                if (
                    oyuncu.x < dusman.x + dusman.w &&
                    oyuncu.x + oyuncu.w > dusman.x &&
                    oyuncu.y < dusman.y + dusman.h &&
                    oyuncu.h + oyuncu.y > dusman.y
                ) {
                    oyunuBitir();
                }

                mermiler.forEach((mermi, mIndex) => {
                    if (
                        mermi.x < dusman.x + dusman.w &&
                        mermi.x + mermi.w > dusman.x &&
                        mermi.y < dusman.y + dusman.h &&
                        mermi.h + mermi.y > dusman.y
                    ) {
                        dusmanlar.splice(dIndex, 1);
                        mermiler.splice(mIndex, 1);
                        skor += 10;
                        skorEl.innerText = skor;
                    }
                });
            });
        }

        function oyunuBitir() {
    oyunDevamEdiyor = false;
    oyunSonuEkrani.style.display = 'flex';
    sonSkorEl.innerText = skor;

    // YENİ EKLENEN KISIM: Skoru arka planda skor_kaydet.php'ye fırlatıyoruz (Fetch API)
    if (skor > 0) {
        fetch('skor_kaydet.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ skor: skor }) // Skoru JSON formatına çevirip yolluyoruz
        })
        .then(response => response.json())
        .then(data => {
            console.log("Skor başarıyla sunucuya iletildi!");
            // İstersen oyun sonu ekranına "Skor Tablosunu Gör" butonu ekleyebilirsin
            if (!document.getElementById('tabloGitBtn')) {
                const btn = document.createElement('button');
                btn.id = 'tabloGitBtn';
                btn.innerText = 'Liderlik Tablosu';
                btn.style.marginTop = '10px';
                btn.style.background = '#00d2d3';
                btn.onclick = () => window.location.href = 'skor_tablosu.php';
                oyunSonuEkrani.appendChild(btn);
            }
        })
        .catch(hata => console.error("Skor gönderilirken hata oluştu:", hata));
    }
    }

        function oyunuYenidenBaslat() {
            oyunDevamEdiyor = true;
            skor = 0;
            skorEl.innerText = '0';
            dusmanlar = [];
            mermiler = [];
            oyunSonuEkrani.style.display = 'none';
            oyuncu.x = canvas.width / 2 - 25;
            
            oyunDongusu();
        }

        oyunDongusu();
    </script>
</body>
</html>