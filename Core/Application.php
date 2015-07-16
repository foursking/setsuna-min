<?php

namespace Setsuna\Core;

use Setsuna\Router\Router;
use Setsuna\Core\Pimple;
use Setsuna\Database\Connection;
use Setsuna\Database\Mysql;
use \Exception;


class Application 
{ 

    protected $router;
    public static $container = '';
    public function __construct() { }

    public function detectEnvironment($environments = array()){

        foreach ($environments as $environment => $hosts)
        {

            foreach ((array) $hosts as $host)
            {
                if ($host == gethostname()) return $environment;
            }
        }

        return 'production';

    }



    public function init()
    {
        $container = new Pimple();
        $container['carrier'] = array(
            'suffix'  => array(
                'class' => 'Controller',
                'action' => 'Action',
            ),

        );

        $container['APP_ROOT'] = APPPATH;

        $env = $this->detectEnvironment();


        //init router
        $container['Router'] = $container->share(function() {
            return new Router();
        });

        //init router
        $container['redis'] = $container->share(function() {
            $config = include(APPPATH . '/config/' . GN_ENVIRONMENT . '/cache.php');
            return new \Setsuna\Storage\Cache\Redis($config['default']);
        });


        //init router
        $container['redis_xcross'] = $container->share(function() {
            $config = include(APPPATH . '/config/' . GN_ENVIRONMENT . '/cache.php');
            return new \Setsuna\Storage\Cache\Redis($config['xcross']);
        });




        $container['register_master'] = $container->share(function(){

            $config = include(APPPATH . '/config/' . GN_ENVIRONMENT . '/database.php');
            $config = $config['register_master'];
            $mysql = new Mysql();
            return $mysql->get_instance(
                $config['hostname'] . ':' . $config['port'] , 
                $config['username'] ,
                $config['password'] ,
                $config['database']
            );

        });


    $container['xcross_master'] = $container->share(function(){

            $config = include(APPPATH . '/config/' . GN_ENVIRONMENT . '/database.php');
            $config = $config['xcross_master'];
            $mysql = new Mysql();
            return $mysql->get_instance(
                $config['hostname'] . ':' . $config['port'] , 
                $config['username'] ,
                $config['password'] ,
                $config['database']
            );

        });




        self::$container = $container;

        return $container;
    }

    /**
     * @return type
     */

    public function run() {
        if(defined('GN_SAPI_NAME') AND 'cli' == GN_SAPI_NAME) {
            $reqUri = '/' . ltrim($_SERVER['argv'][1] , '/');
        }else{
            $reqUri = array_shift(explode('?', $_SERVER['REQUEST_URI']));
        }

        list($call, $param) = self::$container['Router']->dispatch($reqUri);

        if (is_array($call)) {
            $class = empty($call[0]) ? 'index' . self::$container['carrier']['suffix']['class'] 
                : $call[0] . self::$container['carrier']['suffix']['class'];
            $func = empty($call[1]) ? 'index' . self::$container['carrier']['suffix']['action'] 
                : $call[1] . self::$container['carrier']['suffix']['action'];

            $c = new $class();

            $c->selfinit(self::$container);

            return call_user_func(array($c , $func));


        } else {
            // i dont know
            return $call($param);
        }
    }



}


