<?php
if( !defined("ABSPATH") )
{
    http_response_code(404);
    exit;
}

class Content {
    
    public static function push($code)
    {
        Jotefde::spaceController()->pushContent($code);
    }
    
    public static function newLine($count=1)
    {
        for($i=0; $i < $count; $i++)
        {
            self::push("<br/>");
        }
    }
    
    public static function H($size, $text, $options = [])
    {
        $mopt = self::matchOptions($options);
        $code = "<h$size".$mopt.">$text</h$size>";
        self::push($code);
    }
    
    public static function A($text, $link, $options = [])
    {
        $mopt = self::matchOptions($options);
        $code = "<a href=\"$link\"$mopt>$text</a>";
        self::push($code);
    }
    
    public static function P($text, $options = [])
    {
        $mopt = self::matchOptions($options);
        $code = "<p".$mopt.">$text</p>";
        self::push($code);
    }
    
    public static function B($text, $options = [])
    {
        $mopt = self::matchOptions($options);
        $code = "<b".$mopt.">$text</b>";
        self::push($code);
    }
    
    public static function I($text, $options = [])
    {
        $mopt = self::matchOptions($options);
        $code = "<i".$mopt.">$text</i>";
        self::push($code);
    }
    
    public static function Table($arr, $options = [])
    {
        $opt = self::matchOptions($options);
        $cols = [];
        
        $code = "<table$opt>";
        $code .= "<tr>";
        
        foreach( $arr as $key=>$row )
        {
            $code .= "<th>$key</th>";
            for( $i = 0; $i < count($row); $i++ )
            {
                $cols[$i][] = $row[$i];
            }
        }
        $code .= "</tr>";
        for($j = 0; $j < count($cols); $j++)
        {
            $code .= "<tr>";
            for($k = 0; $k < count($cols[$j]); $k++)
            {
                $code .= "<td>".$cols[$j][$k]."</td>";
            }
            $code .= "</tr>";
        }
        $code .= "</table>";
        self::push($code);
    }
    
    public static function HTable($arr, $options = [])
    {
        $opt = self::matchOptions($options);
        $code = "<table$opt>";
        foreach($arr as $key=>$values)
        {
            $code .= "<tr>";
                $code .= "<th>$key</th>";
                for($i = 0; $i < count($values);$i++)
                {
                    $code .= "<td>".$values[$i]."</td>";
                }
            $code .= "</tr>";
        }
        $code .= "</table>";
        self::push($code);
    }
    
    public static function Img($src, $alt, $options = [])
    {
        $mopt = self::matchOptions($options);
        $code = "<img src=\"$src\" alt=\"$alt\"$mopt/>";
        self::push($code);
    }
    
    public static function openDiv($id = "", $classes = "", $options = [])
    {
        $mopt = self::matchOptions($options);
        $code = "<div".
                ( (!empty($id)) ? ' id="'.$id.'"' : '' ).
                ( (!empty($classes)) ? ' class="'.$classes.'"' : '' )."$mopt>"; 
        self::push($code);
    }
    
    public static function closeDiv()
    {
        self::push("</div>");
    }

    private static function matchOptions($arr)
    {
        $str = '';
        foreach($arr as $key=>$value)
        {
            $str .= ' '.$key.'="'.$value.'"';
        }
        return $str;
    }
}

