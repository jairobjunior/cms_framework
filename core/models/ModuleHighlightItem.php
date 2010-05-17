<?
class ModuleHighlightItem extends AppModel {

	public $table = "highlight_items";
	protected $uses = array('trash','status');

	public function __construct() {
		parent::__construct();
		$this->imports('Selector');
	}
}
?>
