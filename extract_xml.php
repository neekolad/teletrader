<?php

// Load database conf
include 'mysqli_connect.php';

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

function arraytoXML($arr, &$xml, $parent)
{
    foreach($arr as $key => $value)
    {

        // Change output key depending on parent
        switch ($parent) {
            case "ISINS"        : $key = "ISIN";        break;
            case "TITLEINFODATA": $key = "TITLEINFO";   break;
        }

        // If value is array type
        if (is_array($value)) {   
            
            // Create nested element
            $label = $xml->addChild($key);

            // Add it to document
            arrayToXml($value, $label, $key);  
        }
        else {


            // Add element
            $xml->addChild($key, $value);
        }
    }
}
 
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><REPORT></REPORT>');
arrayToXml($table, $xml, "_root_");
$xml->asXML('output.xml');


// ovde su bili hederi
$xml_string = $xml->saveXML();
print($xml_string);
$filename = "Report2" . ".xml";
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-type: text/xml");

exit;


// XSC schema

$XSD = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<xs:schema xmlns:xs=\"http://www.w3.org/2001/XMLSchema\" xmlns:vc=\"http://www.w3.org/2007/XMLSchema-versioning\" elementFormDefault=\"qualified\" attributeFormDefault=\"unqualified\" vc:minVersion=\"1.1\">

<xs:simpleType name=\"ISIN\">
	<xs:restriction base=\"xs:string\">
		<xs:pattern value=\"[A-Z]{2}[0-9A-Z]{9}[0-9]{1}\"/>
	</xs:restriction>
</xs:simpleType>
<xs:simpleType name=\"Currency\">
	<xs:restriction base=\"xs:string\">
		<xs:pattern value=\"(([A-Z]){3})?\"/>
	</xs:restriction>
</xs:simpleType>
<xs:simpleType name=\"TITLEINFODATAID\">
	<xs:restriction base=\"xs:int\">
	</xs:restriction>
</xs:simpleType>

<xs:element name=\"REPORT\">
<xs:annotation>
	<xs:documentation>XML Report</xs:documentation>
</xs:annotation>
<xs:complexType>
	<xs:sequence>
		<xs:element name=\"ISINS\">
			<xs:complexType>
			<xs:sequence>
				<xs:element name=\"ISIN\" maxOccurs=\"unbounded\">
					<xs:complexType>
					<xs:sequence>
						<xs:element name=\"VALUE\" type=\"ISIN\"></xs:element>
						<xs:element name=\"VALIDUNTIL\" minOccurs=\"0\"></xs:element>
					</xs:sequence>
					</xs:complexType>
				</xs:element>
			</xs:sequence>
			</xs:complexType>
		</xs:element>
		<xs:element name=\"TITLEINFODATA\">
			<xs:complexType>
			<xs:sequence>
				<xs:element name=\"TITLEINFO\" maxOccurs=\"unbounded\">
					<xs:complexType>
					<xs:sequence>
						<xs:element name=\"ID\" type=\"TITLEINFODATAID\"></xs:element>
						<xs:element name=\"ISIN\" type=\"ISIN\"></xs:element>
						<xs:element name=\"TITLENAME\"></xs:element>
						<xs:element name=\"CURRENCY\" type=\"Currency\"></xs:element>
						<xs:element name=\"EMITTENTNAME\"></xs:element>
					</xs:sequence>
					</xs:complexType>
				</xs:element>
			</xs:sequence>
			</xs:complexType>
		</xs:element>
	</xs:sequence>
</xs:complexType>
</xs:element>
</xs:schema>";



// Load output.xml

$doc = new DOMDocument();
$doc->load('output.xml');



// Check if output matches the schema, if so -> allow download

if ($doc->schemaValidateSource($XSD)) {


    echo "This document is valid!\n";
} else {
    echo 'not working';
}


?>
