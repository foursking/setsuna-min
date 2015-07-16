<?php

namespace Setsuna\Http;

class Response
{
    
    public function json($data = array() , $end = false)
    {
        header('Content-type: application/json');
        echo(json_encode($data));

        if($end) {
            exit;
        }

    }

}
