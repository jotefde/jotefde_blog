<?php
if( !defined("ABSPATH") )
{
    http_response_code(404);
    exit;
}
function push($_content)
{
    Jotefde::spaceController()->pushContent($_content);
}
function controller()
{
    return Jotefde::spaceController();
}
/*
 * Home space
 */

$home = new Space("Home");
$home->Access( Jotefde::GUEST_ACCESS );
$home->Layout( "Standard" );
$home->titleName( "Home - " . Jotefde::App()->getTitle() );
$home->headerName( "Welcome to the JoteFDe blog!" );
$home->contentFile( Jotefde::SPACES_PATH."/homeSpace.php" );
Jotefde::Routes()->pushRoute( $home );