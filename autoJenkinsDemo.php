<?php

    require_once 'autoload.php';

    use App\Classes\JenkinsCURL;

    $baseUrl = 'http://cristian:11f2c1bc575a81841c22094c96e2d233f5@localhost:8090/job/TestJulio2021/';
    $buildJob = 'build';
    $lastBuildNumber = 'lastBuild/buildNumber';
    $apiJsonJob = 'api/json';
    $response;
    $objJenkins;
    $valorBuild;

    // Nuevo: Simple dependiendo del boton, hacer validaciones
    if(isset($_POST['btnEjecutarPruebas'])){

        // 1. Recuperar ultima ejecución
        // Ejecutar una peticion para recuperar el numero de la ultima build generada
        $objJenkins = new JenkinsCURL($baseUrl . $lastBuildNumber);
        $response = $objJenkins->init()->setOption()->setOption(CURLOPT_POST, true)->setOption(CURLOPT_RETURNTRANSFER, true)->execute();
        $objJenkins->close(); // Cerrar conexion
        // Pasar el valor json a primitivo
        $valorBuild = json_decode($response, true);

        //2. Ejecutar una peticion para recuperar los valores asociados a la build con el numero recuperado
        $objJenkins = new JenkinsCURL($baseUrl . $valorBuild . '/' . $apiJsonJob);
        $response = $objJenkins->init()->setOption()->setOption(CURLOPT_POST, true)->setOption(CURLOPT_RETURNTRANSFER, true)->execute();
        $objJenkins->close(); // Cerrar conexion

        
        $decoded = json_decode($response, true);
        $resultado= $decoded['result'];

        // Si la build con el numero recuperada tiene un valor en result diferente a null, ejecutar una nueva build
        if(!is_null($resultado)){
            echo 'Iniciando una nueva ejecución pruebas automatizadas <br>' .
                  '¡Por favor no intente iniciar una nueva ejecución en los próximos minutos! <br>'. 
                  '¡Nuevas ejecuciones estarán bloqueadas hasta finalizar actual! <br>' .
                  'Espere a los resultados en su bandeja de entrada. <br> '. 
                  'Retornando a pagina de inicio en 30 segundos...';
                  header("Refresh:30; url=autoJenkinsDemo.html");

            // 3. Ejecutar Nueva Build
            $objJenkins = new JenkinsCURL($baseUrl . $buildJob);
            $objJenkins->init()->setOption()->setOption(CURLOPT_POST, true)->execute();
            $objJenkins->close(); // Cerrar conexion
        }else{
            // Si la build no tiene un resultado aun, no dejar ejecutar nada
            echo '¡No es posible iniciar una nueva ejecución automatizada! <br> ' . 
                 'Es probable que ya esté en curso una ejecución. <br>'.
                 'Retornando a pagina de inicio en 15 segundos...';
                  header("Refresh:15; url=autoJenkinsDemo.html");
        }

        

    }







    /*

    // Creo un objeto tipo JenkinsCURL, paso por parametros la URL para la build
    //$obj = new JenkinsCURL('http://cristian:11f2c1bc575a81841c22094c96e2d233f5@localhost:8090/job/TestJulio2021/build');
    //$obj = new JenkinsCURL('http://cristian:11f2c1bc575a81841c22094c96e2d233f5@localhost:8090/job/TestJulio2021/lastBuild/buildNumber');
    $obj = new JenkinsCURL('http://cristian:11f2c1bc575a81841c22094c96e2d233f5@localhost:8090/job/TestJulio2021/41/api/json');


    // Invento
    if(isset($_POST['btnEjecutarPruebas'])){
                
        $response = $obj->init()->setOption()->setOption(CURLOPT_POST, true)->setOption(CURLOPT_RETURNTRANSFER, true)->execute();
        $obj->close();
        
        $decoded = json_decode($response, true);

        //var_dump($decoded);
        $valor = $decoded['result'];

        if(is_null($valor)){
            $valor = 'valor nulo todavía';
        }

        echo $valor;

        echo "<br> Ejecución Pruebas exitosa <br>";
        //header("Refresh:10; url=autoJenkinsDemo.html");
    }else{
       echo ":(";
    }
    */


    

?>