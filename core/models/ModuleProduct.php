<?
class ModuleProduct extends AppModel {

	public $table = "products";
	protected $uses = array('trash','status');

	public function __construct() {
		parent::__construct();
		$this->imports('Selector');
		$this->imports('Tag');
	}
}
?>
