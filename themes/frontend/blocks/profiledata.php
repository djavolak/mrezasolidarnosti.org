<?php if(isset($block) && $isDonorLoggedIn && isset($block['donor']) && $block['donor']): ?>
    <div id="profileData">
        <h1><?=htmlentities($block['title'] ?? '')?></h1>
        <div id="profileDataInfo">
            <div id="profileDataForm">
                <form id="formProfileData" action="/donor/updateProfileData" method="POST">
                    <?=$this->formToken(); ?>
                    <div class="messagesContainer">

                    </div>
                    <div class="inputContainer">
                        <label for="name">Ime</label>
                        <input type="text" id="name" name="firstName" disabled value="<?=$block['donor']->firstName?>">
                    </div>
                    <div class="inputContainer">
                        <label for="lastname">Prezime</label>
                        <input type="text" id="lastname" name="lastName" disabled value="<?=$block['donor']->lastName?>">
                    </div>
                    <div class="inputContainer">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" disabled value="<?=$block['donor']->email?>">
                    </div>
                    <p></p>
                    <button id="formSubmit" type="submit" class="buttonPrimary centered">Izmeni</button>
                </form>
            </div>
            <div id="profileDataStats">
                <div class="left">
                    <h2>Ukupno donirano</h2>
                    <div class="amount big"><?=$block['totalDonated']?> RSD</div>
                    <div class="amount small"><?=$block['totalDonatedEUR']?> EUR</div>
                </div>
                <div class="right">
                    <h2>Ukupno transakcija</h2>
                    <div class="amount big"><?=$block['totalTransactions']?></div>
                </div>
                <a class="instructions buttonPrimary centered" href="<?=$this->localizeUrl('/instrukcije-za-uplatu')?>" title="Kreiraj instrukcije za uplatu">
                    <svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_759_2558)">
                            <path d="M5.41118 10.0395H2.09539C1.65569 10.0395 1.234 9.86478 0.923086 9.55387C0.612171 9.24295 0.4375 8.82126 0.4375 8.38156V3.96051C0.4375 3.52081 0.612171 3.09911 0.923086 2.7882C1.234 2.47728 1.65569 2.30261 2.09539 2.30261H8.72697C9.16667 2.30261 9.58837 2.47728 9.89928 2.7882C10.2102 3.09911 10.3849 3.52081 10.3849 3.96051V6.44735M0.4375 5.06577H10.3849M7.62171 10.0395H10.9375M9.2796 11.6973L10.9375 10.0395L9.2796 8.38156M2.65079 7.82893H2.65355M4.85855 7.82893H5.96382" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_759_2558">
                                <rect width="11.375" height="14" fill="white"/>
                            </clipPath>
                        </defs>
                    </svg>
                    Kreiraj instrukcije za uplatu
                </a>
            </div>
            <div id="profileDataLinks">
                <p>Pregled svih dosadašnjih kao i nove instrukcija možeš videti na stranici <a href="/instrukcije-za-uplatu" title="Instrukcije za uplatu">Instrukcije za uplatu</a>.</p>
            </div>
        </div>
    </div>
    <script src="<?=FRONT_ASSET_URL?>/js/profileData.js"></script>
<?php endif; ?>
