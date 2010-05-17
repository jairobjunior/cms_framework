<?
// Inicio Benchmark
if(BENCHMARK){
	include(CORE.'/class/Benchmark.php');
	Benchmark::start('Load Time');
}


/**
 * Inclui um modulo a ser usado no controlador.
 *
 * @param string $model 
 * @return void
 * @author Danillo César de Oliveira Melo
 */
function include_model($model) {
	require CORE.'/models/Module'.$model.'.php';
	require ROOT.'/models/'.$model.'.php';
}


/**
 * *********** BOOT ************
 */


/**
 * Carrega todos os arquivos necessários.
 */
if(isset($config['language'])){
	include(ROOT.'/configs/i18n/'.$config['language'].'.php');
} else{
	$i18n = array();
}

include(ROOT.'/configs/routes.php');
include(CORE.'/class/SKHelper.php');
include(CORE.'/class/SKTemplate.php');
include(CORE.'/class/SKController.php');
include(CORE.'/class/SKModel.php');
include(ROOT.'/models/AppModel.php');
include(ROOT.'/controllers/AppController.php');
include(CORE.'/models/behaviors/AppBehavior.php');
include(CORE.'/class/RequestHandler.php');


/**
 * Inicia o sistema de roteamento. 
 */
$request = new RequestHandler($route);


/**
 * Inicia o controlador.
 */
if($request->valid){
	$controller_name = $request->controller_name."Controller";
	include(ROOT.'/controllers/'.$controller_name.'.php');
	$controller = new $controller_name($request,$i18n,$config);
	$controller->execute($request->action_name);
}else{
	header("HTTP/1.0 404 Not Found");
	if(empty($config[404])) {
		echo 'Not Found.';
		die();
	}
	include(ROOT.'/public/'.$config[404]);
	die();
}

?>