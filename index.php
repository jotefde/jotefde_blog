<?php
session_start();
define("ABSPATH", __DIR__);
$site_url = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI'];
define("ABSURL", $site_url);

require_once ABSPATH."/app/core.php";
Jotefde::App();

if( Jotefde::isReady() )
{
    Jotefde::App()->setMode( Jotefde::FULL_MODE );
    Jotefde::App()->modules();
    if( Jotefde::App()->start() )
    {
        $space = Jotefde::Routes()->get("space");
        Jotefde::spaceController()->set(
                Jotefde::Routes()->getSpace($space)
                );
        Jotefde::App()->show();
    } 
} 

if( !empty($message = Jotefde::getMessage()) || ($code = Jotefde::getHttpCode()) != 200 )
{
    http_response_code( $code );
    printf("There was an error loading the page:<strong> %s</strong>", $message );
    exit;
}

