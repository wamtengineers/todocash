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
        ->setTitle('Stock List')
        ->setSubject('List of All Available Stock')
        ->setDescription('')
        ->setKeywords('')
        ->setCategory('');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Stock List');
$spreadsheet->setActiveSheetIndex(0)->mergeCells("A1:E4");
$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:E4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVERTICAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$spreadsheet->setActiveSheetIndex(0)->getStyle('A1:E4')->getFont()->setBold(true)->setSize(20);
// Add some data
$spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A5', 'S.No')
		->setCellValue('B5', 'Name')
		->setCellValue('C5', 'Packing')
		->setCellValue('D5', 'Rate/Piece')
		->setCellValue('E5', 'Packet Price');
$extra='';
if(isset($_SESSION["items"]["list"]["q"]) && !empty($_SESSION["items"]["list"]["q"])){
	$q=$_SESSION["items"]["list"]["q"];
	$extra.=" and title like '%".$q."%'";
}
if(isset($_SESSION["items"]["list"]["type"]) && $_SESSION["items"]["list"]["type"]!=""){
	$type=$_SESSION["items"]["list"]["type"];
	$extra.=" and type like '%".$type."%'";
}
if(isset($_SESSION["items"]["list"]["stock"]) && $_SESSION["items"]["list"]["stock"]!=""){
	$stock=$_SESSION["items"]["list"]["stock"];
	if( $stock == "0" ){
		$extra.=" and quantity>10";
	}
	if( $stock == "1" ){
		$extra.=" and quantity<=10";
	}
	if( $stock == "2" ){
		$extra.=" and quantity=0";
	}
}
$order_by = "title";
$order = "asc";
if( isset( $_SESSION["items"]["list"]["order_by"] ) ){
	$order_by = $_SESSION["items"]["list"]["order_by"];
}
if( isset( $_SESSION["items"]["list"]["order"] ) ){
	$order = $_SESSION["items"]["list"]["order"];
}
$orderby = $order_by." ".$order;
$sql="select * from items where 1 $extra order by $orderby";
$rs=doquery($sql, $dblink);
if(numrows($rs)>0){
	$sn=6;
	while($r=dofetch($rs)){
		$unit = array();
		if( $r["type"] == 0 ) {
			$unit[]=1;
		}
		else{
			$children = doquery("select quantity from item_group where group_item_id = '".$r["id"]."'", $dblink);
			while($child=dofetch($children)){
				$unit[] = $child[ "quantity" ];
			}
		}
		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A'.$sn, $sn-5)
			->setCellValue('B'.$sn, unslash($r["title"]))
			->setCellValue('C'.$sn, implode("/", $unit))
			->setCellValue('D'.$sn, curr_format($r["unit_price"]/$unit[0]))
			->setCellValue('E'.$sn, curr_format($r["unit_price"]));
		$spreadsheet->setActiveSheetIndex(0)->getStyle("A".$sn.":E".$sn."")->applyFromArray(
			array(
				'borders' => array(
					'allborders' => array(
						'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
						'color' => array('rgb' => '000000')
					)
				)
			)
		);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('A'.$sn)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('C'.$sn)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('D'.$sn)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$spreadsheet->setActiveSheetIndex(0)->getStyle('E'.$sn)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
		$sn++;
	}
}
$spreadsheet->setActiveSheetIndex(0)->getStyle("A5:E5")->applyFromArray(
    array(
        'borders' => array(
            'allborders' => array(
                'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => array('rgb' => '000000')
            )
        )
    )
);
$spreadsheet->setActiveSheetIndex(0)->getStyle("A5:E5")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->setActiveSheetIndex(0)->getStyle("A5:E5")->getFont()->setBold(true);

$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
// Save
// Redirect output to a client's web browser (Xls)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="StockList"');
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