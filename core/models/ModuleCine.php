<?
class ModuleCine extends AppModel {

	public $table = "cines";
	protected $uses = array('trash','status');

	public function __construct() {
		parent::__construct();
	}
}
?>
