<div class="card" style="width: 18rem;">
    <div class="card-body">
        <?php forEach($parts as $part=>$value){?>
        <h5 class="card-title"><?= htmlspecialchars($value->getName()) ?></h5>
        <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($value->getType()) ?> - <?= htmlspecialchars($value->getBrand()) ?></h6>
        <p class="card-text">
            <strong>Model:</strong> <?= htmlspecialchars($value->getModelNumber()) ?><br />
            <strong>Release Date:</strong> <?= htmlspecialchars($value->getReleaseDate()) ?><br />
            <strong>Description:</strong> <?= htmlspecialchars($value->getDescription()) ?><br />
            <strong>Performance Score:</strong> <?= $value->getPerformanceScore() ?><br />
            <strong>Market Price:</strong> $<?= $value->getMarketPrice() ?><br />
            <strong>RSM:</strong> $<?= $value->getRsm() ?><br />
            <strong>Power Consumption:</strong> <?= $value->getPowerConsumptionW() ?>W<br />
            <strong>Dimensions:</strong> <?= $value->getLengthM() ?>m x <?= $value->getWidthM() ?>m x <?= $value->getHeightM() ?>m<br />
            <strong>Lifespan:</strong> <?= $value->getLifespan() ?> years<br />
        </p>
        <p class="card-text"><small class="text-muted">Last updated on <?= htmlspecialchars($value->getTimeStamp()?->getUpdatedAt()??'') ?></small></p> <!-- updated_atのゲッターがあると仮定した場合 -->
        <?php } ?>
    </div>
</div>