<?php

if( !defined("ABSPATH") )
{
    http_response_code(404);
    exit;
}

class Space {
    private $spaceName,
            $userAccess,
            $layout,
            $titleName,
            $headerName,
            $contentFile,
            $viewCounter;
    
    public function __construct($_name)
    {
        $this->spaceName = $_name;
        $viewsQuery = Jotefde::DB()->query("SELECT count(*) FROM spaceViews WHERE space_name='$_name'");
        $fetch = $viewsQuery->fetch(PDO::FETCH_NUM);
        $this->viewCounter = $fetch[0];
    }
    
    public function Access( $acc = null )
    {
        switch( $acc )
        {
            case Jotefde::USER_ACCESS:
                $this->userAccess = Jotefde::USER_ACCESS;
                break;
            
            case Jotefde::MOD_ACCESS:
                $this->userAccess = Jotefde::MOD_ACCESS;
                break;
            
            case Jotefde::ADMIN_ACCESS:
                $this->userAccess = Jotefde::ADMIN_ACCESS;
                break;
            
            case null:
                if( !empty($this->userAccess) )
                {
                    return $this->userAccess;
                }
                break;
                
            default:
                $this->userAccess = Jotefde::GUEST_ACCESS;
        }
    }
    
    public function Layout( $lay = null )
    {
        if( $path = Jotefde::App()->getLayout( $lay ) )
        {
            $this->layout = $lay;
            return $path;
        }
        elseif( !empty($this->layout) )
        {
            return $this->layout;
        }
        trigger_error("Layout '$lay' doesn't exists", E_USER_ERROR);
    }
    
    public function titleName($tname = null)
    {
        if( is_string($tname) )
        {
            $this->titleName = $tname;
        }
        return $this->titleName;
    }
       
    public function headerName($hname = null)
    {
        if( is_string($hname) )
        {
            $this->headerName = $hname;
        }
        return $this->headerName;
    }
    
    public function contentFile($path = null)
    {
        if( @file_exists($path) )
        {
            $this->contentFile = $path;
            return $path;
        }
        elseif( !empty($this->contentFile) )
        {
            return $this->contentFile;
        }
        trigger_error("Space content doesn't exists in '$path'", E_USER_ERROR);
    }
    
    public function viewCounter()
    {
        return $this->viewCounter;
    }
    
    public function resetViews()
    {
        $s = $this->spaceName;
        if( $result = Jotefde::DB()->query("DELETE * FROM spaceViews WHERE space_name='$s") )
        {
            $this->viewCounter = 0;
            return true;
        }
        return false;
    }
}
