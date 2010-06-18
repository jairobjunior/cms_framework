<?php

/**
 * Controlador principal do framework
 *
 * @author Sook contato@sook.com.br
 */

	abstract class SKController {

		protected $template;

		protected $layout = "default";
		protected $helpers = array();
		protected $render = true;

		private		$i18n = null;
		private		$config;

		protected $params = array();
		/**
        * Função Construtora da Classe
        * @access public
        * @return void
        */
		public function __construct($request,$i18n,$config){
			$this->config = $config;
			$this->i18n = $i18n;
			$this->params = $request->params;
			$this->template = new SKTemplate();
			$this->request = $request;
		}
        /**
        * Função para pré carregamento de funções
        * @access public
        * @return void
        */
		public function beforeFilter() {}
		/**
        * Função para carregamento posterior de funções
        * @access public
        * @return void
        */
		public function afterFilter() {}

		/**
        * Função que adiciona chave e valores ao template
        * @access public
        * @param String $key
        * @param Mixed $value
        * @return void
        */

		public function add($key, $value){
			$this->template->add($key, $value);
		}
		/**
        * Função renderizadora das views
        * @access public
        * @param String $view
        * @return void
        */
		public function render($view){
			$this->loadHelpers();
			$view = $view[0] == '/' ? substr($view, 1) : 'views/'.strtolower($this->request->controller_name).'/'.$view;

			$content = $this->template->renderPage($view.".php");

			// Para não redenrizar layout.
			// Setar no controller: var $layout = null;
			if(!empty($this->layout)){
				$this->add('content',$content);
				$content = $this->template->fetch('views/layouts/'.$this->layout.'.php');
			}
			echo $content;

			if (BENCHMARK){
				echo '<style type="text/css">
					div.cms_debug{
						background-color: white;
						position: fixed;
						bottom:0;
						-moz-box-shadow:0 -1px 4px #000;
						box-shadow:0 -1px 4px #000;
						-webkit-box-shadow:0 -1px 4px #000;
						padding: 2px 4px 0 4px;
						left:10px;
						opacity:0.3;
					}
					div.cms_debug:hover{
						opacity:1;
					}
				</style>';
				Benchmark::stop('Load Time');
				echo '<div class="cms_debug">';
				foreach (Benchmark::getTotals() as $total) {
					echo $total.'<br>';
				}
				echo '</div>';
			}
			die(); // Para garantir e não chamar 2 render.
		}

        /**
        * Redireciona para url desejada
        * @access public
        * @param String $url
        * @param Boolean $full
        * @return void
        */
		function redirect($url,$full = false) {
			$url = $full ? $url : APP_URL.$url;
			header('Location: '.$url);
		}

        /**
        * Passa valor de status para o cabeçalho
        * @access public
        * @param String $status
        * @return void
        */
		function header($status) {
			header($status);
		}

        /**
        * Executa a renderização completa incluindo as funções padrões
        * @access public
        * @param String $action
        * @return void
        */
		public function execute($action) {

			$this->beforeFilter();
			$this->$action();
			$this->afterFilter();


			$this->template->params = $this->params;
			$this->render($action);
		}

        /**
        * Carrega os Helpers
        * @access public
        * @param Array $helpers
        * @return void
        */
		public function helpers($helpers)	{
			$helpers = is_array($helpers) ? $helpers : array($helpers);
			$this->helpers = array_merge($this->helpers, $helpers);
		}

        /**
        * Adiciona os helpers no html.
        * @access private
        * @return void
        */
		private function loadHelpers() {
			// Helpers existentes no core.
			$core_helpers = array('Date','Html','Image','Text','Paginate');

			$this->helpers = array_merge($this->helpers, $this->config['default_helpers']);
			// Adiciona os helpers na view.
			foreach ($this->helpers as $helper) {
				$local = in_array($helper, $core_helpers) ? CORE : ROOT;
				require $local."/helpers/".$helper."Helper.php";
				$class = $helper.'Helper';
				$this->add(strtolower($helper), new $class($this->i18n));
			}

			// Adiciona os helpers requeridos em outros helpers.
			foreach ($this->helpers as $helper) {
				$helper = $this->template->get(strtolower($helper));
				foreach ($helper->uses as $name) {
					$name = strtolower($name);
					$helper->$name = $this->template->get($name);
				}
			}
		}


	}
?>

