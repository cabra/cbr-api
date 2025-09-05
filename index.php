<?php
require_once 'cbr_api.php';
$cbr = new CBR();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBR Currency API</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
</head>
<body>
<div class="container">
  <div class="row">
    <div class="column">
	<h1>CBR Currency API Demo</h1>
    
    <h2>Валюта по ID R01010</h2>
    <?php if ($currency = $cbr->GetByItemID("R01010")): ?>
        <div class="currency">
            <h3><?= htmlspecialchars($currency['name']) ?></h3>
            <p><strong>ID:</strong> <?= htmlspecialchars($currency['id']) ?></p>
            <p><strong>Англ. название:</strong> <?= htmlspecialchars($currency['eng_name']) ?></p>
            <p><strong>Номинал:</strong> <?= htmlspecialchars($currency['nominal']) ?></p>
        </div>
    <?php else: ?>
        <p class="error">Валюта не найдена</p>
    <?php endif; ?>

    <h2>Валюты с номиналом 1-10 (первые 5)</h2>
    <?php foreach (array_slice($cbr->GetItemByNominal(1, 10), 0, 5) as $curr): ?>
        <div class="currency">
            <h3><?= htmlspecialchars($curr['name']) ?></h3>
            <p><strong>Номинал:</strong> <?= htmlspecialchars($curr['nominal']) ?></p>
            <p><strong>ID:</strong> <?= htmlspecialchars($curr['id']) ?></p>
        </div>
    <?php endforeach; ?>

    <h2>Валюта по названию</h2>
    <?php if ($currency = $cbr->GetItemByName("Доллар США")): ?>
        <div class="currency">
            <h3><?= htmlspecialchars($currency['name']) ?></h3>
            <p><strong>ID:</strong> <?= htmlspecialchars($currency['id']) ?></p>
            <p><strong>Англ. название:</strong> <?= htmlspecialchars($currency['eng_name']) ?></p>
            <p><strong>Номинал:</strong> <?= htmlspecialchars($currency['nominal']) ?></p>
        </div>
    <?php else: ?>
        <p class="error">Валюта не найдена</p>
    <?php endif; ?>
	
    <h2>Всего валют: <?= $cbr->getTotalCurrencies() ?></h2>	
	</div>
  </div>
</div>
</body>
</html>