<?php

/**
 * Setsuna : Autoloader
 *
 */

namespace Setsuna\Core;


class Loader
{
    /**
     * Registered classes.
     *
     * @var array
     */
    protected $classes = array();

    /**
     * Class instances.
     *
     * @var array
     */
    protected $instances = array();

    /**
     * Autoload directories.
     *
     * @var array
     */
    protected static $dirs = array();


    /*** Autoloading Functions ***/

    /**
     * Starts/stops autoloader.
     */
    public static function autoload($enabled = true, $dirs = array())
    {
        if ($enabled) {
            spl_autoload_register(array(__CLASS__, 'loadClass'));
        } else {
            spl_autoload_unregister(array(__CLASS__, 'loadClass'));
        }

        if (!empty($dirs)) {
            self::addDirectory($dirs);
        }
    }

    /**
     * Autoloads classes.
     *
     * @param string $class Class name
     */
    public static function loadClass($class)
    {

        $class_file = str_replace(array('\\', '_'), '/', $class) . '.php';

        if (preg_match('/[a-zA-Z]Controller$/', $class)) {
            $file = APPPATH . '/controllers/' . $class_file;

            require $file;
            return;
        }


        foreach (self::$dirs as $dir) {

            $file = $dir . '/' . $class_file;

            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }

    /*****************/
    public static $loadArr = array();

    public static function load($type, $class, $paramet = null)
    {

        $key = $type . '|' . $class.'|';
        if (isset(self::$loadArr[$key]))
            return self::$loadArr[$key];

        $filePath = APPPATH . '/' . $type . '/' . $class . '.php';
        if (!file_exists($filePath))
            return false;


        if ($type == 'config') {
            self::$loadArr[$key] = require $filePath;
        } else {
            require_once($filePath);
            if (is_string($paramet))
                self::$loadArr[$key] = new $class($paramet);
            elseif (is_array($paramet))
                self::$loadArr[$key] = call_user_func_array(array($class), $paramet);
            else
                self::$loadArr[$key] = new $class();
        }
        return self::$loadArr[$key];
    }


    /**
     * Adds a directory for autoloading classes.
     *
     * @param mixed $dir Directory path
     */
    public static function addDirectory($dir)
    {


        if (is_array($dir) || is_object($dir)) {

            foreach ($dir as $value) {
                self::addDirectory($value);
            }
        } else if (is_string($dir)) {
            if (!in_array($dir, self::$dirs)) self::$dirs[] = $dir;
        }

    }
}
