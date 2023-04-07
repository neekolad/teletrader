<?PHP

include 'vendor/autoload.php';

// Load database conf
include 'mysqli_connect.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Make query

$q = "SELECT i.isin AS `ISIN`,
              t.titlename AS `TitleName`,
              t.currency AS `Currency`,
              t.emittentname AS `EmittentName`
      FROM isin i 
      JOIN titleinfodata t
      USING(titleinfodataid)
      WHERE i.ValidUntil IS NULL";


// Result of the query

$r = $dbc->query($q);

if (mysqli_num_rows($r) > 0) {
  
    while ($row = $r->fetch_assoc()) {
      $table[] = $row;
    }
}


// Make spreadsheet and select it

$file = new Spreadsheet();
$active_sheet = $file->getActiveSheet();


// Set column names

$active_sheet->setCellValue('A1', 'ISIN');
$active_sheet->setCellValue('B1', 'TitleName');
$active_sheet->setCellValue('C1', 'Currency');
$active_sheet->setCellValue('D1', 'EmittentName');

$active_sheet->getColumnDimension('A')->setAutoSize(true);
$active_sheet->getColumnDimension('B')->setAutoSize(true);
$active_sheet->getColumnDimension('C')->setAutoSize(true);
$active_sheet->getColumnDimension('D')->setAutoSize(true);


// Set column names to bold

$active_sheet->getStyle('A1:D1')->getFont()->setBold(true);


// Create filters
$active_sheet->setAutoFilter(
    $active_sheet->calculateWorksheetDimension()
);


// Apply filters to the whole table
$active_sheet->getAutoFilter()->setRangeToMaxRow();


$count = 2;


// Fill the table with data
foreach($table as $row)
{
$active_sheet->setCellValue('A' . $count, $row["ISIN"]);
$active_sheet->setCellValue('B' . $count, $row["TitleName"]);
$active_sheet->setCellValue('C' . $count, $row["Currency"]);
$active_sheet->setCellValue('D' . $count, $row["EmittentName"]);

$count = $count + 1;
}


// Create the file name

$filename = "Report1" . date('Ymd') . ".xlsx";
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file, "Xlsx");


// Set headers

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=\"$filename\"");


// Save file
$writer->save("php://output");

exit;
?>
