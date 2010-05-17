<?
class ModuleTheater extends AppModel {

	public $table = "theaters";
	protected $uses = array('trash','status');

	public function __construct() {
		parent::__construct();
	}
}
?>
