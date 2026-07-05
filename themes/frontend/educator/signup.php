<?php $this->layout('layout::standard') ?>
<main>
	<div class="it-grid">
		<h2><?=$this->t('Prijavi oštećenog')?></h2>
		<p><strong><?=$this->t('Samo za drugi deo februarske plate')?></strong></p>
		<p><?=$this->t('Delegati treba da popune podatke za svakog pojedinačno kolegu koji se prijavljuje za program finansijske pomoći „Mreža solidarnosti“.')?></p>
		<p><strong><?=$this->t('Jedan formular = jedna prijava za jednog kolegu.')?></strong></p>
		<p><?=$this->t('Molimo vas da podatke unosite pažljivo i tačno – to je ključ za pravičnu, efikasnu i transparentnu raspodelu pomoći. Posebnu pažnju obratite na tačnost broja računa i iznosa, jer greške mogu usporiti isplatu.')?></p>
		<p>📌 <?=$this->t('Za dodatnu proveru ispravnosti unetog računa možete koristiti ovaj alat pre nego što podatke unesete u formu:')?> <a href="https://www.cekos.rs/kontrolni-broj-modul-97" target="_blank"><?=$this->t('Proveri broj računa')?></a></p>

		<?=$this->printError( $data['errors'] ?? array(), 'form' ); ?>
		<form method="post" action="/obrazacOsteceni" id="it-osteceni-form" class="it-form" aria-label="Osteceni forma" data-type="osteceni">
			<?=$this->formToken(); ?>
			<div class="it-form-response" aria-hidden="true"></div>
			<div class="it-form-field">
				<label for="full-name"><?=$this->t('Ime i Prezime')?> *</label>
				<input type="text" name="name" id="full-name" aria-required="true" value="<?php if (isset($data['data']['name'])) { echo $data['data']['name']; } ?>" required />
			</div>
			<div class="it-row-section it-col-num--2 it-responsive--predefined">
				<div class="it-row">
					<div class="it-column">
						<div class="it-form-field">
							<label for="city"><?=$this->t('Mesto škole')?> *</label>
							<select name="city" id="city" class="it-school-city" aria-required="true" required>
								<?php
								if ( isset( $schoolsMap ) && is_array( $schoolsMap ) ) {
									$cities = array_keys( $schoolsMap );

									foreach ( $cities as $city ) {
										?>
										<option value="<?php echo $city; ?>"><?php echo $city; ?></option>
										<?php
									}
								}
								?>
							</select>
						</div>
					</div>
					<div class="it-column">
						<div class="it-form-field">
							<label><?=$this->t('Naziv škole')?> *</label>
							<input type="hidden" name="schoolName" id="school-name" class="it-school-value" value="" aria-required="true" required />
							<?php
							if ( isset( $schoolsMap ) && is_array( $schoolsMap ) ) {
								foreach ( $schoolsMap as $city => $schools ) {
									?>
									<select class="it-school-name" data-city="<?php echo $city; ?>">
										<option value=""><?=$this->t('Izaberite školu')?></option>
										<?php
										foreach ( $schools as $school ) {
											?>
											<option value="<?php echo $school; ?>"><?php echo $school; ?></option>
											<?php
										}
										?>
									</select>
									<?php
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="it-row-section it-col-num--2 it-responsive--predefined">
				<div class="it-row">
					<div class="it-column">
						<div class="it-form-field">
							<label for="bank-account"><?=$this->t('Broj žiro računa')?> *</label>
							<input type="text" name="accountNumber" id="bank-account" aria-required="true" aria-describedby="bank-account-desc" value="" required />
							<small id="bank-account-desc"><?=$this->t('Broj žiro računa kolege kojeg prijavljujete (Molimo Vas da ovaj podatak unesete s najvećom pažnjom)')?></small>
						</div>
					</div>
					<div class="it-column">
						<div class="it-form-field">
							<label for="amount"><?=$this->t('Tačan iznos')?> *</label>
							<input type="number" name="amount" id="amount" aria-required="true" aria-describedby="amount-desc" value="" required />
							<small id="amount-desc"><?=$this->t('Tačan iznos umanjenog dela zarade (Molimo Vas da za svaku osobu pojedinačno unesete tačan iznos razlike, odnosno deo zarade koji je umanjen)')?></small>
						</div>
					</div>
				</div>
			</div>
			<button type="submit" class="it-form-button it-button it-size--normal it-layout--filled it-m">
				<span class="it-m-text"><?=$this->t('Pošalji')?></span>
			</button>
		</form>
	</div>
	<br /><br />
	<div class="it-section">
		<div class="it-grid">
			<div class="it-row-section it-col-num--2 it-responsive--predefined">
				<div class="it-row">
					<div class="it-column">
						<h2 style="margin-top: 0;"><?=$this->t('Slanje isplatnih listića')?></h2>
						<p><?=$this->t('Nakon što popunite formulare za svakog kolegu pojedinačno, u jednom mejlu nam pošaljite sve platne listiće prijavljenih kolega iz vaše škole, uz jasnu napomenu da se prijava odnosi isključivo na drugi deo februarske plate.')?></p>
						<p style="margin-bottom: 0;">📩 <?=$this->t('Dokumentaciju šaljete na sledeći način:')?></p>
						<small><?=$this->t('Primalac:')?> listici@mrezasolidarnosti.org</small>
						<br />
						<small><?=$this->t('Naslov mejla (subject): Platni listici < puno ime škole >, 2. deo februar')?></small>
					</div>
					<div class="it-column">
						<img src="/assets/img/osteceni-email-preview.png" alt="<?=$this->t('Mreža Solidarnosti - IT Srbija')?>" />
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="it-section it--blue">
		<div class="it-grid">
			<div class="it-row-section">
				<div class="it-row">
					<div class="it-column">
						<h2><?=$this->t('Dodatne napomene')?></h2>
						<p style="margin-bottom: 0; max-width: 820px"><?=$this->t('Apelujemo na sve članove kolektiva da iskažu solidarnost i odgovornost. U ovoj prvoj fazi, prioritet je da pomoć stigne do kolega koji su najviše pogođeni obustavom.')?></p>
						<p><?=$this->t('Prioritet imaju kolege koje su:')?></p>
						<ul style="padding-left: 20px;">
							<li><?=$this->t('finansijski najugroženije')?></li>
							<li><?=$this->t('pod najvećim pritiskom')?></li>
							<li><?=$this->t('na ivici odustajanja zbog nedostatka podrške')?></li>
							<li><?=$this->t('u izrazitoj manjini, jer je mali procenat nastavnika u obustavi u odnosu na ceo kolektiv')?></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>