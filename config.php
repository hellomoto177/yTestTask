<?php
// database
$dsn = 'mysql:dbname=testTask;host=localhost';
$user = 'user';
$password = 'password';


// files for include
$parseFile = 'orders.txt'; // Файл с заказами для парсинга, содержит client_id;item_id;comment
$fileForOrders = 'undefinedOrders.txt'; // файл для заказов, данные по которым отсутствуют в бд