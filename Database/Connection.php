<?php

namespace Setsuna\Database;
use Setsuna\Database\ActionMapper;

class Connection extends ActionMapper 
{


	protected $app;


	protected $factory;


	protected $connections = array();


	protected $extensions = array();



    public function __construct($config = array())
    {

       
        if (extension_loaded('pdo'))
        {

            if(2 == arrayLevel($config)) {
                shuffle($config);
                $config = $config[0];
            }
            $config['hostname'] = 'mysql:host=' . $config['hostname'] . ';' 
                                           . 'port=' . ($config['port'] ? $config['port'] : '33306') . ';'
                                           . 'dbname=' . $config['database'];

            parent::__construct($config);
            $this->initialize();
        }
        else
        {
            //throw exception here 
        
        }

    
    }

	

    Private function setup_server(){
    
    
    
    }



	private function initialize()
	{
		if (is_resource($this->conn_id) OR is_object($this->conn_id))
		{
			return true;
		}

		$this->conn_id = $this->pconnect ? $this->db_pconnect() : $this->db_connect();

        return true;

    }

} 


