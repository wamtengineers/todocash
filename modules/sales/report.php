<?php
date_default_timezone_set('Asia/karachi');
include('vendor/autoload.php');

$helper = new \PhpOffice\PhpSpreadsheet\Helper\Sample();
if (!defined('EOL')) {
    define('EOL', $helper->isCli() ? PHP_EOL : '<br />');
}
// Return to the caller script when runs by CLI
/*if ($helper->isCli()) {
	echo 'Hello';
    return;
}*/

// Create new Spreadsheet object
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
// Set document properties
$spreadsheet->getProperties()
        ->setCreator($site_title)
        ->setLastModifiedBy($site_title)
        ->setTitle('Sales List')
		->setSubject('List of All Available Sales')
        ->setDescription('')
        ->setKeywords('')
        ->setCategory('');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Sales List');
$spreadsheet->setActiveSheetIndex(0)->mergeCells("A1:G4");
$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:G4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVERTICAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:G4')->getFont()->setBold(true)->setSize(20);
// Add some data
$spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A5', 'S.No')
		->setCellValue('B5', 'Date')
		->setCellValue('C5', 'Customer Name')
		->setCellValue('D5', 'Total Items')
		->setCellValue('E5', 'Total Price')
		->setCellValue('F5', 'Discount')
		->setCellValue('G5', 'Net Price')
		->setCellValue('D10', 'Total');
$extra='';
if(isset($_SESSION["sales"]["list"]["date_from"]) && !empty($_SESSION["sales"]["list"]["date_from"])){
	$date_from=$_SESSION["sales"]["list"]["date_from"];
	$extra.=" and date>='".datetime_dbconvert($date_from)."'";
}
if(isset($_SESSION["sales"]["list"]["date_to"]) && !empty($_SESSION["sales"]["list"]["date_to"])){
	$date_to=$_SESSION["sales"]["list"]["date_to"];
	$extra.=" and date<'".datetime_dbconvert($date_to)."'";
}
if(isset($_SESSION["sales"]["list"]["q"]) && !empty($_SESSION["sales"]["list"]["q"])){
	$q=$_SESSION["sales"]["list"]["q"];
	$extra.=" and customer_name like '%".$q."%'";
}

$order_by = "customer_name";
$order = "asc";
if( isset( $_SESSION["sales"]["list"]["order_by"] ) ){
	$order_by = $_SESSION["sales"]["list"]["order_by"];
}
if( isset( $_SESSION["sales"]["list"]["order"] ) ){
	$order = $_SESSION["sales"]["list"]["order"];
}
$orderby = $order_by." ".$order;
$sql="select * from sales where 1 $extra order by $orderby";
$rs=doquery($sql, $dblink);
if(numrows($rs)>0){
	$sn=6;
	$total_items = $total_price = $discount = $net_price = 0;
	while($r=dofetch($rs)){
		$total_items += $r["total_items"];
		$total_price += $r["total_price"];
		$discount += $r["discount"];
		$net_price += $r["net_price"];
		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A'.$sn, $sn-5)
			->setCellValue('B'.$sn, datetime_convert($r["date"]))
			->setCellValue('C'.$sn, unslash($r["customer_name"]))
			->setCellValue('D'.$sn, unslash($r["total_items"]))
			->setCellValue('E'.$sn, $r["total_price"])
			->setCellValue('F'.$sn, $r["discount"])
			->setCellValue('G'.$sn, $r["net_price"]);
		$spreadsheet->setActiveSheetIndex(0)->getStyle("A".$sn.":G".$sn."")->applyFromArray(
			array(
				'borders' => array(
					'allborders' => array(
						'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
						'color' => array('rgb' => '000000')
					)
				)
			)
		);
		$sn++;
	}
}	
$spreadsheet->setActiveSheetIndex(0)->getStyle("A5:G5")->applyFromArray(
    array(
        'borders' => array(
            'allborders' => array(
                'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => array('rgb' => '000000')
            )
        )
    )
);
$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('A'.$sn, "Total");
$spreadsheet->setActiveSheetIndex(0)->mergeCells("A".$sn.":C".$sn);
$spreadsheet->setActiveSheetIndex(0)->getStyle("A".$sn.":C".$sn)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)->setVERTICAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('D'.$sn, $total_items);
$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('E'.$sn, $total_price);
$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('F'.$sn, $discount);
$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('G'.$sn, $net_price);
$spreadsheet->setActiveSheetIndex(0)->getStyle("A".$sn.":G".$sn)->getFont()->setBold(true);
$spreadsheet->setActiveSheetIndex(0)->getStyle("A".$sn.":G".$sn)->applyFromArray(
    array(
        'borders' => array(
            'allborders' => array(
                'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => array('rgb' => '000000')
            )
        )
    )
);
$spreadsheet->setActiveSheetIndex(0)->getStyle("A5:G5")->getFont()->setBold(true);
$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
// Save
// Redirect output to a client's web browser (Xls)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="SaleList"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header('Expires: '.date("D, d M Y H:i:s").' GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Excel2007');
$writer->save('php://output');
die;
?>