<?php

    require_once 'autoload.php';

    use App\Classes\EjecutorJenkins;


    // Nuevo: Simple dependiendo del boton, hacer validaciones
    if(isset($_POST['btnEjecutarPruebas'])){

        // 1. Recuperar el numero de la ultima build generada
        $objEjecutor = new EjecutorJenkins();
        $response = $objEjecutor->getLastBuildNumber();
        $valorBuild = json_decode($response, true);

        //2. Ejecutar una peticion para recuperar los valores asociados a la build con el numero recuperado
        $response = $objEjecutor->getBuildStatus($valorBuild);
        $decoded = json_decode($response, true);
        $resultado= $decoded['result'];

        // Si la build con el numero recuperada tiene un valor en result diferente a null, ejecutar una nueva build
        if(!is_null($resultado)){
            $objEjecutor->mensaje01();
            echo 'Iniciando una nueva ejecución pruebas automatizadas <br>' .
                  '¡Por favor no intente iniciar una nueva ejecución en los próximos minutos! <br>'. 
                  '¡Nuevas ejecuciones estarán bloqueadas hasta finalizar actual! <br>' .
                  'Espere a los resultados en su bandeja de entrada. <br> '. 
                  'Retornando a pagina de inicio en 30 segundos...';
                  header("Refresh:30; url=autoJenkinsDemo.html");
        
            // 3. Ejecutar Nueva Build
            $objEjecutor->buildWithParameters($_POST['rdo1'], $_POST['inpEmail']);
        }else{
            // Si la build no tiene un resultado aun, no dejar ejecutar nada
            echo '¡No es posible iniciar una nueva ejecución automatizada! <br> ' . 
                 'Es probable que ya esté en curso una ejecución. <br>'.
                 'Retornando a pagina de inicio en 15 segundos...';
                  header("Refresh:15; url=autoJenkinsDemo.html");
        }
    

    }



?>