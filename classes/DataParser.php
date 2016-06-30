<?php 
class DataParser 
{
	private $fileForParse = 'orders.txt'; // Файл с заказами
	private $parseData;

	public function __construct() 
	{
		require 'config.php';
		$this->fileForParse = $parseFile;
	}

	/**
	 * Публичный метод для парсинга текстового файла с заказами
	 */
	public function parse() 
	{
		$file = file($this->fileForParse);
		for ($i = 0; $i < count($file); $i++) {
			$this->parseData[$i] = explode(';', $file[$i]);
		}
	}

	/**
	 * Публичный метод возвращает массив с данными парсинга 
	 * текстового файла заказов
	 */
	public function getParseData() 
	{
		return $this->parseData;
	}
}
?>