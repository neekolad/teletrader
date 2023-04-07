<?php


// Database connection

$dbc = @mysqli_connect('localhost', 'nikola', '123123', 'project_db');

// Check connection
if (!$dbc->connect_errno != 0) {
  echo $dbc->connect_error;
}


// Make query1 & query2

$q1 = "SELECT isin AS `VALUE`,
                ValidUntil
        FROM isin";


$q2 = "SELECT i.titleinfodataid AS `ID`,
                i.isin AS `ISIN`,
                t.titlename AS `TitleName`,
                t.currency AS `Currency`,
                t.emittentname AS `EmittentName`
        FROM isin i 
        JOIN titleinfodata t
        USING(titleinfodataid)
        WHERE i.ValidUntil IS NULL";



// Result of the query1

$r = $dbc->query($q1);


if (mysqli_num_rows($r) > 0) {
  
    while ($row = $r->fetch_assoc()) {
      $table['ISINS'][] = $row;
    }
}

unset($r);


// Adding result of query2

$r = $dbc->query($q2);

if (mysqli_num_rows($r) > 0) {
  
    while ($row = $r->fetch_assoc()) {
      $table['TITLEINFODATA'][] = $row;
    }
}



// Creating custom function to generate XML file

function arraytoXML($arr, &$xml)
{
    foreach($arr as $key => $value)
    {
        if(is_int($key))
        {
            $key = "Element" . $key;  //To avoid numeric tags like <0></0>
         
        }
        if(is_array($value))
        {
            $label = $xml->addChild($key);
            arrayToXml($value, $label);  //Adds nested elements.
        }
        else
        {
            $xml->addChild($key, $value);
        }
    }
}
 
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><REPORT></REPORT>');
arrayToXml($table, $xml);
$xml->asXML('output.xml');
$xml_string = $xml->saveXML();

print($xml_string);


$table_xml = arrayToXml($table, $xml);
$filename = "Report2" . ".xml";

header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-type: text/xml");

exit;


?>


<pre>
    <?php
        print_r($table);
    ?>
</pre>