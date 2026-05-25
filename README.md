# 🚀 Web Tasarım Üssü (Kişisel Dijital Portfolyo ve İçerik Yönetim Sistemi)

**Geliştirici:** Mehmet Emin Ulutaş  
**Canlı Yayın Linki (Deploy):** [https://gweinbleid.infinityfreeapp.com]  

## 📌 1. Projenin Amacı
Bu projenin temel amacı; sunucu taraflı (back-end) bir programlama dili olan **PHP 8** ile modern ön yüz (front-end) teknolojilerini harmanlayarak dinamik, güvenli, modüler ve kullanıcı etkileşimli nesnel bir web uygulaması geliştirmektir. Projede, statik veri sunumunun ötesine geçilerek; oturum yönetimi (session) ile rol bazlı sayfa yetkilendirmesi, harici REST API servisleri aracılığıyla asenkron canlı veri çekimi, form verilerinin manipülasyona karşı korunması ve veritabanı alternatifi olarak dosya tabanlı JSON manipülasyonu gibi modern web mühendisliği standartlarının uygulanması hedeflenmiştir.

## 🛠 2. Kullanılan Teknolojiler
* **Back-end:** PHP 8
* **Front-end:** HTML5, CSS3, Vanilla JavaScript (ES6+)
* **Veri Saklama (Database):** JSON (Dosya Tabanlı CRUD İşlemleri)
* **Veri İletişimi:** Fetch API (Asenkron POST/GET)
* **Dış API'ler:** Open-Meteo REST API, ExchangeRate REST API
* **Sunucu (Deployment):** InfinityFree (Linux / Apache Cloud)

## 🏗 3. Mimari Yapı ve İçerik
Web Tasarım Üssü, RBAC (Rol Bazlı Erişim Kontrolü) prensiplerine göre mimarisi kurgulanmış çok sayfalı bir web uygulamasıdır. 
* **Modüler Tasarım:** `include` yapısıyla menü bileşenleri tek merkezden yönetilmiş ve kod tekrarı (DRY) önlenmiştir.
* **Güvenlik Duvarı:** Başarıyla giriş yapan kullanıcılar, `session` bazlı güvenlik duvarıyla korunan ana üsse yönlendirilir. Giriş yapmamış kişilerin sayfalara erişimi sunucu tarafında engellenmiştir.
* **Dinamik İçerik:** API'ler üzerinden anlık çekilen hava durumu ve piyasa kurları, PHP döngüleriyle üretilen 3D müze galerisi ve interaktif ziyaretçi defteri modülleri bulunmaktadır.
* **Oyunlaştırma:** Entegre edilmiş JavaScript tabanlı "Neon Uzay" mini-oyunu üzerinden asenkron Fetch API entegrasyonuyla çalışan gerçek zamanlı bir Liderlik Tablosu mekanizması eklenmiştir.

## ⚙️ 4. Teknik Gereksinimlerin Karşılanma Detayları
* **Döngüler (Loops):** Proje genelinde dinamik listelemede `foreach` döngüleri kullanılmıştır. 
* **Koşul Yapıları (If / Else):** Kimlik doğrulama, form validasyonu ve rol atamaları (Admin/Üye) tamamen koşul blokları üzerine inşa edilmiştir.
* **Veri Saklama:** Kullanıcı hesapları (`kullanicilar.json`), yorumlar ve skorlar üzerinde `file_get_contents` ve `file_put_contents` fonksiyonları ile başarılı okuma, yazma ve sıralama işlemleri yapılmıştır.

## 💎 5. Öne Çıkan Gelişmiş Özellikler
1. **Rol Bazlı İçerik Silme:** Giriş yapan kullanıcı "admin" rolüne sahipse, sistem dinamik olarak yönetim panelini aktif eder. Admin, seçtiği ziyaretçi yorumunu JSON veritabanından kalıcı olarak silebilir.
2. **Form Güvenliği (XSS Koruması):** Olası Cross-Site Scripting (XSS) saldırılarını engellemek adına formdan gelen tüm girdiler `htmlspecialchars()` filtresinden geçirilmektedir.
3. **Asenkron Skor Entegrasyonu (AJAX):** Mini-oyunda elde edilen skorlar, sayfa yenilenmeden JS Fetch API ile arka planda sunucuya fırlatılmakta ve liderlik tablosu otomatik hesaplanmaktadır.
