<?php

function __autoload($className) {
	$filename = 'classes/' . $className . '.php';
    require $filename;
}

// Парсим файл с заказами
$dp = new DataParser();
$dp->parse();
$data = $dp->getParseData();

$oh = new OrdersHandler();

/* 
 * Проверяем каждый заказ, если в бд есть id клиента и товара, то добавляем данные в таблицу orders
 * Если данных в базе нет, то заказ отправляется в файл, который указан в $oh->txtFile (private)
 */

for ($i = 0; $i < count($data); $i++) {
	$currentOrder = $data[$i];
	if ($oh->orderCheck($currentOrder) === true) {
		$oh->addOrderToDb($currentOrder);
	} else {
		$oh->addOrderToFile($currentOrder);
	}
}

?>