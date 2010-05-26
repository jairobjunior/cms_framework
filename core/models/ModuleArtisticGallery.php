<?
class ModuleArtisticGallery extends AppModel {

	public $table = "artistic_galleries";
	protected $uses = array('trash','status');

	public function __construct() {
		parent::__construct();
		$this->imports('Selector');
	}
}
?>
