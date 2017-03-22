<?php

if( !defined("ABSPATH") )
{
    http_response_code(404);
    exit;
}

final class Jotefde
{
    private static $instance = null,
                   $message = "",
                   $http_code = 200;
    
    private static $dbdriver,
                   $routes,
                   $spaceController;
    
    const FULL_MODE = 0,
          MINIMAL_MODE = 1;
    
    const GUEST_ACCESS = 153426,
          USER_ACCESS = 232654,
          MOD_ACCESS = 365464,
          ADMIN_ACCESS = 454365;
    
    const LAYOUTS_PATH = ABSPATH."/public/layouts",
          SPACES_PATH = ABSPATH."/app/spaces";
    
    public $mode;
    
    private $layoutsList,
            $layout,
            $siteTitle,
            $defaultPage,
            $modulesLegit;
    
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
    
    public static function spaceController()
    {
        if( !class_exists("spaceController") ) return false;
                
        if( !( self::$spaceController instanceof spaceController) )
        {
            self::$spaceController = new spaceController();
        }
        return self::$spaceController;
    }
    
    /*
     * Private methods section
     */
    public function __construct()
    {
        $this->required();
        $this->layoutsList = [];
        
    }
    
    private function required()
    {
        /*
         * Modules
         */
        require_once ABSPATH.'/app/modules/DBDriver.php';
        require_once ABSPATH.'/app/modules/Routes.php';
        require_once ABSPATH.'/app/modules/spaceController.php';
        require_once ABSPATH.'/app/modules/Content.php';
        
        /*
         * Objects
         */
        require_once ABSPATH.'/app/objects/Space.php';
        require_once ABSPATH.'/app/objects/Image.php';
    }
    
    /*
     * Public methods section
     */
    public function start()
    {
        if( !$this->modulesLegit ) return false;
        
        // Layouts
        $layoutsChecker = [];
        $layoutsChecker[] = $this->registerLayout("Standard", "/standard");
        
        if(in_array(false, $layoutsChecker) )
        {
            return false;
        }
        $this->layout = $this->defaultLayout();
        require_once ABSPATH.'/app/spaces.php';
        
        return true;
    }
    
    private function initDBConfigs()
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
        if( Jotefde::DB() === false )
        {
            Jotefde::message("Cannot load 'DBDriver' module.");
            return false;
        }
        //Init configs from database
        $configs = $this->initDBConfigs();
        $this->siteTitle = $configs["site_title"];
        $this->defaultPage = $configs["default_page"];
        
        if( Jotefde::Routes() === false )
        {
            Jotefde::message("Cannot load 'Routes' module.");
            return false;
        }
        
        if( Jotefde::spaceController() === false )
        {
            Jotefde::message("Cannot load 'spaceController' module.");
            return false;
        }
        
        $this->modulesLegit = true;
    }
    
    public function registerLayout( $name, $path )
    {
        if(file_exists(Jotefde::LAYOUTS_PATH.$path."/layout.php") )
        {
            if( !array_key_exists($name, $this->layoutsList) )
            {
                $this->layoutsList[$name] = $path;
                return true;
            }
        }
        Jotefde::message("Cannot load '$name' layout in '<i>$path</i>'.");
        return false;
    }
    
    public function getLayout($name)
    {
        if(array_key_exists($name, $this->layoutsList) )
        {
            return $this->layoutsList[$name];
        }
        return false;
    }
    
    public function defaultLayout()
    {
        list($key, $value) = each($this->layoutsList);
        return $value;
    }
    
    public function getTitle()
    {
        return $this->siteTitle;
    }
    
    public function defaultPage()
    {
        return $this->defaultPage;
    }
    
    public function setLayout($name)
    {
        $path = $this->getLayout($name);
        if( $path )
        {
            $this->layout = $path;
            return true;
        }
        trigger_error("Layout '$name' doesn't exists", E_USER_WARNING);
        return false;
    }
    
    public function show()
    {
        include_once Jotefde::spaceController()->getFile();
        include_once Jotefde::LAYOUTS_PATH."/".$this->layout."/layout.php";
    }
}