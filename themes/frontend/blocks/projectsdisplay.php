<?php if(isset($block)): ?>

    <div class="projectsWrapper">
        <div class="projectsDisplay">
            <?php foreach(($block['projects'] ?? []) as $project): ?>
                <div class="projectDisplay<?=!empty($project['className']) ? ' ' . htmlentities($project['className']) : ''?>">
                    <?php if(!empty($project['filename'])): ?>
                        <img src="/images<?=htmlentities($project['filename'])?>"
                             alt="<?=htmlentities($project['alt'] ?: ($project['title'] ?? ''))?>">
                    <?php endif; ?>
                    <h2><?=htmlentities($project['title'] ?? '')?></h2>
                    <div><?=$project['description'] ?? ''?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
