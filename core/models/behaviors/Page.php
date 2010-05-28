<?
class Page extends AppBehavior {

	public $totalRecords = 0;
	public $currentPage;
	public $totalPages;
	public $perPage;
	public $results;
	
	
	function paginate($page = 0, $params = array()) {
		
		// Pega o numero de registro por página.
		$this->perPage = empty($params['perPage']) ? $this->model->perPage: $params['perPage'];
		$params = array_merge($this->model->params, $params);

		// Total de registros no banco do módulo passado com parametro
		$totalRecords = $this->model->query("SELECT COUNT(*) as count_all FROM ".$this->model->table." WHERE ".$this->model->getStringWhere($params['where']));
		$this->totalRecords = $totalRecords[0]['count_all']; 
		
		// Calcula o total de páginas
		$this->totalPages = ceil($this->totalRecords / $this->perPage);
		
		// Altera a página de visualização
		$page = $page > $this->totalPages ? $this->totalPages : $page;
		$this->currentPage = $page;
	
		// Verifica de onde irá iniciar a listagem dos registros
		$from = (($this->currentPage-1) * $this->perPage);
	
		// Consulta os registros de acordo com o limit.
		$params['limit'] = $from.",".$this->perPage;
		
		// Busca registros
		$this->results = $this->model->findAll($params);
		
		return $this;
	}

	function getPages() {
		$pages = array();
		$numberPages = $this->totalPages;
		for($i = 1; $i <= $numberPages; $i++){
			$pages[] = $i;
		}
		return $pages;
	}

	function hasNextPage() {
		if( count($this->getPages()) > $this->currentPage) {
			return true;
		}
		return false;
	}

	function hasPrevPage() {
		return ($this->currentPage > 1)? true : false;
	}

	function getNextPage() {
		return $this->currentPage + 1;
	}

	function getPrevPage() {
		return $this->currentPage - 1;
	}

	function getLastPage() {
		$nPagina = count($this->getPages());
		return ($nPagina < 1) ? 1 : $nPagina;
	}

	function hasPage() {
		return ($this->getLastPage() > 1) ? true : false;
	}
}
?>