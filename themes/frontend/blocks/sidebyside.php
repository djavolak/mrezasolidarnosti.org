<?php if(isset($block)): ?>

    <div class="whatIsContent">
        <div class="whatIsSection">
            <h2><?=nl2br(htmlentities($block['title'] ?? ''))?></h2>
            <div><?=$block['description'] ?? ''?></div>
        </div>
    </div>
<?php endif; ?>
