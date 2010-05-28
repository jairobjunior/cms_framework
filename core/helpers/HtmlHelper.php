<?
class HtmlHelper extends SKHelper {
	
	private $styleSheets = array();
	
	function url($v){
		return APP_URL.$v;
	}
	
	function link() {
		$args = func_get_args();
		
		$title = isset($args[2]['title']) ? $args[2]['title']: '';
		$id = isset($args[2]['id']) ? $args[2]['id']: '';
		$class = isset($args[2]['class']) ? $args[2]['class']: '';
		$rel = isset($args[2]['rel']) ? $args[2]['rel']: '';
		
		return '<a id="'.$id.'" class="'.$class.'" rel="'.$rel.'" href="'.$args[1].'" title="'.$title.'">'.$args[0].'</a>';
	}

	function modules($v){
		return MODULES_PATH.$v;
	}
	
	// Return full image path.
	function image($v){
		return IMAGES_PATH.$v;
	}

	// Insert stylesheets files.
	function css($files) {
		$files = is_array($files) ? $files : array($files);
		$this->styleSheets = array_merge($this->styleSheets, $files);
	}
	
	// Print the stylesheets on page.
	function styleSheets($files) {
		$result = '';
		$this->styleSheets = array_merge($files, $this->styleSheets);
		foreach ($this->styleSheets as $file){
			$result.= '<link rel="stylesheet" href="'.CSS_PATH.$file.'" type="text/css" media="all"/>';
		}
		return $result;
	}
	
	//Insert javascript files
	function js($files){
		$result = '';
		foreach ($files as $file){
			$result.= '<script type="text/javascript" src="'.JAVASCRIPTS_PATH.$file.'"></script>';
		}
		return $result;
	}
}
?>
