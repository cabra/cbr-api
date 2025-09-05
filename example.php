<?php
require_once 'cbr_api.php';

// Примеры использования
$cbr = new CBR();

echo "=== CBR Currency API Examples ===\n\n";

// 1. Получить валюту по ID
echo "1. Валюта по ID R01010:\n";
$currency = $cbr->GetByItemID("R01010");
if ($currency) {
    print_r($currency);
} else {
    echo "Не найдено\n";
}

// 2. Получить валюты по номиналу
echo "\n2. Валюты с номиналом 1-10:\n";
$currencies = $cbr->GetItemByNominal(1, 10);
echo "Найдено: " . count($currencies) . " валют\n";

// 3. Получить валюту по названию
echo "\n3. Валюта по названию 'Финляндская марка':\n";
$currency = $cbr->GetItemByName("Финляндская марка");
if ($currency) {
    print_r($currency);
} else {
    echo "Не найдено\n";
}

echo "\nВсего валют в базе: " . $cbr->getTotalCurrencies() . "\n";
?>