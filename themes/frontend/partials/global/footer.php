<footer id="footerMain">
    <div id="footerMessage">
        <p>Što više donatora, to više ljudi može dobiti pomoć! Pridruži se i budi deo mreže solidarnosti.</p>
        <a href="" class="donateButton">
            <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.2479 1.62712C10.9699 1.34905 10.6399 1.12846 10.2767 0.977961C9.9135 0.827462 9.52419 0.75 9.13103 0.75C8.73787 0.75 8.34856 0.827462 7.98535 0.977961C7.62213 1.12846 7.29212 1.34905 7.01418 1.62712L6.43735 2.20395L5.86053 1.62712C5.2991 1.0657 4.53765 0.750291 3.74368 0.750291C2.9497 0.750291 2.18825 1.0657 1.62683 1.62712C1.0654 2.18854 0.75 2.95 0.75 3.74397C0.75 4.53794 1.0654 5.29939 1.62683 5.86082L6.43735 10.6713L11.2479 5.86082C11.526 5.58288 11.7465 5.25287 11.897 4.88965C12.0475 4.52644 12.125 4.13713 12.125 3.74397C12.125 3.35081 12.0475 2.9615 11.897 2.59828C11.7465 2.23507 11.526 1.90506 11.2479 1.62712Z"
                      stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Doniraj
        </a>
    </div>
    <div id="partners">
        <h2>Delegati i partneri Mreže</h2>
        <div id="partnerLogos">
            <img src="<?=FRONT_ASSET_URL?>/images/partners/akc.png" alt="AKC">
            <img src="<?=FRONT_ASSET_URL?>/images/partners/suns.png" alt="Slobodni Univerzitet Krizni Centar">
            <img src="<?=FRONT_ASSET_URL?>/images/partners/suns2.png" alt="Novi Sad Slobodan Univerzitet">
            <img src="<?=FRONT_ASSET_URL?>/images/partners/stit.png" alt="Štit">
            <img src="<?=FRONT_ASSET_URL?>/images/partners/sunis.png" alt="Suniš">
            <img src="<?=FRONT_ASSET_URL?>/images/partners/its.png" alt="ITS">
        </div>
    </div>
    <div id="footerBottom">
        <div id="footerLeft">
            <div id="footerLeftContent">
                <h2>Kontakt</h2>
                <p>
                    Za pitanja, podršku ili uključivanje u rad Mreže, pišite nam putem emaila ili nam se pridružite na
                    <a href="" title="Discord">
                        Discordu
                    </a>.
                </p>
                <a href="mailto:info@mrezasolidarnosti.org" title="info@mrezasolidarnosti.org">info@mrezasolidarnosti.org</a>
            </div>
        </div>
        <div id="footerRight">
            <div id="footerRightContent">
                <div id="footerLogo">
                    <img src="<?=FRONT_ASSET_URL?>/images/footerLogo.svg" alt="Mreža solidarnosti">
                </div>
                <h2>
                    Ostanite povezani sa mrežom
                </h2>
                <p>Prijavite se za važne vesti, priče i načine da se uključite kada je podrška najpotrebnija.</p>
                <a href="" title="Prijavi se">Prijavi se</a>
            </div>
            <ul id="footerSocials">
                <?php if(isset($socialLinks) && $socialLinks):?>
                    <?php foreach ($socialLinks as $socialLink): ?>
                        <li>
                            <a class="socialLink" href="<?=$socialLink['url']?>" title="<?=$socialLink['platform']?>" target="_blank">
                                <?=$socialLink['icon']?>
                            </a>
                        </li>

                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <div id="footerBottomLast">
                <a href="/" title="Mreža solidarnosti">
                    <img src="<?=FRONT_ASSET_URL?>/images/logoBlue.svg" alt="Mreža solidarnosti">
                </a>
                <div class="copy">
                    <a href="" title="Politika privatnosti">Politika privatnosti</a>
                    <span>© 2026 Mreža solidarnosti. All rights reserved.</span>
                </div>
            </div>
        </div>
    </div>
</footer>