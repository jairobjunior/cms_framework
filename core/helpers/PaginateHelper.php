<?php
class PaginateHelper extends SKHelper {
	
	private $model;
	
	public function __construct($i18n, $model = null){
		parent::__construct($i18n);
		$this->model = $model;
	}
	
	public function hasNext() {
		return $this->model->hasNext();
	}
	
	public function hasPrev() {
		return $this->model->hasPrev();
	}
	
	public function urlNext() {
		return $this->getFormatedUrl($this->model->getNext());
	}

	public function urlPrev() {
		return $this->getFormatedUrl($this->model->getPrev());
	}

	public function urlLastPage() {
		return $this->getFormatedUrl($this->model->getLastPage());
	}
	
	public function urlPages($url) {
		$currentUrl = $this->getURL();
		if (strpos($currentUrl,'?') != false) {
			$params = substr($currentUrl, strpos($currentUrl,'?'), strlen($currentUrl));			
		}
		foreach ($this->model->getPages() as $page){
			$urlPages[] = (substr($url,-1) == '/')? $url.$page.$params:$url."/".$page.$params;
		}
		return $urlPages;
	}
	
	private function getFormatedUrl($pageNumber) {
		$url = $this->getURL();
		if (strpos($url,'?') != false) {
			$params = substr($url, strpos($url,'?'), strlen($url));
			$url = str_replace($params,'',$url);
		}
		$url = substr($url, 0, strrpos($url,'/'));
		return $url."/".$pageNumber.$params;
	}
	
	private function getURL(){
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = $this->strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
	}
	
	private function strleft($s1, $s2) {
		return substr($s1, 0, strpos($s1, $s2));
	}
}