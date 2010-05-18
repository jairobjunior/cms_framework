<?php
	abstract class SKController {
			
		//Helpers padrão para toda a aplicação
		#private $default_helpers = array('Html','Date','Text','Image');
		
		protected $template;

		protected $modelPaginate;
		
		protected $layout = "default";
		protected $helpers = array();
		protected $render = true;
		
		private		$i18n = null;
		private		$config;
		
		protected $params = array(); 
		
		public function __construct($request,$i18n,$config){
			$this->config = $config;
			$this->i18n = $i18n;
			$this->params = $request->params;
			$this->template = new SKTemplate();
			$this->loadHelpers();
			$this->beforeFilter();
			$this->request = $request;
		}

		public function beforeFilter() {}
		
		public function add($key, $value){
			$this->template->add($key, $value);
		}
		
		public function render($view){
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
		
		
		function redirect($url) {
			header('Location: '.SITE_URL.$url);
		}
		
		
		function header($status) {
			header($status);
		}
		
		public function execute($action) {
			$this->$action();
			$this->render($action);
		}

		// Adiciona os helpers no html.
		private function loadHelpers() {
			$paginateIndex = array_search('Paginate', $this->helpers);
			if ($paginateIndex !== false) {
				require CORE."/helpers/PaginateHelper.php";
				$this->add("paginate", new PaginateHelper($this->i18n, $this->modelPaginate));
				unset($this->helpers[$paginateIndex]);
			}

			//TODO REFACTORE
			foreach ($this->config['default_helpers'] as $helper) {
				require CORE."/helpers/".$helper."Helper.php";
				$class = $helper.'Helper';
				$this->add(strtolower($helper), new $class($this->i18n));
			}
			foreach ($this->helpers as $helper) {
				$class = $helper.'Helper';
				require ROOT."/helpers/".$helper."Helper.php";
				$this->add(strtolower($helper), new $class($this->i18n));
			}
		
		}	
		
			
	}
?>
