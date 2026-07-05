<?php $this->layout('layout::standard') ?>
<div id="notFound">
    <h1><?=$this->t('Token za registraciju je istekao ili nije validan.')?></h1>
    <p><?=$this->t('Probajte')?> <a href="<?=$this->localizeUrl('/registracija-donatora')?>" title="<?=$this->t('Registruj se')?>"><?=$this->t('iz početka')?></a>.</p>
</div>
