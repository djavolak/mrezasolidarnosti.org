<?php $this->layout('emailTheme::email') ?>
<p style="max-width:600px;padding:20px 20px 0 20px;margin:15px auto;color:#505050;">
Zdravo <?=$data['name']?>,<br><br>
Kako bismo potvrdili da je ova email adresa ispravna i da pripada tebi, zamolićemo te da klikneš na dugme ispod:<br><br>

<a href="<?=$data['baseUrl']?>/donor/verifyEmail?token=<?=$data['token']?>" title="Potvrdi email adresu">Potvrdi email adresu</a>
</p>