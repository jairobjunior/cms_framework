<?php
class Comment extends AppBehavior {
	
	public function findComments($id) {
		$recordType = "Modules::".get_class($this->model);
		$this->model->table = "core_comments";
		
		$params = array();
		$params['where'] = "record_type = '".$recordType."' AND record_id = ".$id." AND status = 1";
		$params['order'] = "id ASC";
		
		return $this->model->findAll($params);
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