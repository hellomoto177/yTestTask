<?php
class OrdersHandler 
{
	private $pdo;
	private $txtFile;
	private $clientsExist;
	private $merchExist;

	/**
	* Конструктор создает подключение к базе
	* и достает id существующих клиентов и товаров
	*/
	public function __construct() {
		require 'config.php';
		$this->txtFile = $fileForOrders;

		$this->pdo = new PDO($dsn, $user, $password);
		$stmt = $this->pdo->query('SELECT id FROM clients');
		while ($row = $stmt->fetch()) {
		    $clients_id_array[] = $row['id'];
		}

		$this->clientsExist = $clients_id_array;

		$stmt = $this->pdo->query('SELECT id FROM merchandise');
		while ($row = $stmt->fetch()) {
		    $merch_id_array[] = $row['id'];
		}

		$this->merchExist = $merch_id_array;
    }

	/**
	* Деструктор убивает подключение к базе
	*/
    public function __destruct() {
    	$this->pdo = null;
    }

	/**
	* Публичный метод для просмотра id существующих клиентов
	*/
    public function getClients() {
    	return $this->clientsExist;
    }

	/**
	* Публичный метод для просмотра id существующих товаров
	*/
    public function getMerch() {
    	return $this->merchExist;
    }

	/**
	* Публичный метод для проверки на валидность заказа
	* На вход array($clientId, $itemId)
	* Если клиент и товар есть возвращается (bool)true
	*/
    public function orderCheck(array $order) {
		$cId = $order[0];
		$mId = $order[1];

		if(in_array($cId, $this->clientsExist))
			if(in_array($mId, $this->merchExist))
				return true;			
    }

    /**
	* Публичный метод для добавления заказа в бд
	* На вход array($clientId, $itemId, $comment)
	* Четвертым параметром в базу попадает текущее время Y-m-d H:i:s
	*/
    public function addOrderToDb(array $order) {
		$stmt = $this->pdo->prepare('INSERT INTO orders (client_id, item_id, comment, order_date) VALUES (?,?,?,?)');
		$stmt->execute(array($order[0], 
							 $order[1], 
							 preg_replace("/\r|\n/", "", $order[2]),
							 date("Y-m-d H:i:s"),
						));
    }

    /**
	* Публичный метод для добавления заказа в файл
	* Вызывается в случае, если во входящем заказе присутствует несуществующий id клиента или заказа
	* На вход array($clientId, $itemId, $comment)
	*/
    public function addOrderToFile(array $order) {
    	$file = $this->txtFile;
    	if(file_exists($file) && is_writable($file)) {
    		$stroke = join(';', $order);
			file_put_contents($file, $stroke, FILE_APPEND | LOCK_EX);
    	}
    	else
    		exit('file does not exist or is not writable');
    }
}
?>