# CBR Currency API

PHP класс для работы с данными о валютах с сайта Центрального Банка РФ.

## Установка

1. Скачайте файлы
2. Разместите на вашем сервере
3. Подключите класс: `require_once 'cbr_api.php';`

## Использование

```php
$cbr = new CBR();

// Получить валюту по ID
$currency = $cbr->GetByItemID("R01010");

// Получить валюты по номиналу
$currencies = $cbr->GetItemByNominal(1, 10);

// Получить валюту по названию
$currency = $cbr->GetItemByName("Доллар США");