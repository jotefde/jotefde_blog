<?php

if( !defined("ABSPATH") )
{
    http_response_code(404);
    exit;
}

class Image {
    private $src,
            $base64,
            $MIME;
    
    public function __construct($_src)
    {
        if( file_exists($_src) )
        {
            $this->src = $_src;
            $this->MIME = pathinfo($_src, PATHINFO_EXTENSION);
            $data = file_get_contents($_src);
            $this->base64 = "data:image/" . $this->MIME . ";base64," . base64_encode($data);
        }
    }
    
    public function get()
    {
        return $this->base64;
    }
}
