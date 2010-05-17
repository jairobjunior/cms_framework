<?
class Paginator extends AppBehavior {

	private $countRecords = 0;
	private $currentPage;
	private $perPage = 1;
	

	function SKPaginator($conexao, $numRegistrosPorPagina) {
		$this->conexao = $conexao;
		$this->numRegistrosPorPagina = $numRegistrosPorPagina;
		$this->totalDeRegistros = 0;
	}
	
	function paginate($page = 0, $params = array()) {
		//Altera a página de visualização
		$this->setCurrentPage($page);
		
		//Total de registros no banco do módulo passado com parametro
		$countRecords = $this->model->query("SELECT COUNT(*) as count_all FROM ".$this->model->table." WHERE ".$this->model->getStringWhere($params['where']));
		$this->countRecords = $countRecords[0]['count_all']; 
		
		//Verifica de onde irá iniciar a listagem dos registros
		$from = (($this->getCurrentPage()-1) * $this->perPage);
	
		//Consulta os registros de acordo com o limit.
		$params['limit'] = $from.",".$this->perPage;
		return $this->model->findAll($params);
	}
	
	function setCurrentPage($page) {
		if (!empty($page)){
			$this->currentPage = $page;
		} else {
			$this->currentPage = 1;
		}
	}
	
	function getCurrentPage() {
		return $this->currentPage;
	}

	function getPageNumbers() {
		return ceil($this->countRecords / $this->perPage);
	}

	function getPages() {
		$numberPages = $this-> getPageNumbers();
		for($i = 1; $i <= $numberPages; $i++){
			$pages[] = $i;
		}
		return $pages;
	}

	function hasNext() {
		if( count($this->getPages()) > $this->getCurrentPage()) {
			return true;
		}
		return false;
	}

	function hasPrev() {
		return ($this->getCurrentPage() > 1)? true : false;
	}

	function getNext() {
		return $this->getCurrentPage() + 1;
	}

	function getPrev() {
		return $this->getCurrentPage() - 1;
	}

	function getLastPage() {
		$nPagina = count($this->getPages());
		return ($nPagina < 1)? 1 : $nPagina;
	}

	function hasPages() {
		return ($this->getLastPage() > 1)? true : false;
	}
}
?>