<?php $this->layout('layout::standard') ?>
<main>
    <div class="it-grid">
        <h2><?=$this->t('Uspešno ste prijavili oštećenog kolegu!')?></h2>

        <p><?=$this->t('Ako imate još kolega koje želite da prijavite, slobodno nastavite klikom na dugme ispod')?></p>

        <a href="<?=$this->localizeUrl('/obrazacOsteceni')?>"><?=$this->t('Prijavi sledećeg oštećenog')?></a>
    </div>
</main>