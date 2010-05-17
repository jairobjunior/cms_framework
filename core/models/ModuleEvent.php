<?
class ModuleEvent extends AppModel {

	public $table = "events";
	protected $uses = array('trash','status');

	public function __construct() {
		parent::__construct();
	}
}
?>
