<?php
class Label extends AppBehavior {

	public function findAllModelsUsingLabel(&$params) {
		$recordType = "Modules::".get_class($this->model);

		$params['join'] = "INNER JOIN core_v_records_label_types cvrlt ON (cvrlt.record_id = ".$this->model->table.".".$this->model->primaryKey." AND cvrlt.record_type = '".$recordType."')";
		
		if (!empty($params['label'])) {
			$params['where'] = "(".$params['where'].") AND (";
			$conditionAnd = explode("&",$params['label']);
			for ($i = 0; $i < count($conditionAnd)-1; $i++) {
				$params['where'] .= $this->getWhereLabel($conditionAnd[$i]);
				$params['where'] .= "AND";
			}
			$conditionOr = explode("\|",$conditionAnd[count($conditionAnd)-1]);
			for ($i = 0; $i < count($conditionOr); $i++) {
				$params['where'] .= $this->getWhereLabel($conditionOr[$i]);
				$params['where'] .= "OR";
			}
			$params['where'] = rtrim($params['where'],"OR");
			$params['where'] .= ")";
		}
	}
	
	private function getWhereLabel($label) {
		$values = explode("=",$label);
		return " (cvrlt.`alias_type` = '".$values[0]."' AND cvrlt.`alias_label` = '".$values[1]."') ";
	}
}