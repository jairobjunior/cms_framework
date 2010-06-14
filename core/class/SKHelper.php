<?
abstract class SKHelper {
	
	protected $i18n;
	
	public $uses = array();

	public function __construct($i18n){
		$this->i18n = $i18n;
	}
	
	
	public function sprintf2($str='', $vars=array(), $char='%') {
	    if (!$str) return '';
	    if (count($vars) > 0) {
	        foreach ($vars as $k => $v) {
	            $str = str_replace($char . $k, $v, $str);
	        }
	    }
	    return $str;
	}
	
	
}
?>
