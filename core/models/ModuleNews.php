<?
class ModuleNews extends AppModel {
	
	// Nome da tabela do modelo.
	public $table = "news";
	// Nome do modelo.
	public $name = "New";
	
	protected $uses = array('trash','status');

	public function __construct() {
		parent::__construct();
		//$this->imports('Label');
		$this->imports('Selector');
	}
}
?>
