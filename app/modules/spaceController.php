<?php
if( !defined("ABSPATH") )
{
    http_response_code(404);
    exit;
}

class spaceController {
    private $space_instance,
            $content;
    
    public function __construct()
    {
        
    }
    
    public function set($space)
    {
        if( $space instanceof Space )
        {
            $this->space_instance =& $space;
        }
    }
    
    public function getAccess()
    {
        return $this->space_instance->Access(null);
    }
    
    public function getLayout()
    {
        return $this->space_instance->Layout(null);
    }
    
    public function getHeader()
    {
        return $this->space_instance->headerName(null);
    }
    
    public function getTitle()
    {
        return $this->space_instance->titleName(null);
    }
    
    public function getViews()
    {
        return $this->space_instance->viewCounter();
    }
    
    public function getFile()
    {
        return $this->space_instance->contentFile(null);
    }
    
    public function pushContent($html)
    {
        $this->content .= $html;
    }
    
    public function popContent()
    {
        $buff = $this->content;
        $this->content = "";
        return $buff;
    }
}
