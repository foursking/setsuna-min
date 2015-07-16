<?php


function socket_server_interflow($host = '' , $port = '' , $package = '' , Caluse $callback){

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create  socket\n"); // Socket resourse

$connection = socket_connect($socket, $host, $port) or die("Could not connet server\n");    //  连接  server

$write = socket_write($socket, $decodeClientAuth , strlen($package)) or die("Write failed\n"); // 数据传送 向服务器发送消息  


$response = get_need_length($socket , 4);


$length = (hexdec(bin2hex($response)));

$binaryData = get_need_length($socket , ($length - 4));

socket_close($socket);    //关闭socket 资源

return $callback($binaryData);

}



function get_need_length($socket, $needLength) {

	while ($needLength > 0) {
		$response = socket_read($socket , $needLength, PHP_BINARY_READ);
		$responseTotal .= $response;
		$resLength = strlen($response);
		$needLength -= $resLength;
	}

	return $responseTotal;
}


 function arrayLevel($arr){ 
            $al = array(0);
            function aL($arr,&$al,$level=0){
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

function demo(){

    echo __function__;

}
