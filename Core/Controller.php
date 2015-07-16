<?php

namespace Setsuna\Core;


class Controller
{
    public $template;
    public $config;
    public $app;

    private $vars = array();
    private $lazies = array('names' => array(), 'values' => array());

    private $scripts = array();
    private $styles = array();
    
    public function __construct() {
     
    }

    public function selfinit($container){
    
        $this->container = $container;
    
    }

    /**
     * 获取类属性
     * @param string $key
     * @return mixed 如无，返回null
     */
    public function __get($key) {
        if (array_key_exists($key, $this->vars)) {
            return $this->vars[$key];
        } elseif (array_key_exists($key, $this->lazies['names'])) {
            if (!array_key_exists($key, $this->lazies['values'])) {
                $this->lazies['values'][$key] = $this->lazies['names'][$key]();
            }
            return $this->lazies['values'][$key];
        }
        return null;
    }

    /**
     * 给类属性赋值
     * @param string $key
     * @param mixed $value 如果是一个函数，则为懒加载服务
     */
    public function __set($key, $value) {
        if (is_callable($value)) {
            return $this->lazies['names'][$key] = $value;
        } else {
            return $this->vars[$key] = $value;
        }
    }

    protected function param() {
        $num_args = func_num_args();
        if ($num_args == 1) {
            $args = func_get_arg(0);
            if (is_array($args)) {
                $names = $args;
                return $this->paramMulti($names);
            } elseif (is_string($args)) {
                $name = $args;
                return $this->_param($name, null);
            }
        } elseif ($num_args == 2) {
            $name = func_get_arg(0);
            $default = func_get_arg(1);
            return $this->_param($name, $default);
        } else {
            return $_REQUEST;
        }
    }
    
    protected function paramMulti($names) {
        $ret = array();
        foreach ($names as $a => $b) {
            if (is_int($a)) {
                $name = $b;
                $default = null;
            } else {
                $name = $a;
                $default = $b;
            }
            $ret[$name] = $this->_param($name, $default);
        }
        return $ret;
    }

    public function paramFile($name) {
        if (isset($_FILES[$name])) {
            return $_FILES[$name];
        }
        return null;
    }
    public function view($filename,$data){
        extract($data);
        $path=APPPATH.'/view/'.$filename.'.php';
        if(!file_exists($path))
            exit('error:no find the '.$path.' page');
        include_once(APPPATH.'/view/'.$filename.'.php');
    }

   
  
	public function loadModel($modelname) {

    spl_autoload_register(function ($modelname) {

            $fileName = str_replace('\\', '/', $modelname) . '.php';
            if (preg_match('/Model$/', $modelname)) {
                $modelFile = APPPATH .'/models/'.$fileName;
                require $modelFile;
            }
        });
        return new $modelname($this->container);
	}


    
}
