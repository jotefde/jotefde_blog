<?php
if( !defined("ABSPATH") )
{
    http_response_code(404);
    exit;
}

class Routes {
    private $spaces;
    
    public function __construct()
    {
        $this->spaces = [];
    }
    
    public function pushRoute($_space)
    {
        if( $_space instanceof Space )
        {
            $this->spaces[] = $_space;
        }
    }
}
