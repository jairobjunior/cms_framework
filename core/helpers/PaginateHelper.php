<?php
class PaginateHelper extends SKHelper {
	

	public function __construct($i18n){
		parent::__construct($i18n);
	}
	
	public function hasPage($page) {
		return $page->hasPage();
	}
	
	public function hasNext($page) {
		return $page->hasNextPage();
	}
	
	public function hasPrev($page) {
		return $page->hasPrevPage();
	}
	
	public function current($page) {
		return $page->currentPage;
	}
	
	public function urlNext($page, $url = "") {
		return $this->getFormatedUrl($page->getNextPage(), $url);
	}

	public function urlPrev($page, $url = "") {
		return $this->getFormatedUrl($page->getPrevPage(), $url);
	}

	public function urlLastPage($page, $url = "") {
		return $this->getFormatedUrl($page->getLastPage(), $url);
	}
	
	
	
	public function show($page, $options = array()) {
		if(!$this->hasPage($page)) return '';
		
		$defaults = array('type'=>'full','class'=>'paginate','url'=>'','range'=>10);
		$options = array_merge($defaults, $options);
		
		$types = array(
			'full' => array('_prev','_pages','_next'),
			'simple' => array('_prev','_next'),
			'next' => array('_next'),
			'prev' => array('_prev')
		);
		
		$html = '<div class="'.$options['class'].'">';
		foreach ($types[$options['type']] as $value) {
			$html .= $this->$value($page,$options);
		}
		$html .= '</div>';
		
		return $html;
	}
	
	public function _prev($page,$options) {
		$defaults = array('show'=>true);
		$options = array_merge($defaults, $options);
		$text = isset($options['text']) ? $options['text'] : $this->i18n['prev_page'];
		
		if($this->hasPrev($page)) {
			return '<a class="prev_page" href="'.$this->urlPrev($page,$options['url']).'" title="'.$text.'">'.$text.'</a>';
		} else {
			if($options['show']){
				$html = '<span class="disabled prev_page">'.$text.'</span>';
			}else{
				$html = '';
			}
			return $html;
		}
	}
	
	public function _pages($page,$options) {
		$range = $options['range'];
		$range--;
		$pages_list = array();

		$offset_prev =  ($this->current($page)-$range);
		if($offset_prev < 1) $offset_prev = 1;
		for ($i=$offset_prev; $i < $this->current($page); $i++) { 
			$pages_list[] = $i;
		}
		
		$pages_list[] = $this->current($page);
		
		$offset_next =  ($this->current($page)+$range);

		if($offset_next > $page->totalPages) $offset_next = $page->totalPages;
		for ($i=$this->current($page); $i < $offset_next; $i++) { 
			$pages_list[] = $i+1;
		}
		
		
		$html = "";
		foreach ($pages_list as $p) {
			# code...
			if ($this->current($page) == $p) {
				$html .= '<span class="current number">'.$p.'</span>';
			} else {
				$html .= '<a class="number" href="'.$this->getFormatedUrl($p,$options['url']).'">'.$p.'</a>';
			}
		}

		return $html;
	}
	
	public function _next($page,$options) {
		$defaults = array('show'=>true);
		$options = array_merge($defaults, $options);
		$text = isset($options['text']) ? $options['text'] : $this->i18n['next_page'];
		
		if($this->hasNext($page)) {
			return '<a class="next_page" href="'.$this->urlNext($page,$options['url']).'" title="'.$text.'">'.$text.'</a>';
		} else {
			if($options['show']){
				$html = '<span class="disabled next_page">'.$text.'</span>';
			}else{
				$html = '';
			}
			return $html;
		}
	}
	
	// Displaying items 6 - 10 of 26 in total
	public function info($page) {
		$current_page = $page->currentPage;
		$per_page = $page->perPage;
		$records = count($page->results);
		
		$offset = (($current_page-1) * $per_page);
		$init = $offset + 1;
		$end = $offset + $records;
		
		if ($page->totalPages < 2) {
			switch ($page->totalRecords) {
			case 0:
	        echo $this->i18n['page_info']['0'];
	        break;
	    case 1:
	        echo $this->i18n['page_info']['1'];
	        break;
	    default;
	        echo $this->sprintf2($this->i18n['page_info']['all'],array('value'=>$page->totalRecords));
	        break;
			}
		} else {
			echo $this->sprintf2($this->i18n['page_info']['range'],array('from'=>$init,'to'=>$end,'all'=>$page->totalRecords));
		}
	}
	
	
	public function getFormatedUrl($pageNumber, $url = "") {
		$params = "";
		$currentUrl = $this->getURL();
		if (strpos($currentUrl,'?') != false) {
			$params = substr($currentUrl, strpos($currentUrl,'?'), strlen($currentUrl));			
		}	
		return APP_URL.$url."/".$pageNumber.'/'.$params;
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