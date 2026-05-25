<nav class="navbar">
    <div class="logo">WEB TASARIM<span class="vurgu">ÜSSÜ</span></div>
    <div class="menu-linkleri">
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
        <a href="admin.php" style="color: #f1c40f; font-weight: bold;"><i class="fa-solid fa-shield-halved"></i> Yönetim</a>
    <?php endif; ?>
        <a href="anasayfa.php#projeler">Projeler</a>
        <a href="ziyaretci.php">Ziyaretçi Defteri</a>
        <a href="iletisim.php">İletişim</a>
        <a href="cikis.php" class="cikis-btn">Çıkış (<?php echo $_SESSION['kullanici_adi']; ?>)</a>
    </div>
</nav>

<div class="mini-oyun-widget" onclick="window.location.href='oyun.php'">
    <i class="fa-solid fa-gamepad"></i>
    <span>Oyna</span>
</div>