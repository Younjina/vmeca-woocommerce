<?php

//include dirname(__FILE__, )
//echo dirname(__FILE, 5).'/wp-blog-header.php';

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

require(dirname(__FILE__, 5) . '/wp-load-test.php');
//require(dirname(__FILE__, 5).'/wp-blog-header.php');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
    ->setLastModifiedBy("Maarten Balliauw")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");

$orderby    = 'date';
$order      = 'DESC';
$show_posts = -1;
$filename   = 'location-' . date('ymd', time()) . '.xlsx';

$args        = array();
$common_args = array(
    'post_type'      => 'stores',
    'post_status'    => array('publish', 'private'),
    'orderby'        => $orderby,
    'order'          => $order,
    'posts_per_page' => $show_posts,
);

$args  = $common_args;
$posts = new WP_Query($args);

/*$cat_ary = array();
$cat_ary = explode(',', $_GET['cat']);*/

// Put Excel data
$data   = array('아이디', '분류', '취급점', '주소', '전화번호', '지도좌표(위도)', '지도좌표(경도)');
$al_ary = array('A', 'B', 'C', 'D', 'E', 'F', 'G');

for ($i = 0; $i < count($data); $i++) {
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($al_ary[$i] . '1', $data[$i]);
}

$save_it_id = 0;
$excel_cnt  = 2;
while ($posts->have_posts()) {
    $posts->the_post();

    $title   = get_the_title();
    $post_id = get_the_id();

    $cat        = '';
    $categories = get_the_terms($post_id, 'store_category');

    if (!empty($categories)) { //값이 있으면
        $cat = $categories[0]->name;
    } else {
        $cat = '';
    }
    $cat = '';
//        $cat = $cat_ary[$excel_cnt-2];

    $_mapdaum_address = get_post_meta($post_id, '_mapdaum_address', true);
    $_mapdaum_address = esc_html($_mapdaum_address);
    $_mapdaum_tel     = get_post_meta($post_id, '_mapdaum_tel', true);
    $_mapdaum_lat     = get_post_meta($post_id, '_mapdaum_lat', true); // 위도
    $_mapdaum_lng     = get_post_meta($post_id, '_mapdaum_lng', true); // 경도


    $post_id          = (isset($post_id)) ? $post_id : '';
    $cat              = (isset($cat)) ? $cat : '';
    $title            = (isset($title)) ? $title : '';
    $_mapdaum_address = (isset($_mapdaum_address)) ? $_mapdaum_address : '';
    $_mapdaum_tel     = (isset($_mapdaum_tel)) ? $_mapdaum_tel : '';
    $_mapdaum_lat     = (isset($_mapdaum_lat)) ? $_mapdaum_lat : '';
    $_mapdaum_lng     = (isset($_mapdaum_lng)) ? $_mapdaum_lng : '';

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $excel_cnt, $post_id)
        ->setCellValue('B' . $excel_cnt, $cat)
        ->setCellValue('C' . $excel_cnt, $title)
        ->setCellValue('D' . $excel_cnt, $_mapdaum_address)
        ->setCellValue('E' . $excel_cnt, $_mapdaum_tel)
        ->setCellValue('F' . $excel_cnt, $_mapdaum_lat)
        ->setCellValue('G' . $excel_cnt, $_mapdaum_lng);


    /*        $objPHPExcel->getActiveSheet()->SetCellValue( 'A'.$excel_cnt,  $post_id);
            $objPHPExcel->getActiveSheet()->SetCellValue( 'B'.$excel_cnt,  $cat);
            $objPHPExcel->getActiveSheet()->SetCellValue( 'C'.$excel_cnt,  $title);
            $objPHPExcel->getActiveSheet()->SetCellValue( 'D'.$excel_cnt,  $_mapdaum_address);
            $objPHPExcel->getActiveSheet()->SetCellValue( 'E'.$excel_cnt,  $_mapdaum_tel);
            $objPHPExcel->getActiveSheet()->SetCellValue( 'F'.$excel_cnt,  $_mapdaum_lat);
            $objPHPExcel->getActiveSheet()->SetCellValue( 'G'.$excel_cnt,  $_mapdaum_lng);*/

    /*echo $post_id . " : " . $cat . " : " . $title . " : " . $_mapdaum_address . " : " . $_mapdaum_tel . " : " . $_mapdaum_lat . " : " . $_mapdaum_lng;
      echo "<br/>";*/
    $excel_cnt++;
    $save_it_id++;
}//while


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('매장정보');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


/*print_r($objPHPExcel);
return false;*/
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

//echo "<meta content=\"application/vnd.ms-excel; charset=UTF-8\" name=\"Content-type\"> "; //한글 깨지지 않게 <<-- 한글 깨졌던 이유 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
//ob_end_flush();
exit;
?>