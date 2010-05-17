<?php
	/**
	 * Classe para trabalhar com arquivos de template onde 
	 * todas as views mais complexas podem utilizar para facilitar na
	 * mudança de layout.
	 * @author Jairo Junior, Danillo Cesar
	 * @since 19/03/2008
	 */
	class SKTemplate{

		private $variables   = array();
		public $gzip       	 = true;
		public $partial_path = 'partials/';
		
		
		
		/**
		 * Displays the specified page.
		 *
		 * @param string $filename
		 */
		public function renderPage($filename) {
			if ($this->gzip) ob_start('ob_gzhandler');
			return $this->fetch($filename);
		}
		
		/**
		 * Render any specified view (used inside page)
		 *
		 * @param string $view 
		 * @return void
		 */
		public function render($view) {
			$view = ($view[0] === '/') ? $view : $this->fetch(VIEW.'/'.$view.'.php');
			echo $view;
		}
		
		/**
		 * Render any specified partial (used inside page)
		 *
		 * @param string $view 
		 * @return void
		 */
		public function partial($view) {
			echo $this->fetch(VIEW.'/'.$this->partial_path.$view.'.php');
		}
		
		
		/**
		 * Parses the specified template and returns its content.
		 *
		 * @param   string $___filename
		 * @return  string
		 */
		public function fetch($___filename) {
			ob_start();
			extract($this->variables, EXTR_REFS | EXTR_OVERWRITE);
			include($___filename);
			$___content = ob_get_contents();
			ob_end_clean();
			
			return $___content;
		}
		
		/**
		 * Gets the specified assigned variable.
		 *
		 * @param   string $name
		 * @return  mixed
		 */
		public function get($name)	{
			return $this->variables[$name];
		}
		
		/**
		 * Assigns the variable $name.
		 *
		 * @param string    $name 
		 * @param mixed     $value
		 */
		public function add($name, $value) {
			$this->variables[$name] = $value;
		}
		
		/**
		 * Clears the specified variable.
		 *
		 * @param   string  $name
		 */
		public function clear($name) {
			unset($this->variables[$name]);
		}
		
		/**
		 * Clears all variables.
		 */
		public function clearAll()	{
			$this->variables = array();
		}
		
		/**
		 * Assigns the specified variable $name with the content of $filename.
		 *
		 * @param   string  $name
		 * @param   string  $filename
		 * @return  string
		 */
		function load($name, $filename)	{
			//if (!is_file($filename)) { $this->variables[$name] = "<strong>{$filename}</strong> not found."; return; }
			$content = $this->fetch($filename);
			$this->variables[$name] = $content;
			return $content;
		}
		
		/**
		 * Parses the specified file $filename with an array $data.
		 * Useful for parsing piece of code.
		 *
		 * @param   string  $filename
		 * @param   array   $data
		 * @return  string
		 */
		function parse($filename, $data) {
			$tpl = new Template();
			foreach ($data as $k => $v)
				$tpl->add($k, $v);
			
			return $tpl->fetch($filename);
		}
	}
?>
