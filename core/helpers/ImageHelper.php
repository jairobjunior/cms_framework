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
	
	
	public function gravatar($email, $default = null) {
		$gravatarMd5 = "";

		$default = ($default != null) ? "?default=".urlencode( $default ) : '';
		
		if ($email != "" && isset($email)) {
	    $gravatarMd5 = md5($email);
	  }
	
	
	//"?default=" . urlencode( $default ) .
		
		
		return '<img src="http://www.gravatar.com/avatar/'.$gravatarMd5.$default.'" width="56" alt="Avatar">';
	}
}

?>