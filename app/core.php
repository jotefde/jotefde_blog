<?php

if( !defined("ABSPATH") )
{
    http_response_code(404);
    exit;
}

final class Jotefde
{
    private static $instance = null;
    private static $message = "";
    private static $http_code = 200;
    
    private static $dbdriver;
    private static $routes;
    
    const FULL_MODE = 0,
          MINIMAL_MODE = 1;
    
    const GUEST_ACCESS = 153426,
          USER_ACCESS = 232654,
          MOD_ACCESS = 365464,
          ADMIN_ACCESS = 454365;
    
    const LAYOUTS_PATH = ABSPATH."/public/layouts",
          SPACES_PATH = ABSPATH."/app/spaces";
    
    public $mode;
    private $layouts;
    private $siteTitle;
    
    /*
     * Static methods section
     */
    public static function App()
    {
        if( !( self::$instance instanceof self) )
        {
            self::$instance = new self;
            
        }
        return self::$instance;
    }
    
    public static function isReady()
    {
        if( !defined("ABSPATH") )
        {
            self::message("Constant 'ABSPATH' is undefined.", 404);
            return false;
        }
        
        $db_connection_result = self::DB()->connect("localhost", "root", "skubi23", "jotefde_blog");
        if( $db_connection_result !== true )
        {
            self::message( $db_connection_result->getMessage() );
            return false;
        }
        
        return true;
    }
    
    public static function message($text, $code=200)
    {
        self::$message = $text;
        self::$http_code = $code;
    }
    
    public static function getMessage()
    {
        $buff = self::$message;
        self::$message = "";
        return $buff;
    }
    
    public static function getHttpCode()
    {
        $buff = self::$http_code;
        self::$http_code = 200;
        return $buff;
    }
    
    public static function DB()
    {
        if( !class_exists("DBDriver") ) return false;
                
        if( !( self::$dbdriver instanceof DBDriver) )
        {
            self::$dbdriver = new DBDriver();
        }
        return self::$dbdriver;
    }
    
    public static function Routes()
    {
        if( !class_exists("Routes") ) return false;
                
        if( !( self::$routes instanceof Routes) )
        {
            self::$routes = new Routes();
        }
        return self::$routes;
    }
    
    /*
     * Private methods section
     */
    private function __construct()
    {
        $this->required();
        $this->layouts = [];
    }
    
    private function required()
    {
        
        require_once ABSPATH.'/app/modules/DBDriver.php';
        require_once ABSPATH.'/app/modules/Routes.php';
    }
    
    /*
     * Public methods section
     */
    public function start()
    {
        if( Jotefde::DB() === false )
        {
            Jotefde::message("Cannot load 'DBDriver' module.");
            return false;
        }
        
        if( Jotefde::Routes() === false )
        {
            Jotefde::message("Cannot load 'Routes' module.");
            return false;
        }
        
        
        // Layouts
        $layoutsChecker = [];
        $layoutsChecker[] = $this->registerLayout("Standard", Jotefde::LAYOUTS_PATH."/standard");
        
        if(in_array(false, $layoutsChecker) )
        {
            return false;
        }
        
        //Init configs from database
        $configs = $this->initConfigs();
        $this->siteTitle = $configs["site_title"];
        
        
        return true;
    }
    
    private function initConfigs()
    {
        $configsQuery = Jotefde::DB()->query("SELECT * FROM configs");
        $configs = [];
        while( $row = $configsQuery->fetch(PDO::FETCH_ASSOC) )
        {
            if( !empty($row["options"]) )
            {
                $configs[$row["name"]] = [
                    "value" => $row["value"],
                    "options" => $row["options"]
                ];
            }
            else {
                $configs[$row["name"]] = $row["value"];
            }
        }
        return $configs;
    }
    
    public function setMode($_mode = self::FULL_MODE)
    {
        $this->mode = $_mode;
    }
    
    public function modules()
    {
    }
    
    public function registerLayout( $name, $path )
    {
        if(file_exists($path."/layout.php") )
        {
            if( !array_key_exists($name, $this->layouts) )
            {
                $this->layouts[$name] = $path;
                return true;
            }
        }
        Jotefde::message("Cannot load '$name' layout in '<i>$path</i>'.");
        return false;
    }
    
    public function getLayout($name)
    {
        if(array_key_exists($name, $this->layouts) )
        {
            return $this->layouts[$name];
        }
        return false;
    }
    
    public function defaultLayout()
    {
        return $this->layouts[0];
    }
    
    public function getTitle()
    {
        return $this->siteTitle;
    }

}