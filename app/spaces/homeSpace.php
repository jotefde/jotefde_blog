<?php
if( !defined("ABSPATH") )
{
    http_response_code(404);
    exit;
}

Jotefde::App()->setLayout( 
        controller()->getLayout()
        );

Content::H(3, controller()->getHeader(), ["class"=>"main-header"] );
Content::A("Typowy link", "http://google.pl", ["download"=>"true"]);

$table1 = [
    "#" => [1,2,3,4],
    "First Name" => ["Kuba", "Jacek", "Ania", "Joe"],
    "Name" => ["Frydryk", "Nowak", "Kowalska", "Doe"],
    "Age" => [18, 43, 23, 34]
];

Content::Table($table1, ["class"=>"table1"]);
Content::newLine(2);
Content::HTable($table1, ["class"=>"table2"]);

$img64 = new Image(ABSPATH."/public/images/elo.jpg");
Content::Img($img64->get(), "Elo obrazek");

Content::openDiv("MyDiv1", ".mydivs .container");
    Content::B("Dobry Div pierwszy!");
    Content::openDiv("MyDiv2", ".mydivs .container");
        Content::A("Dobry Div drugi!", "http://google.pl");
    Content::closeDiv();
    Content::P("Tu jeszcze paragraf !!!");
Content::closeDiv();
