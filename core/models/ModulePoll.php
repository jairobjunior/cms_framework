<?
class ModulePoll extends AppModel {

	public $table = "polls";
	protected $uses = array('trash','status');

	public function __construct() {
		parent::__construct();
	}
	
	public function findAll($params = array()) {
		$polls = parent::findAll($params);
		if(!$polls) return false;
		
		foreach ($polls as $key => $value) {
			$s = $this->connection->find("SELECT * FROM polls_responses WHERE polls_id = ".$polls[$key]['id']." ");
			$polls[$key]['responses'] = $s;
		}
		return $polls;
	}
}
?>
