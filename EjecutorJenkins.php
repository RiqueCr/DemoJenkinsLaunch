<?php

    namespace App\Classes;

    use App\Classes\JenkinsCURL;

    class EjecutorJenkins{

        // Propiedades
        private $baseUrl = 'http://cristian:11f2c1bc575a81841c22094c96e2d233f5@localhost:8090/job/TestJulio2021/';
        private $buildJob = 'build';
        private $buildJobWithParameters = 'buildWithParameters';
        private $lastBuildNumber = 'lastBuild/buildNumber';
        private $buildTimestamp = 'buildTimestamp';
        private $apiJsonJob = 'api/json';
        private $stopJob = 'stop';
        private $objJenkins;

        public function __construct(){}

        /**
         * Recupera el numero de la ultima ejecucion de un Job
         *
         * @return void
         */
        public function getLastBuildNumber(){
            $lastNumber;
            $this->objJenkins = new JenkinsCURL($this->baseUrl . $this->lastBuildNumber);
            $lastNumber = $this->objJenkins->init()->setOption()->setOption(CURLOPT_POST, true)->setOption(CURLOPT_RETURNTRANSFER, true)->execute();
            $this->objJenkins->close();
            return $lastNumber;
        }

        /**
         * Recupera el status de una build
         * Es necesario ingresar por parametro el numero de la build a consultar
         * Retorna un json como string. Es necesario hacer decode para acceder a los datos
         * @param [type] $buildNumber 
         * @return void
         */
        public function getBuildStatus($buildNumber){
            $buildStatusJSON;
            $this->objJenkins = new JenkinsCURL($this->baseUrl . $buildNumber . '/' . $this->apiJsonJob);
            $buildStatusJSON = $this->objJenkins->init()->setOption()->setOption(CURLOPT_POST, true)->setOption(CURLOPT_RETURNTRANSFER, true)->execute();
            $this->objJenkins->close();
            return $buildStatusJSON;
        }

        /**
         * Ejecuta Job sin parametros
         *
         * @return void
         */
        public function buildJob(){
            $this->objJenkins = new JenkinsCURL($this->baseUrl . $this->buildJob);
            $this->objJenkins->init()->setOption()->setOption(CURLOPT_POST, true)->execute();
            $this->objJenkins->close(); 
        }

        /**
         * Retorna la hora y fecha de una ejecucion
         * Es necesario ingresar por parametros el numero de la build
         *
         * @param [type] $buildNumber
         * @return void
         */
        public function getBuildTimestamp($buildNumber){
            $timeStamp;
            $this->objJenkins = new JenkinsCURL($this->baseUrl . $buildNumber . '/' . $this->buildTimestamp);
            $timeStamp = $this->objJenkins->init()->setOption()->setOption(CURLOPT_POST, true)->setOption(CURLOPT_RETURNTRANSFER, true)->execute();
            $this->objJenkins->close(); 
            return $timeStamp;
        }

        /**
         * Ejecuta Job con parametro
         * Es necesario ingresar por parametro el valor
         *
         * @param [type] $param Valor para enviar a la build
         * @return void
         */
        public function buildWithParameters($param01, $param02){
            $array = [
                'PARAM' => $param01,
                'EMAIL' => $param02
            ];
            $data = http_build_query($array);
            $this->objJenkins = new JenkinsCURL($this->baseUrl . $this->buildJobWithParameters);
            $this->objJenkins->init()->setOption()->setOption(CURLOPT_POST, true)->setOption(CURLOPT_POSTFIELDS, $data)->execute();
            $this->objJenkins->close(); 
        }

        /**
         * Aborta build en ejecucion
         * Es necesario ingresar por parametros el numero de la build a abortar
         *
         * @param [type] $buildNumber
         * @return void
         */
        public function stopBuild($buildNumber){
            $this->objJenkins = new JenkinsCURL($this->baseUrl . $buildNumber . '/' . $this->stopJob);
            $this->objJenkins->init()->setOption()->setOption(CURLOPT_POST, true)->execute();
            $this->objJenkins->close(); 
        }


        public function mensaje01(){
            echo '<p style="font-family: sans-serif;">Iniciando una nueva ejecución pruebas automatizadas <br>' .
                  '¡Por favor no intente iniciar una nueva ejecución en los próximos minutos! <br>'. 
                  '¡Nuevas ejecuciones estarán bloqueadas hasta finalizar actual! <br>' .
                  'Espere a los resultados en su bandeja de entrada. <br> '. 
                  'Retornando a pagina de inicio en 30 segundos...
                  </p>';
        }


    }



?>