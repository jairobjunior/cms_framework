<?
class ModuleMusic extends AppModel {

	public $table = "musics";
	protected $uses = array('trash','status');

	public function __construct() {
		parent::__construct();
		$this->imports('Selector');
		$this->imports('Tag');
	}
}
?>
