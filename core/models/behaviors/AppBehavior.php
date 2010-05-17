<?php
abstract class AppBehavior {
	
	protected $model;
	
	public function __construct($model) {
		$this->model = $model;
	}
}