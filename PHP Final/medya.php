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
    <title>Sinema Odası - PHP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #1a1a1a;
            color: white;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
            transition: background-color 1s ease;
            overflow: hidden;
            cursor: none;
        }

        .geri-don {
            position: absolute;
            top: 20px; left: 20px;
            color: #bdc3c7;
            text-decoration: none;
            font-size: 18px;
            border: 1px solid #bdc3c7;
            padding: 8px 15px;
            border-radius: 5px;
            z-index: 10;
            transition: 0.3s;
        }
        .geri-don:hover { background: white; color: black; }

        .ekran-cercevesi {
            width: 800px;
            max-width: 90%;
            background: #000;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 0 50px rgba(255, 255, 255, 0.1);
            text-align: center;
            position: relative;
            transition: box-shadow 1s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .ekran-cercevesi:fullscreen {
            width: 100vw;
            height: 100vh;
            max-width: none;
            border-radius: 0;
            padding: 0;
            justify-content: center;
            background: #000;
            cursor: none; 
        }

        .ekran-cercevesi:fullscreen .video-kapsayici {
            height: 100vh;
            width: 100%;
            aspect-ratio: unset;
            border-radius: 0;
        }

        .ekran-cercevesi:fullscreen .kontrol-paneli {
            position: absolute;
            bottom: 30px;
            width: 100%;
            z-index: 20;
            padding-bottom: 20px;
        }

        .ekran-cercevesi:fullscreen .zaman-cubugu-alani {
            position: absolute;
            bottom: 100px;
            width: 90%;
            z-index: 20;
            left: 50%;
            transform: translateX(-50%);
        }

        .video-kapsayici {
            width: 100%;
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            aspect-ratio: 16 / 9; 
        }

        #youtube-player {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0; left: 0;
        }

        .video-koruyucu {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: transparent;
            z-index: 5;
        }

        .zaman-cubugu-alani {
            width: 100%;
            height: 8px;
            background: #333;
            border-radius: 4px;
            margin-top: 15px;
            cursor: none;
            position: relative;
            overflow: hidden;
            transition: height 0.2s;
        }
        
        .zaman-cubugu-alani:hover {
            height: 12px; 
        }

        .zaman-cubugu-dolu {
            height: 100%;
            width: 0%;
            background: #e056fd;
            box-shadow: 0 0 10px #e056fd;
            transition: width 0.1s linear;
        }

        .kontrol-paneli {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
            z-index: 6;
        }

        .btn {
            background: #e056fd;
            border: none;
            padding: 10px 20px;
            color: white;
            font-weight: bold;
            border-radius: 30px;
            cursor: none;
            font-size: 16px;
            transition: 0.3s;
            display: flex; align-items: center; gap: 8px;
            backdrop-filter: blur(5px); 
        }
        .btn:hover { transform: scale(1.1); box-shadow: 0 0 15px #e056fd; }

        .isik-btn { background: #f1c40f; color: #333; }
        .isik-btn:hover { box-shadow: 0 0 15px #f1c40f; }

        .ozel-imlec {
            position: fixed;
            width: 20px; height: 20px;
            border: 2px solid #e056fd;
            border-radius: 50%;
            pointer-events: none;
            transform: translate(-50%, -50%);
            transition: width 0.2s, height 0.2s, background-color 0.2s;
            z-index: 9999;
            box-shadow: 0 0 10px #e056fd;
            top: -100px;
            left: -100px;
        }
        .ozel-imlec.tikla { width: 10px; height: 10px; background-color: #e056fd; }
        .ozel-imlec.buyu { width: 50px; height: 50px; background-color: rgba(224, 86, 253, 0.1); border-color: white; }

        body.karanlik-mod { background-color: #000000; }
        body.karanlik-mod h1 { opacity: 0.2; transition: opacity 1s; }
        body.karanlik-mod p { opacity: 0.2; transition: opacity 1s; }
        body.karanlik-mod .ekran-cercevesi { box-shadow: 0 0 100px rgba(224, 86, 253, 0.3); }

        h1 { margin-bottom: 10px; }
        p { color: #7f8c8d; margin-bottom: 30px; }
    </style>
</head>
<body>

    <a href="anasayfa.php" class="geri-don">← Menü</a>

    <h1>Multimedya Odası</h1>
    <p>Favori içeriklerin için özel alan.</p>

    <div class="ekran-cercevesi" id="tamEkranAlani">
        
        <div class="ozel-imlec"></div>

        <div class="video-kapsayici">
            <div id="youtube-player"></div>
            <div class="video-koruyucu" onclick="oynatDurdur()"></div>
        </div>

        <div class="zaman-cubugu-alani" id="zamanCubugu" onclick="videoyuSar(event)">
            <div class="zaman-cubugu-dolu" id="zamanDolu"></div>
        </div>

        <div class="kontrol-paneli">
            <button class="btn" id="oynatBtn" onclick="oynatDurdur()">
                <i class="fa-solid fa-play"></i> Oynat
            </button>

            <button class="btn" id="sesBtn" onclick="sesiKapat()">
                <i class="fa-solid fa-volume-high"></i> Ses
            </button>

            <button class="btn" onclick="tamEkranYap()">
                <i class="fa-solid fa-expand"></i> Tam Ekran
            </button>

            <button class="btn isik-btn" onclick="isiklariYakSondur()">
                <i class="fa-solid fa-lightbulb"></i> Işıklar
            </button>
        </div>
    </div>

    <script>
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        var player;
        var zamanlayici;

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('youtube-player', {
                width: '100%',
                height: '100%',
                videoId: 'y3gOOskYA0k', 
                playerVars: {
                    'controls': 0,
                    'rel': 0,
                    'disablekb': 1,
                    'modestbranding': 1
                },
                events: {
                    'onStateChange': onPlayerStateChange
                }
            });
        }

        const oynatBtn = document.getElementById('oynatBtn');
        const sesBtn = document.getElementById('sesBtn');
        const zamanDolu = document.getElementById('zamanDolu');
        const body = document.body;

        function onPlayerStateChange(event) {
            if (event.data == YT.PlayerState.PLAYING) {
                oynatBtn.innerHTML = '<i class="fa-solid fa-pause"></i> Durdur';
                zamanlayiciBaslat();
            } else {
                oynatBtn.innerHTML = '<i class="fa-solid fa-play"></i> Oynat';
                clearInterval(zamanlayici);
            }
        }

        function zamanlayiciBaslat() {
            clearInterval(zamanlayici);
            zamanlayici = setInterval(function() {
                if (player && player.getCurrentTime) {
                    var sure = player.getCurrentTime();
                    var toplam = player.getDuration();
                    var yuzde = (sure / toplam) * 100;
                    zamanDolu.style.width = yuzde + "%";
                }
            }, 500);
        }

        function videoyuSar(event) {
            if (!player || !player.getDuration) return;
            
            var cubuk = document.getElementById('zamanCubugu');
            var tiklamaKonumu = event.offsetX;
            var toplamGenislik = cubuk.clientWidth;
            var tiklamaYuzdesi = tiklamaKonumu / toplamGenislik;
            
            var yeniZaman = player.getDuration() * tiklamaYuzdesi;
            
            player.seekTo(yeniZaman, true);
            zamanDolu.style.width = (tiklamaYuzdesi * 100) + "%";
        }

        function oynatDurdur() {
            if (player && player.getPlayerState) {
                var state = player.getPlayerState();
                if (state == YT.PlayerState.PLAYING) {
                    player.pauseVideo();
                } else {
                    player.playVideo();
                }
            }
        }

        function sesiKapat() {
            if (player && player.mute) {
                if (player.isMuted()) {
                    player.unMute();
                    sesBtn.innerHTML = '<i class="fa-solid fa-volume-high"></i> Ses';
                } else {
                    player.mute();
                    sesBtn.innerHTML = '<i class="fa-solid fa-volume-xmark"></i> Sessiz';
                }
            }
        }

        function isiklariYakSondur() {
            body.classList.toggle('karanlik-mod');
        }

        function tamEkranYap() {
            var elem = document.getElementById("tamEkranAlani");
            if (!document.fullscreenElement) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.webkitRequestFullscreen) { 
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        }

        const imlec = document.querySelector('.ozel-imlec');
        
        document.addEventListener('mousemove', (e) => {
            imlec.style.left = e.clientX + 'px';
            imlec.style.top = e.clientY + 'px';
        });

        document.addEventListener('mousedown', () => imlec.classList.add('tikla'));
        document.addEventListener('mouseup', () => imlec.classList.remove('tikla'));

        const tiklanabilirler = document.querySelectorAll('button, a, .video-koruyucu, .zaman-cubugu-alani');
        
        tiklanabilirler.forEach(eleman => {
            eleman.addEventListener('mouseenter', () => imlec.classList.add('buyu'));
            eleman.addEventListener('mouseleave', () => imlec.classList.remove('buyu'));
        });
    </script>
</body>
</html>