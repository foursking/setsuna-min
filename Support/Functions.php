<?php



if ( ! function_exists('is_really_writable'))
{
    function is_really_writable($file)
    {
        // If we're on a Unix server with safe_mode off we call is_writable
        if (DIRECTORY_SEPARATOR == '/' AND @ini_get("safe_mode") == false)
        {
            return is_writable($file);
        }

        // For windows servers and safe_mode "on" installations we'll actually
        // write a file then read it.  Bah...
        if (is_dir($file))
        {
            $file = rtrim($file, '/').'/'.md5(mt_rand(1,100).mt_rand(1,100));

            if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === false) {
                return false;
            }

            fclose($fp);
            @chmod($file, DIR_WRITE_MODE);
            @unlink($file);
            return true;
        }
        elseif ( ! is_file($file) OR ($fp = @fopen($file, FOPEN_WRITE_CREATE)) === false) {
            return false;
        }

        fclose($fp);
        return true;
    }
}



//判断变量是否为空  
//
//
//string 为空的例子 '' , 
//array 为空的例子 array()



function is_empty($varible = '')
{

    switch (gettype($varible)) {

    case 'string':

        $flag = ('' == $varible) ? true : false;
        break;


    case 'array':

        $flag = (0 == count($varible)) ? true : false;
        break;


    case 'NULL':

        $flag = true;
        break;

    default:

        $flag = false;
        break;
    }

    return $flag;

}


function P() {
    $args = func_get_args (); //获取多个参数

    echo '<div style="width:100%;text-align:left"><pre>';
    //多个参数循环输出

    $i = 1;

    foreach ( $args as $arg ) {
        if (is_array ( $arg ) || is_object ( $arg )) {
            echo PHP_EOL . PHP_EOL . " <font color='red'>ARGUMENT  {$i}========================================================</font>" . PHP_EOL . PHP_EOL . PHP_EOL;
            print_r ( $arg );
        } else {
            echo PHP_EOL . PHP_EOL . " <font color='red'>ARGUMENT  {$i}========================================================</font>" . PHP_EOL . PHP_EOL . PHP_EOL;
            var_dump ( $arg );
        }

        $i ++;
    }
    echo '</pre></div>';
}


function arrayLevel($arr)
{ 
    $al = array(0);
    function aL($arr,&$al,$level=0)
    {
        if(is_array($arr)){
            $level++;
            $al[] = $level;
            foreach($arr as $v){
                aL($v,$al,$level);
            }
        }
    }
    aL($arr,$al);
    return max($al);
}


function in_range($value, array $range)
{
    list($start, $end) = $range;
    return (($value >= $start) && ($value <= $end));
}





if ( ! function_exists('getClientIp'))
{

    function getClientIp()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if (!preg_match("#^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}#", $ip)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $ipArr = explode(',', $ip);
        return $ipArr[0];
    }

}


if ( ! function_exists('getEmailTable'))
{

    function getEmailTable($email = '')
    {
        $buffer = explode('@' ,  $email);

        return array_pop($buffer);

    }

}



if ( ! function_exists('getRandArrayValue'))
{

    function getRandArrayValue($array = '')
    {
        return $array[array_rand($array)];
    }

}
