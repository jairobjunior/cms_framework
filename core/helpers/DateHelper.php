<?
class DateHelper extends SKHelper {

	//29 de month de 2009
	function inWords ($date) {
		
		$date = explode(' ', $date);

		
		list ($year, $month, $day) = preg_split('/[\/.-]/', $date[0]);
		$inWords = $this->i18n['date']['inWords'];
		$inWords = str_replace("%d", $day, $inWords);
		$inWords = str_replace("%F", $this->i18n['date']['months'][$month-1], $inWords);
		$inWords = str_replace("%Y", $year, $inWords);
		return $inWords;
	}

	//11:29
	function time ($date) {
		$date = explode(' ', $date);
		list ($hour, $minutes, $second) = explode(':', $date[1]);
		return $hour.":".$minutes;
	}
}
?>
