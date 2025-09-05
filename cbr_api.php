<?php
class CBR {
    private $currencies = array();
    private $url = "https://cbr.ru/scripts/XML_val.asp?d=0";
    
    public function __construct() {
        $this->currencies = $this->_loadCurrencies();
    }
    
    private function _loadCurrencies() {
        echo "Загрузка данных с ЦБ РФ...\n";
        
        // Загружаем данные с помощью cURL
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);
        
        $xmlContent = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($xmlContent === false) {
            echo "Ошибка загрузки: $error\n";
            return array();
        }
        
        echo "Данные загружены (" . strlen($xmlContent) . " байт)\n";
        
        // Сохраним raw данные для отладки
        //file_put_contents('debug_raw.xml', $xmlContent);
      
        // Конвертируем из windows-1251 в UTF-8
        $xmlContent = iconv('windows-1251', 'UTF-8', $xmlContent);
        
        // Сохраним конвертированные данные для отладки
        //file_put_contents('debug_utf8.xml', $xmlContent);
        
        // Убедимся, что XML declaration правильная
        if (strpos($xmlContent, '<?xml') === 0) {
            // Заменяем encoding на UTF-8 в XML declaration
            $xmlContent = preg_replace('/encoding="[^"]+"/', 'encoding="UTF-8"', $xmlContent, 1);
        } else {
            // Добавляем XML declaration если её нет
            $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>' . $xmlContent;
        }
        
       
        // Парсим XML
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlContent);
        
        if ($xml === false) {
            echo "Ошибки парсинга XML:\n";
            foreach (libxml_get_errors() as $error) {
                echo " - " . $error->message . "\n";
            }
            libxml_clear_errors();
            return array();
        }
        
        // Извлекаем данные
        $currencies = array();
        foreach ($xml->Item as $item) {
            $currency = array(
                'id' => (string)$item['ID'],
                'name' => (string)$item->Name,
                'eng_name' => (string)$item->EngName,
                'nominal' => (int)$item->Nominal,
                'parent_code' => trim((string)$item->ParentCode)
            );
            $currencies[] = $currency;
        }
        
        echo "Успешно загружено валют: " . count($currencies) . "\n";
        return $currencies;
    }
    
    // Валюта по ID
    public function GetByItemID($item_id) {
        foreach ($this->currencies as $currency) {
            if ($currency['id'] === $item_id) {
                return $currency;
            }
        }
        return null;
    }
    
    // Валюта по номиналу
    public function GetItemByNominal($min_nominal, $max_nominal) {
        $result = array();
        foreach ($this->currencies as $currency) {
            if ($currency['nominal'] >= $min_nominal && $currency['nominal'] <= $max_nominal) {
                $result[] = $currency;
            }
        }
        return $result;
    }
    
    // Валюта по названию
    public function GetItemByName($name) {
        $name = mb_strtolower(trim($name), 'UTF-8');
        foreach ($this->currencies as $currency) {
            if (mb_strtolower($currency['name'], 'UTF-8') === $name) {
                return $currency;
            }
        }
        return null;
    }
    
    public function getTotalCurrencies() {
        return count($this->currencies);
    }
}

?>