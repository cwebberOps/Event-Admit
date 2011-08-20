<?php
require_once 'lib/PHPExcel.php';
require_once 'lib/PHPExcel/IOFactory.php';
require_once 'lib/class.db.php';

error_reporting(0);
//date_default_timezone_set('America/Los_Angeles');
$objPHPExcel = new PHPExcel();
$sheet = $objPHPExcel->setActiveSheetIndex(0);

$sheet->setCellValue('A1', 'Student ID');
$sheet->setCellValue('B1', 'Last Name');
$sheet->setCellValue('C1', 'First Name');
$sheet->setCellValue('D1', 'Status');
$sheet->setCellValue('E1', 'Timestamp');

$db = new db();
$i = 2;

$log = $db->query("SELECT enrolled.first, enrolled.last, log.sid, log.timestamp, log.status FROM enrolled, log WHERE log.sid = enrolled.sid;");
while ($row = $db->row($log)) {
    $sheet->setCellValue('A' . $i, $row['sid']);
    $sheet->setCellValue('B' . $i, $row['last']);
    $sheet->setCellValue('C' . $i, $row['first']);
    $sheet->setCellValue('D' . $i, $row['status']);
    $sheet->setCellValue('E' . $i, $row['timestamp']);
    $i++;
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="report.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');


?>
