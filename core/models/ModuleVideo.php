<?
class ModuleVideo extends AppModel {

	public $table = "videos";
	protected $uses = array('trash','status');

	public function __construct() {
		parent::__construct();
	}
}
?>
