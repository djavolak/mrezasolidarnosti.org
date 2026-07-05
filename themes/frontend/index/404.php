<?php $this->layout('layout::standard') ?>
<div id="notFound">
    <h1><?=$this->t('Stranica nije pronađena')?></h1>
    <p><?=$this->t('Probajte')?> <a href="<?=$this->localizeUrl('/')?>" title="Home"><?=$this->t('iz početka')?></a>.</p>
</div>
