<?php
$formatNumber = function(int $amount): string {
    return number_format($amount, 0, ',', '.');
};
?>
<style>
    .schoolStatsContainer { padding: 1rem; }
    .schoolStatsPeriod {
        margin-bottom: 1.5rem;
    }
    .schoolStatsPeriod h4 {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--colorPrimary-400, #a587fa);
        margin-bottom: 0.75rem;
        padding-bottom: 0.25rem;
        border-bottom: 1px solid var(--colorOnBorders, #eee);
    }
    .schoolStatsGrid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 0.75rem;
    }
    .schoolStatCard {
        background: var(--colorSurface-100, #fff);
        border: 1px solid var(--colorOnBorders, #e0e0e0);
        border-radius: 8px;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .schoolStatCard .label {
        font-size: 0.8rem;
        color: var(--colorOnSurface-400, #888);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .schoolStatCard .value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
    }
    .schoolStatCard .value .unit {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--colorOnSurface-400, #888);
    }
</style>

<div class="schoolStatsContainer">
    <?php foreach ($schoolStats as $stat): ?>
        <div class="schoolStatsPeriod">
            <h4><?= htmlspecialchars($stat['period']->getLabel()) ?></h4>
            <div class="schoolStatsGrid">
                <div class="schoolStatCard">
                    <span class="label">Broj ostecenih</span>
                    <span class="value"><?= $formatNumber($stat['beneficiaryCount']) ?></span>
                </div>
                <div class="schoolStatCard">
                    <span class="label">Trazeni iznos</span>
                    <span class="value"><?= $formatNumber($stat['requestedAmount']) ?> <span class="unit">RSD</span></span>
                </div>
                <div class="schoolStatCard">
                    <span class="label">Potvrdene (<?= $formatNumber($stat['confirmedCount']) ?>)</span>
                    <span class="value"><?= $formatNumber($stat['confirmedAmount']) ?> <span class="unit">RSD</span></span>
                </div>
                <div class="schoolStatCard">
                    <span class="label">Placene (<?= $formatNumber($stat['paidCount']) ?>)</span>
                    <span class="value"><?= $formatNumber($stat['paidAmount']) ?> <span class="unit">RSD</span></span>
                </div>
                <div class="schoolStatCard">
                    <span class="label">Aktivne (<?= $formatNumber($stat['activeCount']) ?>)</span>
                    <span class="value"><?= $formatNumber($stat['activeAmount']) ?> <span class="unit">RSD</span></span>
                </div>
                <div class="schoolStatCard">
                    <span class="label">Otkazane (<?= $formatNumber($stat['cancelledCount']) ?>)</span>
                    <span class="value"><?= $formatNumber($stat['cancelledAmount']) ?> <span class="unit">RSD</span></span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
