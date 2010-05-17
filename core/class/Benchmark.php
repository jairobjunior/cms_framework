<?php
/**
 * Benchmark Class
 *
 * @package default
 * @author Danillo César de Oliveira Melo
 */
class Benchmark {
	
	public static $instance;
	public $time = array();
	
	private function __construct() { }

	public static function start($key) {
		$bm = self::getInstance();
		$bm->time[$key]['start'] = microtime(true);
	}

	public static function stop($key) {
		$bm = self::getInstance();
		$bm->time[$key]['stop'] = microtime(true);
		return self::getTime($key);
	}
	
	public static function getTimes() {
		$bm = self::getInstance();
		return $bm->time;
	}
	
	
	public static function getTime($key) {
		$bm = self::getInstance();
		return number_format($bm->time[$key]['stop'] - $bm->time[$key]['start'],8);
	}
	
	public static function getTotals(){
		$bm = self::getInstance();
		$totals = array();
		foreach ($bm->time as $key => $value) {
			$total = number_format($value['stop'] - $value['start'],8);
			$totals[] = $key.': '.$total;
		}
		return $totals;
	}
	
	/**
	 * Sigleton
	 *
	 * @return Benchmark object
	 * @author Danillo César de Oliveira Melo
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
    	self::$instance = new Benchmark();
		}
		return self::$instance;
	}
	
}
?>