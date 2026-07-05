<?php $this->layout('layout::standard') ?>
<main class="it-grid">
	<h2><?=$this->t('Obrazac za delegate u obustavi')?></h2>
	<p><?=$this->t('Molimo Vas da, kao delegat Vaše škole, u ime svog kolektiva popunite ovaj formular kako bismo Vas mogli kontaktirati u vezi sa finansijskom podrškom za prosvetne radnike u obustavi, uključujući i nastavno i nenastavno osoblje.')?></p>

	<?=$this->printError( $data['errors'] ?? array(), 'form' ); ?>

	<form method="post" action="obrazacDelegati" id="it-delegati-form" class="it-form" aria-label="Delegati forma" data-type="delegati">
        <?=$this->formToken(); ?>
		<div class="it-form-response" aria-hidden="true"></div>
		<div class="it-row-section it-col-num--2 it-responsive--predefined">
			<div class="it-row">
				<div class="it-column">
					<div class="it-form-field">
						<label for="email"><?=$this->t('Email *')?></label>
						<input type="email" name="email" id="email" size="40" maxlength="60" autocomplete="email" aria-required="true" aria-invalid="false" value="" required />
					</div>
				</div>
				<div class="it-column">
					<div class="it-form-field">
						<label for="full-name"><?=$this->t('Ime i Prezime *')?></label>
						<input type="text" name="name" id="full-name" aria-required="true" value="" required />
					</div>
				</div>
			</div>
		</div>
		<div class="it-row-section it-col-num--2 it-responsive--predefined">
			<div class="it-row">
				<div class="it-column">
					<div class="it-form-field">
						<label for="phone"><?=$this->t('Broj telefona *')?></label>
						<input type="tel" name="phone" id="phone" maxlength="20" aria-required="true" aria-invalid="false" value="" required="">
					</div>
				</div>
				<div class="it-column">
					<div class="it-form-field">
						<label for="school-type"><?=$this->t('Tip obrazovne ustanove *')?></label>
						<select name="schoolType" id="school-type" aria-required="true" required>
							<?php
							if ( isset( $schoolTypes ) && is_array( $schoolTypes ) ) {
								foreach ( $schoolTypes as $schoolType ) {
									?>
									<option value="<?php echo $schoolType; ?>"><?php echo $schoolType; ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="it-row-section it-col-num--2 it-responsive--predefined">
			<div class="it-row">
				<div class="it-column">
					<div class="it-form-field">
						<label for="city"><?=$this->t('Mesto škole *')?></label>
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
						<label><?=$this->t('Naziv škole *')?></label>
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
						<label for="suspended-number"><?=$this->t('Broj u obustavi *')?></label>
						<input type="number" name="countBlocking" id="suspended-number" min="0" max="500" aria-required="true" aria-describedby="suspended-number-desc" value="" placeholder="0" required />
						<small id="suspended-number-desc"><?=$this->t('Broj nastavnog i nenastavnog osoblja Vaše škole, koji su u obustavi')?></small>
					</div>
				</div>
				<div class="it-column">
					<div class="it-form-field">
						<label for="total-number"><?=$this->t('Ukupan broj *')?></label>
						<input type="number" name="count" id="total-number" min="1" max="500" aria-required="true" aria-describedby="total-number-desc" value="" placeholder="1" required />
						<small id="total-number-desc"><?=$this->t('Ukupan broj nastavnog i nenastavnog osoblja u Vašoj školi (dovoljano je uneti približan broj)')?></small>
					</div>
				</div>
			</div>
		</div>
		<div class="it-form-field">
			<label for="message"><?=$this->t('Komentar (opciono)')?></label>
			<textarea name="comment" id="message" cols="40" rows="6" maxlength="600" aria-describedby="message-desc"></textarea>
			<small id="message-desc"><?=$this->t('Unesi dodatni komentar ili sugestiju')?></small>
		</div>
		<button type="submit" class="it-form-button it-button it-size--normal it-layout--filled it-m">
			<span class="it-m-text"><?=$this->t('Pošalji')?></span>
		</button>
    </form>
</main>