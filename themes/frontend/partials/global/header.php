<?php
    $localeLabels = ['sr' => 'RS', 'en' => 'EN'];
    $localeTitles = ['sr' => 'Srpski', 'en' => 'English'];
    $currentLoc   = $currentLocale ?? 'sr';
    $alternates   = $localeAlternates ?? [];
?>
<?php if(isset($isDonorLoggedIn) && $isDonorLoggedIn):?>
    <header id="mainHeader" class="loggedIn">
        <div id="headerLogo">
            <a href="<?=$this->localizeUrl('/')?>" title="Mreža solidarnosti">
                <picture>
                    <source srcset="<?=FRONT_ASSET_URL?>/images/logoWhiteMobile.svg" media="(max-width: 500px)">
                    <img src="<?=FRONT_ASSET_URL?>/images/logoWhite.svg" alt="Mreža solidarnosti">
                </picture>
            </a>
        </div>
        <nav>
            <ul>
                <li>
                    <a href="<?=$this->localizeUrl('/doniraj')?>" title="<?=$this->t('Doniraj')?>" class="<?=$slug === 'doniraj' ? 'active' : ''?>">
                        <svg class="donate" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M279.6 31C265.5 11.5 242.9 0 218.9 0 177.5 0 144 33.5 144 74.9l0 2.4c0 64.4 82 133.4 122.2 163.3 13 9.7 30.5 9.7 43.5 0 40.2-30 122.2-98.9 122.2-163.3l0-2.4c0-41.4-33.5-74.9-74.9-74.9-24 0-46.6 11.5-60.7 31L288 42.7 279.6 31zM109.3 341.5L66.7 384 32 384c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32l320.5 0c29 0 57.3-9.3 80.7-26.5l126.6-93.3c17.8-13.1 21.6-38.1 8.5-55.9s-38.1-21.6-55.9-8.5L392.6 416 280 416c-13.3 0-24-10.7-24-24s10.7-24 24-24l72 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-152.2 0c-33.9 0-66.5 13.5-90.5 37.5z"/></svg>
                        <span><?=$this->t('Doniraj')?></span>
                    </a>
                </li>
                <li>
                    <a href="<?=$this->localizeUrl('/instrukcije-za-uplatu')?>" title="<?=$this->t('Instrukcije za uplatu')?>" class="<?=$slug === 'instrukcije-za-uplatu' ? 'active' : ''?>">
                        <svg width="24" height="22" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.4211 17.4706H4.47368C3.55241 17.4706 2.66886 17.0987 2.01742 16.4368C1.36598 15.775 1 14.8772 1 13.9412V4.52941C1 3.59335 1.36598 2.69563 2.01742 2.03374C2.66886 1.37185 3.55241 1 4.47368 1H18.3684C19.2897 1 20.1732 1.37185 20.8247 2.03374C21.4761 2.69563 21.8421 3.59335 21.8421 4.52941V9.82353M1 6.88235H21.8421M16.0526 17.4706H23M19.5263 21L23 17.4706L19.5263 13.9412M5.63737 12.7647H5.64316M10.2632 12.7647H12.5789" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span><?=$this->t('Instrukcije za uplatu')?></span>
                    </a>
                </li>
                <li>
                    <a href="<?=$this->localizeUrl('/korisnicki-podaci')?>" title="<?=$this->t('Korisnički podaci')?>" class="<?=$slug === 'korisnicki-podaci' ? 'active' : ''?>">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.231 20.828C11.003 20.7183 10.803 20.5583 10.6461 20.3599C10.4891 20.1615 10.3793 19.9301 10.325 19.683C10.2611 19.4192 10.1358 19.1742 9.95929 18.968C9.7828 18.7618 9.56011 18.6001 9.30935 18.4963C9.05859 18.3924 8.78683 18.3491 8.51621 18.3701C8.24559 18.3911 7.98375 18.4757 7.752 18.617C6.209 19.557 4.442 17.791 5.382 16.247C5.5231 16.0153 5.60755 15.7537 5.62848 15.4832C5.64942 15.2128 5.60624 14.9412 5.50247 14.6906C5.3987 14.44 5.23726 14.2174 5.03127 14.0409C4.82529 13.8645 4.58056 13.7391 4.317 13.675C2.561 13.249 2.561 10.751 4.317 10.325C4.5808 10.2611 4.82578 10.1358 5.032 9.95929C5.23822 9.7828 5.39985 9.56011 5.50375 9.30935C5.60764 9.05859 5.65085 8.78683 5.62987 8.51621C5.60889 8.24559 5.5243 7.98375 5.383 7.752C4.443 6.209 6.209 4.442 7.753 5.382C8.753 5.99 10.049 5.452 10.325 4.317C10.751 2.561 13.249 2.561 13.675 4.317C13.7389 4.5808 13.8642 4.82578 14.0407 5.032C14.2172 5.23822 14.4399 5.39985 14.6907 5.50375C14.9414 5.60764 15.2132 5.65085 15.4838 5.62987C15.7544 5.60889 16.0162 5.5243 16.248 5.383C17.791 4.443 19.558 6.209 18.618 7.753C18.4769 7.98466 18.3924 8.24634 18.3715 8.51677C18.3506 8.78721 18.3938 9.05877 18.4975 9.30938C18.6013 9.55999 18.7627 9.78258 18.9687 9.95905C19.1747 10.1355 19.4194 10.2609 19.683 10.325C20.192 10.448 20.553 10.746 20.767 11.117" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14.8818 11.165C14.7443 10.69 14.4916 10.2563 14.1461 9.90244C13.8006 9.54863 13.3729 9.28569 12.9013 9.137C12.4296 8.98832 11.9285 8.95851 11.4425 9.05022C10.9566 9.14193 10.5008 9.35232 10.1158 9.66267C9.73072 9.97303 9.42834 10.3737 9.23553 10.8291C9.04272 11.2846 8.96544 11.7806 9.01057 12.273C9.0557 12.7655 9.22184 13.2392 9.49422 13.652C9.76659 14.0648 10.1368 14.4038 10.5718 14.639" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18.0001 22L21.3501 18.716C21.5556 18.5166 21.719 18.2782 21.8307 18.0146C21.9425 17.7511 22.0003 17.4678 22.0007 17.1815C22.0012 16.8953 21.9443 16.6118 21.8334 16.3479C21.7225 16.084 21.5599 15.845 21.3551 15.645C20.9373 15.2363 20.3763 15.007 19.7918 15.0059C19.2074 15.0048 18.6455 15.2319 18.2261 15.639L18.0021 15.859L17.7791 15.639C17.3613 15.2306 16.8006 15.0015 16.2163 15.0004C15.6321 14.9992 15.0705 15.2262 14.6511 15.633C14.4456 15.8323 14.2821 16.0707 14.1703 16.3342C14.0585 16.5977 14.0006 16.881 14 17.1672C13.9994 17.4535 14.0562 17.7369 14.167 18.0009C14.2778 18.2648 14.4404 18.5039 14.6451 18.704L18.0001 22Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span><?=$this->t('Korisnički podaci')?></span>
                    </a>
                </li>
                <li>
                    <a href="/donor/logout" title="<?=$this->t('Odjavi se')?>">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 14.5L12 16.75C12 17.3467 11.7893 17.919 11.4142 18.341C11.0391 18.7629 10.5304 19 10 19L3 19C2.46957 19 1.96086 18.7629 1.58579 18.341C1.21071 17.919 1 17.3467 1 16.75L1 3.25C1 2.65326 1.21072 2.08097 1.58579 1.65901C1.96086 1.23705 2.46957 1 3 1L10 1C10.5304 1 11.0391 1.23706 11.4142 1.65901C11.7893 2.08097 12 2.65326 12 3.25L12 5.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7 10L19 10M16 6.625L19 10L16 13.375" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span><?=$this->t('Odjavi se')?></span>
                    </a>
                </li>
            </ul>
        </nav>
        <div id="headerContent">
            <div id="closeNavigation">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.41421 19.799C0.633165 19.0179 0.633165 17.7516 1.41421 16.9706L16.9706 1.4142C17.7516 0.633154 19.0179 0.633155 19.799 1.4142C20.58 2.19525 20.58 3.46158 19.799 4.24263L4.24264 19.799C3.46159 20.58 2.19526 20.58 1.41421 19.799Z"
                          fill="#FE5101"/>
                    <path d="M19.799 19.9706C19.0179 20.7516 17.7516 20.7516 16.9706 19.9706L1.4142 4.4142C0.633154 3.63315 0.633155 2.36682 1.4142 1.58578C2.19525 0.804728 3.46158 0.804728 4.24263 1.58578L19.799 17.1421C20.58 17.9232 20.58 19.1895 19.799 19.9706Z"
                          fill="#FE5101"/>
                </svg>
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="<?=$this->localizeUrl('/doniraj')?>" title="<?=$this->t('Doniraj')?>" class="<?=$slug === 'doniraj' ? 'active' : ''?>">
                            <span><?=$this->t('Doniraj')?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?=$this->localizeUrl('/instrukcije-za-uplatu')?>" title="<?=$this->t('Instrukcije za uplatu')?>" class="<?=$slug === 'instrukcije-za-uplatu' ? 'active' : ''?>">
                            <span><?=$this->t('Instrukcije za uplatu')?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?=$this->localizeUrl('/korisnicki-podaci')?>" title="<?=$this->t('Korisnički podaci')?>" class="<?=$slug === 'korisnicki-podaci' ? 'active' : ''?>">
                            <span><?=$this->t('Korisnički podaci')?></span>
                        </a>
                    </li>
                    <li>
                        <a href="/donor/logout" title="<?=$this->t('Odjavi se')?>">
                            <span><?=$this->t('Odjavi se')?></span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div id="headerActions">
                <div class="languageSwitcher">
                    <span class="currentLanguage">
                        <?=$localeLabels[$currentLoc] ?? strtoupper($currentLoc)?>
                    </span>
                    <ul class="languageOptions">
                        <?php foreach ($alternates as $loc => $locUrl): ?>
                            <li><a class="<?=$loc === $currentLoc ? 'active' : ''?>" href="<?=htmlentities($locUrl)?>?setLocale=<?=htmlentities($loc)?>" title="<?=htmlentities($localeTitles[$loc] ?? $loc)?>"><?=$localeLabels[$loc] ?? strtoupper($loc)?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div id="headerMobileActions">
            <div id="hamburger">
                <svg width="26" height="20" viewBox="0 0 26 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 2C0 0.895431 0.895431 0 2 0H24C25.1046 0 26 0.895431 26 2C26 3.10457 25.1046 4 24 4H2C0.89543 4 0 3.10457 0 2Z"
                          fill="#262185"/>
                    <path d="M0 10C0 8.89543 0.895431 8 2 8H24C25.1046 8 26 8.89543 26 10C26 11.1046 25.1046 12 24 12H2C0.89543 12 0 11.1046 0 10Z"
                          fill="#262185"/>
                    <path d="M0 18C0 16.8954 0.895431 16 2 16H24C25.1046 16 26 16.8954 26 18C26 19.1046 25.1046 20 24 20H2C0.89543 20 0 19.1046 0 18Z"
                          fill="#262185"/>
                </svg>
            </div>
        </div>
    </header>
