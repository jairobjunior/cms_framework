<?
class TextHelper extends SKHelper {

	function toSlug($string, $space = "-") {
		$string = trim($string);
		
		$search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
		$replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
		$string = str_replace($search, $replace, $string);
		
		
		if (function_exists('iconv')) {
			$string = @iconv('UTF-8', 'ASCII//TRANSLIT', $string);
		}
		$string = preg_replace("/[^a-zA-Z0-9 -]/", "", $string);
		$string = strtolower($string);
		$string = str_replace(" ", $space, $string);
		return $string;
	}

	function truncate($text, $limit = 25, $ending = '...') {
		if (strlen($text) > $limit) {
			$text = strip_tags($text);
			$text = substr($text, 0, $limit);
			$text = substr($text, 0, -(strlen(strrchr($text, ' '))));
			$text = $text . $ending;
		}
		return $text;
	}
	
	// Translate text using il8n.
	function locale($key){
		return $this->i18n[$key];
	}
}
?>