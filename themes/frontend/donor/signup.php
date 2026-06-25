<?php $this->layout('layout::standard') ?>
<main class="it-grid">

    <form method="post" action="/donor/register">
        <?=$this->formToken(); ?>

		    <label for="email">Email *</label>
		    <input type="email" name="email" id="email" size="40" maxlength="200" autocomplete="email" value="" required />

            <label for="email">Ime *</label>
            <input type="text" name="firstName" id="firstName" size="40" maxlength="200" value="" required />
            <label for="email">Prezime *</label>
            <input type="text" name="lastName" id="lastName" size="40" maxlength="200" value="" required />

	    <button type="submit">
		    <span class="it-m-text">Pošalji</span>
	    </button>
    </form>
</main>