<?php if(isset($block)): ?>
    <div class="instructionsTable">
        <?=$this->formToken()?>
    </div>
    <template id="desktopTemplateInstructionsTable">
        <table>
            <thead>
            <tr>
                <th><?=$this->t('Oštećeni/a')?></th>
                <th><?=$this->t('Iznos')?></th>
                <th><?=$this->t('Poziv na broj')?></th>
                <th><?=$this->t('Kreirano')?></th>
                <th><?=$this->t('Status')?></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </template>
    <template id="mobileTemplateInstructionsTable">

    </template>
<?php endif; ?>