<?php else:?>
    <header id="mainHeader">
        <div id="headerLogo">
            <a href="<?=$this->localizeUrl('/')?>" title="Mreža solidarnosti">
                <picture>
                    <source srcset="<?=FRONT_ASSET_URL?>/images/logoBlueMobile.svg" media="(max-width: 500px)">
                    <img src="<?=FRONT_ASSET_URL?>/images/logoBlue.svg" alt="Mreža solidarnosti">
                </picture>
            </a>
        </div>
        <div id="headerContent">
            <div id="closeNavigation">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.41421 19.799C0.633165 19.0179 0.633165 17.7516 1.41421 16.9706L16.9706 1.4142C17.7516 0.633154 19.0179 0.633155 19.799 1.4142C20.58 2.19525 20.58 3.46158 19.799 4.24263L4.24264 19.799C3.46159 20.58 2.19526 20.58 1.41421 19.799Z"
                          fill="#FE5101"/>
                    <path d="M19.799 19.9706C19.0179 20.7516 17.7516 20.7516 16.9706 19.9706L1.4142 4.4142C0.633154 3.63315 0.633155 2.36682 1.4142 1.58578C2.19525 0.804728 3.46158 0.804728 4.24263 1.58578L19.799 17.1421C20.58 17.9232 20.58 19.1895 19.799 19.9706Z"
                          fill="#FE5101"/>
                </svg>
            </div>
            <nav>
                <ul>
                    <?php if(isset($mainNavigation) && $mainNavigation):?>
                        <?php foreach($mainNavigation->getItemsFormatted() as $item):?>
                            <li><a href="<?=htmlentities($this->absUrl($item['url'] ?? ''))?>" title="<?=htmlentities($item['label'] ?? '')?>"><?=htmlentities($item['label'] ?? '')?></a></li>
                        <?php endforeach;?>
                    <?php endif; ?>
                </ul>
            </nav>
            <div id="headerActions">
                <?php if (!empty($isLoggedIn)): ?>
                    <a href="/donor/logout" id="login"><?=$this->t('Izloguj se')?></a>
                    <?php $donateUrl = '/doniraj'; ?>
                <?php else: ?>
                    <a href="<?=$this->localizeUrl('/logovanje')?>" id="login"><?=$this->t('Uloguj se')?></a>
                    <?php $donateUrl = '/registracija-donatora'; ?>
                <?php endif; ?>
                <a href="<?=$this->localizeUrl($donateUrl)?>" id="donate" class="donateButton">
                    <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.2479 1.62712C10.9699 1.34905 10.6399 1.12846 10.2767 0.977961C9.9135 0.827462 9.52419 0.75 9.13103 0.75C8.73787 0.75 8.34856 0.827462 7.98535 0.977961C7.62213 1.12846 7.29212 1.34905 7.01418 1.62712L6.43735 2.20395L5.86053 1.62712C5.2991 1.0657 4.53765 0.750291 3.74368 0.750291C2.9497 0.750291 2.18825 1.0657 1.62683 1.62712C1.0654 2.18854 0.75 2.95 0.75 3.74397C0.75 4.53794 1.0654 5.29939 1.62683 5.86082L6.43735 10.6713L11.2479 5.86082C11.526 5.58288 11.7465 5.25287 11.897 4.88965C12.0475 4.52644 12.125 4.13713 12.125 3.74397C12.125 3.35081 12.0475 2.9615 11.897 2.59828C11.7465 2.23507 11.526 1.90506 11.2479 1.62712Z"
                              stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <?=$this->t('Doniraj')?>
                </a>
                <div class="languageSwitcher">
                        <span class="currentLanguage">
                            <?=$localeLabels[$currentLoc] ?? strtoupper($currentLoc)?>
                        </span>
                    <ul class="languageOptions">
                        <?php foreach ($alternates as $loc => $locUrl): ?>
                            <li><a class="<?=$loc === $currentLoc ? 'active' : ''?>" href="<?=htmlentities($locUrl)?>?setLocale=<?=htmlentities($loc)?>" title="<?=htmlentities($localeTitles[$loc] ?? $loc)?>"><?=$localeLabels[$loc] ?? strtoupper($loc)?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div id="headerMobileActions">
            <a href="<?=$this->localizeUrl($donateUrl)?>" id="donateMobile" class="btnIcon">
                <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.2479 1.62712C10.9699 1.34905 10.6399 1.12846 10.2767 0.977961C9.9135 0.827462 9.52419 0.75 9.13103 0.75C8.73787 0.75 8.34856 0.827462 7.98535 0.977961C7.62213 1.12846 7.29212 1.34905 7.01418 1.62712L6.43735 2.20395L5.86053 1.62712C5.2991 1.0657 4.53765 0.750291 3.74368 0.750291C2.9497 0.750291 2.18825 1.0657 1.62683 1.62712C1.0654 2.18854 0.75 2.95 0.75 3.74397C0.75 4.53794 1.0654 5.29939 1.62683 5.86082L6.43735 10.6713L11.2479 5.86082C11.526 5.58288 11.7465 5.25287 11.897 4.88965C12.0475 4.52644 12.125 4.13713 12.125 3.74397C12.125 3.35081 12.0475 2.9615 11.897 2.59828C11.7465 2.23507 11.526 1.90506 11.2479 1.62712Z"
                          stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <a href="<?=$this->localizeUrl('/logovanje')?>" title="<?=$this->t('Uloguj se')?>" id="loginMobile" class="btnIcon">
                <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.2479 1.62712C10.9699 1.34905 10.6399 1.12846 10.2767 0.977961C9.9135 0.827462 9.52419 0.75 9.13103 0.75C8.73787 0.75 8.34856 0.827462 7.98535 0.977961C7.62213 1.12846 7.29212 1.34905 7.01418 1.62712L6.43735 2.20395L5.86053 1.62712C5.2991 1.0657 4.53765 0.750291 3.74368 0.750291C2.9497 0.750291 2.18825 1.0657 1.62683 1.62712C1.0654 2.18854 0.75 2.95 0.75 3.74397C0.75 4.53794 1.0654 5.29939 1.62683 5.86082L6.43735 10.6713L11.2479 5.86082C11.526 5.58288 11.7465 5.25287 11.897 4.88965C12.0475 4.52644 12.125 4.13713 12.125 3.74397C12.125 3.35081 12.0475 2.9615 11.897 2.59828C11.7465 2.23507 11.526 1.90506 11.2479 1.62712Z"
                          stroke="#262185" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15.629 12.2367L15.6948 7.907C15.7038 7.45299 15.5372 7.01302 15.2297 6.6788C14.9222 6.34458 14.4975 6.1419 14.0442 6.11302L12.0617 6.08288L12.1127 2.72887C12.1164 2.48627 12.0235 2.25213 11.8545 2.07798C11.6856 1.90383 11.4543 1.80392 11.2116 1.80023C10.969 1.79654 10.7348 1.88937 10.5606 2.05831C10.3864 2.22725 10.2864 2.45845 10.2827 2.70105L10.2039 7.88452L9.50978 6.98952C9.36421 6.79613 9.14957 6.66638 8.91064 6.62733C8.6717 6.58829 8.42692 6.64296 8.22733 6.77995C8.02773 6.91694 7.88875 7.12568 7.83935 7.36262C7.78995 7.59957 7.83396 7.84643 7.9622 8.05173L10.444 12.1579"
                          fill="#F3F4F6"/>
                    <path d="M15.629 12.2367L15.6948 7.907C15.7038 7.45299 15.5372 7.01302 15.2297 6.6788C14.9222 6.34458 14.4975 6.1419 14.0442 6.11302L12.0617 6.08288L12.1127 2.72887C12.1164 2.48627 12.0235 2.25213 11.8545 2.07798C11.6856 1.90383 11.4543 1.80392 11.2116 1.80023C10.969 1.79654 10.7348 1.88937 10.5606 2.05831C10.3864 2.22725 10.2864 2.45845 10.2827 2.70105L10.2039 7.88452L9.50978 6.98952C9.36421 6.79613 9.14957 6.66638 8.91064 6.62733C8.6717 6.58829 8.42692 6.64296 8.22733 6.77995C8.02773 6.91694 7.88875 7.12568 7.83935 7.36262C7.78995 7.59957 7.83396 7.84643 7.9622 8.05173L10.444 12.1579"
                          stroke="#262185" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div id="hamburger">
                <svg width="26" height="20" viewBox="0 0 26 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 2C0 0.895431 0.895431 0 2 0H24C25.1046 0 26 0.895431 26 2C26 3.10457 25.1046 4 24 4H2C0.89543 4 0 3.10457 0 2Z"
                          fill="#262185"/>
                    <path d="M0 10C0 8.89543 0.895431 8 2 8H24C25.1046 8 26 8.89543 26 10C26 11.1046 25.1046 12 24 12H2C0.89543 12 0 11.1046 0 10Z"
                          fill="#262185"/>
                    <path d="M0 18C0 16.8954 0.895431 16 2 16H24C25.1046 16 26 16.8954 26 18C26 19.1046 25.1046 20 24 20H2C0.89543 20 0 19.1046 0 18Z"
                          fill="#262185"/>
                </svg>
            </div>
        </div>
    </header>
<?php endif;?>
