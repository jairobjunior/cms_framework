<?
class DateHelper extends SKHelper {

	//29 de month de 2009
	function inWordsOld ($date) {
		$date = explode(' ', $date);
		list ($year, $month, $day) = preg_split('/[\/.-]/', $date[0]);
		$inWords = $this->i18n['date']['inWords'];
		$inWords = str_replace("%d", $day, $inWords);
		$inWords = str_replace("%B", $this->i18n['date']['months'][$month-1], $inWords);
		$inWords = str_replace("%Y", $year, $inWords);
		return $inWords;
	}
	
	
	function inWords($date) {
		if($this->i18n['lang'] === 'pt-br'){
			setlocale(LC_ALL, 'portuguese', 'pt_BR', 'pt_br', 'ptb_BRA');
		}
		return strftime($this->i18n['date']['inWords'],strtotime($date));
	}


	// 11:29
	function time ($date) {
		$date = explode(' ', $date);
		list ($hour, $minutes, $second) = explode(':', $date[1]);
		return $hour.":".$minutes;
	}
	
	
	
	function show($date = null) {
		$format = str_replace("%", "", $this->i18n['date']['default']);
		if(empty($date)) return date ($format);
		return date ($format, strtotime($date));
	}
}
?>
