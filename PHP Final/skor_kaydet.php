<?php
session_start();

// Sadece giriş yapanlar skor gönderebilir
if (!isset($_SESSION['giris_yapildi'])) {
    exit();
}

// JavaScript'ten gelen veriyi al (Fetch API)
$gelen_veri = json_decode(file_get_contents("php://input"), true);

if (isset($gelen_veri['skor'])) {
    $yeni_skor = (int)$gelen_veri['skor'];
    $kullanici = $_SESSION['kullanici_adi'];
    $dosya_yolu = 'skorlar.json';

    // Skor dosyası yoksa boş bir dizi oluştur
    $skorlar = file_exists($dosya_yolu) ? json_decode(file_get_contents($dosya_yolu), true) : [];

    $kullanici_bulundu = false;

    // Kullanıcının daha önceden skoru var mı diye listeyi gez
    foreach ($skorlar as &$kayit) {
        if ($kayit['isim'] === $kullanici) {
            $kullanici_bulundu = true;
            // Eğer yeni skor eskisinden büyükse güncelle
            if ($yeni_skor > $kayit['skor']) {
                $kayit['skor'] = $yeni_skor;
            }
            break;
        }
    }

    // Kullanıcı listede hiç yoksa yeni kayıt olarak ekle
    if (!$kullanici_bulundu) {
        $skorlar[] = [
            "isim" => $kullanici,
            "skor" => $yeni_skor
        ];
    }

    // Skorları büyükten küçüğe doğru sırala (Liderlik Tablosu mantığı)
    usort($skorlar, function($a, $b) {
        return $b['skor'] - $a['skor'];
    });

    // Sadece en iyi 10 skoru tutmak istersen (Opsiyonel)
    // $skorlar = array_slice($skorlar, 0, 10);

    // Güncel listeyi JSON dosyasına kaydet
    file_put_contents($dosya_yolu, json_encode($skorlar, JSON_PRETTY_PRINT));

    // JavaScript'e "Başarılı" mesajı döndür
    echo json_encode(["durum" => "basarili"]);
}
?>