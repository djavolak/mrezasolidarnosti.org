<!doctype html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$title?></title>

	<meta name="title" content="Mreža solidarnosti - IT Srbija">
	<meta name="description" content="Mreža solidarnosti je inicijativa IT Srbije za direktnu finansijsku podršku nastavnicima i vannastavnom osoblju čija je plata umanjena zbog obustave rada. Pridruži se, doniraj i podrži one koji se bore za bolje obrazovanje!">
	<meta name="keywords" content="Mreža solidarnosti, IT Srbija, prosveta, donacije, podrška nastavnicima, solidarnost, pomoć nastavnicima, obustava rada, direktna podrška">
	<!-- Open Graph -->
	<meta property="og:type" content="website">
	<meta property="og:url" content="https://mrezasolidarnosti.org/">
	<meta property="og:title" content="Mreža solidarnosti - IT Srbija">
	<meta property="og:description" content="Direktna podrška nastavnicima čija je plata umanjena zbog obustave rada. Uključi se, doniraj, podrži solidarnost u prosveti!">
	<meta property="og:image" content="/assets/img/social.webp">

	<meta property="twitter:card" content="summary_large_image">
	<meta property="twitter:url" content="https://mrezasolidarnosti.org/">
	<meta property="twitter:title" content="Mreža solidarnosti - IT Srbija">
	<meta property="twitter:description" content="Direktna finansijska pomoć nastavnicima u obustavi rada. Pridruži se IT Srbiji u inicijativi solidarnosti!">
	<meta property="twitter:image" content="/assets/img/social.webp">

	<link rel="canonical" href="https://mrezasolidarnosti.org/">

    <link rel="icon" type="image/svg+xml" href="/assets/img/serbian-flag.svg">
    <link rel="apple-touch-icon" href="/assets/img/serbian-flag.svg">
    <meta name="description" content="Mreža solidarnosti je inicijativa IT Srbije za direktnu finansijsku podršku nastavnicima i vannastavnom osoblju čija je plata umanjena zbog obustave rada. Pridruži se, doniraj i podrži one koji se bore za bolje obrazovanje!">
    <meta name="keywords" content="Mreža solidarnosti, IT Srbija, prosveta, donacije, podrška nastavnicima, solidarnost, pomoć nastavnicima, obustava rada, direktna podrška">
    <!-- Open Graph / Facebook --><meta property="og:type" content="website">
    <meta property="og:url" content="https://mrezasolidarnosti.org/">
    <meta property="og:title" content="Mreža solidarnosti - IT Srbija">
    <meta property="og:description" content="Direktna podrška nastavnicima čija je plata umanjena zbog obustave rada. Uključi se, doniraj, podrži solidarnost u prosveti!">
    <meta property="og:image" content="/social.webp"><!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://mrezasolidarnosti.org/">
    <meta property="twitter:title" content="Mreža solidarnosti - IT Srbija">
    <meta property="twitter:description" content="Direktna finansijska pomoć nastavnicima u obustavi rada. Pridruži se IT Srbiji u inicijativi solidarnosti!">
    <meta property="twitter:image" content="/assets/img/social.webp">

    <script defer src="https://cloud.umami.is/script.js" data-website-id="775ac248-d1eb-4779-9e22-1ae08ede6310"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <!-- Tabler Icons -->

<!--	<link rel="icon" href="/assets/img/favicon.ico" sizes="any">-->
<!--	<link rel="apple-touch-icon" href="/assets/img/favicon.png">-->

    <link rel="stylesheet" href="<?=FRONT_ASSET_URL?>/css/style.css?v=0.0.1">
</head>
<body>
<?=$this->section('header', $this->fetch('partialsGlobal::header'))?>

<main <?=$isHome ? 'class="home"' : ''?>>
    <?=$this->section('content')?>
</main>

<?=$this->section('footer', $this->fetch('partialsGlobal::footer'))?>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script type="module" src="<?=FRONT_ASSET_URL?>/js/global.js?v=0.0.1"></script>
<?php if (isset($jsPath) && $jsPath != ""): ?>
    <script src="<?=$jsPath?>" type="module"></script>
<?php endif; ?>
</body>
</html>