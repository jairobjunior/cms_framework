<?
class ModuleDownload extends AppModel {

	public $table = "downloads";
	protected $uses = array('trash','status');

	public function __construct() {
		parent::__construct();
		$this->imports('Selector');
		$this->imports('Tag');
	}
}
?>
