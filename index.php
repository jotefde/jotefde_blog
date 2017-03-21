<?php
session_start();
define("ABSPATH", __DIR__);

require_once ABSPATH."/app/core.php";
Jotefde::App();

if( Jotefde::isReady() )
{
    Jotefde::App()->setMode( Jotefde::FULL_MODE );
    Jotefde::App()->modules();
    if( Jotefde::App()->start() )
    {
        // Show the site
    } 
    else
    {
        http_response_code( Jotefde::getHttpCode() );
        printf("There was an error loading the page:<strong> %s</strong>", Jotefde::getMessage() );
        exit;
    }
} else
{
    http_response_code( Jotefde::getHttpCode() );
    printf("There was an error loading the page:<strong> %s</strong>", Jotefde::getMessage() );
    exit;
}

