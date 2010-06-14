<?
/**
 *	Classe de paginação que será utilizada para listagem dos módulos.
 *	@author Jairo Junior - jairobjunior@gmail.com, Danillo César - danillos@gmail.com
 *	@since	17/11/2007
 */
class Paginator {

	var $conexao;
	var $numRegistrosPorPagina;
	var $paginaAtual;
	var $totalDeRegistros;

	function SKPaginator($conexao, $numRegistrosPorPagina) {
		$this->conexao = $conexao;
		$this->numRegistrosPorPagina = $numRegistrosPorPagina;
		$this->totalDeRegistros = 0;
	}

	function getPaginaAtual() {
		return $this->paginaAtual;
	}

	function getNumeroRegistrosPorPagina() {
		return $this->numRegistrosPorPagina;
	}

	function setPaginaAtual($pag) {
		if (!empty($pag)){
			$this->paginaAtual = $pag;
		} else {
			$this->paginaAtual = 1;
		}
	}

	function buscar($query, $paginaAtual) {
		//Altera a página de visualização
		$this -> setPaginaAtual($paginaAtual);
			
		// Verifica de onde irá iniciar a listagem dos registros
		$inicio = (($this->getPaginaAtual()-1) * $this->getNumeroRegistrosPorPagina());

		// Total de registros no banco do módulo passado com parametro
		$this->totalDeRegistros = count($this->conexao -> consulta($query));
			
		//Monta a query para consultar apenas os limites
		$query .= " LIMIT ".$inicio.", ".$this->getNumeroRegistrosPorPagina();
			
		//Obtem os registros
		$resultadoBusca = $this->conexao -> consulta($query);

		return $resultadoBusca;
	}

	/**
		*	Método que pega o numero total de página do módulo do banco
		* 	Retorna um array()
		*/
	function getPaginas() {
		$retornaArray = array();
			
		// Pega o numero toda de página q irá existir
		$numero_paginas = $this-> getNumeroPaginas();

		for($i = 1; $i <= $numero_paginas; $i++){
			array_push($retornaArray, $i);
		}

		return $retornaArray;
	}

	function getNumeroPaginas() {
		return ceil($this->getTotalRegistros() / $this->getNumeroRegistrosPorPagina());
	}

	function hasProximo() {
		if( count($this->getPaginas()) > $this->getPaginaAtual()) {
			return true;
		}
		return false;
	}

	function hasAnterior() {
		return ($this->getPaginaAtual() > 1)? true : false;
	}


	function getProximo() {
		return $this->getPaginaAtual() + 1;
	}


	function getAnterior() {
		return $this->getPaginaAtual() - 1;
	}


	function getUltimaPagina() {
		$nPagina = count($this->getPaginas());
		return ($nPagina < 1)? 1 : $nPagina;
	}

	function hasPaginas() {
		return ($this->getUltimaPagina() > 1)? true : false;
	}

	function getTotalRegistros() {
		return $this->totalDeRegistros;
	}


	function getNextURL($page = 'pagina') {
		$return = $this->getURL();
		$arrayRemove = array('?'.$page.'='.$this->paginaAtual,'&'.$page.'='.$this->paginaAtual);
		$return = str_replace($arrayRemove,'',$return);

		$newURL = '&'.$page.'='.$this->getProximo();
		if(substr($return,-4) === '.php' || substr($return,-1) === '/'){
			$newURL =  '?'.$page.'='.$this->getProximo();
		}
			
		return $return.$newURL;
	}

	function getPreviousURL($page = 'pagina') {
		$return = $this->getURL();
		$arrayRemove = array('?'.$page.'='.$this->paginaAtual,'&'.$page.'='.$this->paginaAtual);
		$return = str_replace($arrayRemove,'',$return);

		$newURL = '&'.$page.'='.$this->getAnterior();
		if(substr($return,-4) === '.php' || substr($return,-1) === '/'){
			$newURL =  '?'.$page.'='.$this->getAnterior();
		}
			
		return $return.$newURL;
	}

	/**
	 * @desc Retorna o fullpath da pagina com as variaveis get.
	 */
	function getURL(){
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = $this->strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
	}

	function strleft($s1, $s2) {
		return substr($s1, 0, strpos($s1, $s2));
	}

}

/* Exemplo de como usar a classe paginacao

require("_jbj_db.php");

$db = new _jbj_db();

$pagina = 1;

if (isset($_GET['pagina'])) {
$pagina = $_GET['pagina'];
}

$paginacaoSook = new PaginacaoSook($db, 2);

$resultadoBusca = $paginacaoSook -> buscar("SELECT * FROM admin_grupos", $pagina);

foreach ($resultadoBusca as $elemento) {
echo $elemento['ds_grupo']."<br />";
}

$arrayPaginas = $paginacaoSook -> getPaginas();

//Lista dos números de páginas
foreach ($arrayPaginas as $pagina) {
echo "<a href=?pagina=".$pagina.">".$pagina."</a>&nbsp;&nbsp;&nbsp;";
} */

?>