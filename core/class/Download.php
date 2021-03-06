<?
class Download {
	
	private $allowedExt = array (
		  // archives
		  'zip' => 'application/zip',

			// documents
		  'pdf' => 'application/pdf',
		  'doc' => 'application/msword',
		  'xls' => 'application/vnd.ms-excel',
		  'ppt' => 'application/vnd.ms-powerpoint',
		  
		  // executables
		  'exe' => 'application/octet-stream',
		
		  // images
		  'gif' => 'image/gif',
		  'png' => 'image/png',
		  'jpg' => 'image/jpeg',
		  'jpeg' => 'image/jpeg',
		
		  // audio
		  'mp3' => 'audio/mpeg',
		  'wav' => 'audio/x-wav',
		
		  // video
		  'mpeg' => 'video/mpeg',
		  'mpg' => 'video/mpeg',
		  'mpe' => 'video/mpeg',
		  'mov' => 'video/quicktime',
		  'avi' => 'video/x-msvideo'
		);
	
	private $logFile = "downloads.log";
	private $hasLog = false;
	
	public function file($filePath) {
		set_time_limit(0);
		if (!is_file($filePath)) {
		  return "File does not exist. Make sure you specified correct file name."; 
		}

		if ($mimeType = $this->getMimeType($filePath) === false) {
			return "Not allowed file type.";
		}
		
		$this->setHeaders($mimeType, basename($filePath));
		
		$file = @fopen($filePath, "rb");
		if ($file) {
  			while(!feof($file)) {
    			print(fread($file, 1024*8));
    			flush();
    			if (connection_status()!=0) {
      				@fclose($file);
      				die();
    			}
  			}
  			@fclose($file);
		}		
		
		$this->log($filePath);
		die();
	}
	
	private function setHeaders($mimeType, $fileName) {
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Type: $mimeType");
		header("Content-Disposition: attachment; filename=\" $fileName \"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: " . filesize($filePath));
	}
	
	private function getMimeType($filePath) {
		// check if allowed extension
		$fileExt = strtolower(substr(strrchr(basename($filePath),"."),1));
		if (!array_key_exists($fileExt, $this->allowedExt)) {
		  return false; 
		}
		if (empty($this->allowedExt[$fileExt])) {
		    return mime_content_type($filePath);
		}
		return $this->allowedExt[$fileExt];
	}
	
	private function log($filePath) {
		if (!$this->hasLog) return;
		$f = @fopen($this->logFile, 'a+');
		if ($f) {
		  @fputs($f, date("m.d.Y g:ia")."  ".$_SERVER['REMOTE_ADDR']."  ".$filePath."\n");
		  @fclose($f);
		}
	}
}
?>