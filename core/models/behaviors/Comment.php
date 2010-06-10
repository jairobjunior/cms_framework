<?php
class Comment extends AppBehavior {
	
	public function findComments($id) {
		$recordType = "Modules::".get_class($this->model);
		
		$params = array();
		$params['where'] = "record_type = '".$recordType."' AND record_id = ".$id." AND published = 1";
		$params['order'] = "id ASC";
		
		$sql = 'SELECT * FROM core_comments  WHERE record_type = "'.$recordType.'" AND record_id = '.$id.' AND published = 1 ORDER BY id ASC';
		return $this->model->query($sql);
	}
	
	
	public function countComments($id) {
		$recordType = "Modules::".get_class($this->model);
		$sql = 'SELECT COUNT(*) as total FROM core_comments WHERE record_type = "'.$recordType.'" AND record_id = '.$id.' AND published = 1';
		$result = $this->model->query($sql);
		return $result[0]['total'];
	}
	
	public function saveComment($data) {
		$recordType = "Modules::".get_class($this->model);
		$this->model->table = "core_comments";
		
		$params = array();
		$params['name'] = $data['name'];
		$params['email'] = $data['email'];
		$params['site'] = $data['site'];
		$params['content'] = $data['content'];
		$params['record_id'] = $data['record_id'];
		$params['record_type'] = $recordType;
		$params['created_at'] = "now()";
		
		return $this->model->save($params);
	}
}