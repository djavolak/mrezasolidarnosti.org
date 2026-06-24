<?php if(isset($block)): ?>

    <div class="whatIsVisual">
        <?php foreach(($block['cards'] ?? []) as $card): ?>
            <div>
                <?php if(!empty($card['filename'])): ?>
                    <img src="/images<?=htmlentities($card['filename'])?>"
                         alt="<?=htmlentities($card['alt'] ?: ($card['title'] ?? ''))?>">
                <?php endif; ?>
                <h2><?=htmlentities($card['title'] ?? '')?></h2>
                <div><?=$card['description'] ?? ''?></div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
