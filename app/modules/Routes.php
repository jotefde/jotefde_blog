<?php
if( !defined("ABSPATH") )
{
    http_response_code(404);
    exit;
}

class Routes {
    private $spaces,
            $get_vars;
    
    /*
     * Private section
     */
    
    private function init_gets()
    {
        $this->get_vars = $_GET;
        if( !isset( $_GET["space"] ) || $this->getSpace( $_GET["space"] ) == false )
        {
            $this->get_vars["space"] = Jotefde::App()->defaultPage();
        }
            
        
    }
    
    /*
     * Public section
     */
    
    public function __construct()
    {
        $this->spaces = [];
        $this->init_gets();
        //Jotefde::spaceController()->set( $this->get_vars["space"] );
    }
    
    public function pushRoute($_space)
    {
        if( $_space instanceof Space )
        {
            $this->spaces[] = $_space;
        }
    }
    
    public function getSpace( $spacename )
    {
        $spacebuff = null;
        for( $i = 0; $i < count($this->spaces); $i++ )
        {
            $spacebuff =& $this->spaces[$i];
            if( $spacename === $spacebuff->Name() )
            {
                return $this->spaces[$i];
            }
        }
        return false;
    }
    
    public function get($name)
    {
        if(array_key_exists($name, $this->get_vars) )
        {
            return $this->get_vars[$name];
        }
        return false;
    }
    
}
