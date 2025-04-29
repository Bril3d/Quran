<?php

// Set page title for layout
$pageTitle = 'الصفحة غير موجودة';
?>

<head>
  <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<div class="error-page">
  <div class="container text-center">
    <div class="error-content">
      <h1 class="display-1">404</h1>
      <h2>الصفحة غير موجودة</h2>
      <p>عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها.</p>
      <div class="quran-verse">
        <blockquote>
          <p>وَمَا كَانَ اللَّهُ لِيُضِيعَ إِيمَانَكُمْ ۚ إِنَّ اللَّهَ بِالنَّاسِ لَرَءُوفٌ رَحِيمٌ</p>
          <footer>سورة البقرة - آية 143</footer>
        </blockquote>
      </div>
      <a href="<?= baseUrl() ?>" class="btn btn-primary mt-4">العودة إلى الصفحة الرئيسية</a>
    </div>
  </div>
</div>