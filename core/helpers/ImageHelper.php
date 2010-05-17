<?
class ImageHelper extends SKHelper {

	public function resize($image,$height,$widht,$crop = "",$options = array()) {
		$img = '<img src="'.IMAGES_PATH."image.php/".substr($image,strrpos($image,"/"),strlen($image))."?width=".$widht."&height=".$height."&cropratio=".$crop."&image=".$image.'" ';
		foreach ($options as $key => $value) {
			$img .= $key.'="'.$value.'" ';
		}
		$img .= ' />';
		return $img;
	}
}

?>