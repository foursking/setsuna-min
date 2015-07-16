<?php

class Tcplogclient {
    static $log  = array();
    static $dir  = false;
    static $host = TCP_LOG_SERVER_HOST;
    static $port = TCP_LOG_SERVER_PORT;
    public static function setHost($host,$port) {
        self::$host = $host;
        self::$port = $port;
    }

    public static function record($file,$info) {
        self::$log[] = array('file'=>$file,'info'=>$info,'time'=>time());
    }

    public static function save($file='', $info='',$dir='') {
        $args = func_get_args();
        if (!in_array(func_num_args(),array(1,3))) {
            throw new Exception('params error');
        }
        if (func_num_args() == 1) {
            self::$dir = $args[0];
        } else {
            self::record($args[0], $args[1]);
            self::$dir = $args[2];
        }

        if (count(self::$log) < 1) {
            return true;
        }

        $data = array();
        foreach(self::$log as $row) {
            $data[] = array('filename' => self::$dir.'/'.$row['file'],'log' =>$row['info'],'time'=>time());
        }
        $msg  = json_encode($data);

        $socket_client = stream_socket_client('tcp://'.self::$host.':'.self::$port, $errno, $errstr, 1);
        stream_set_timeout($socket_client, 0, 100000);
        if (!$socket_client) {
            trigger_error("$errstr ($errno)", E_USER_NOTICE);
            return true;
        }
        fwrite($socket_client, $msg."\r\n");
        //@fread($socket_client, 1024);
        fclose($socket_client);

        //清空已经发送的日志
        self::$log = array();
        /*$socket     = socket_create(AF_INET, SOCK_STREAM, SOL_TCP); // 创建一个Socket
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO,array("sec"=>0.5, "usec"=>0));//接受超时
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO,array("sec"=>0.5, "usec"=>0));//发送超时
        if(!socket_connect($socket, self::$host, self::$port)) {//  连接
            trigger_error ( socket_strerror ( socket_last_error() ), E_USER_ERROR);
        }
        socket_write($socket, $msg."\r\n") ; // 数据传送 向服务器发送消息
        @socket_read($socket, 2048);
        socket_close($socket);*/

        return true;
    }
}
