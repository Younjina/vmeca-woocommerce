<?php
/*C:\Users\ivy\Documents\wordpress\vmeca\public_html\wp-content\plugins\vmeca-woocommerce\src\test\idea.php*/

$compare_str = 'o'; //비교 문자열 
$al_ary      = array('B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ', 'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK');


$f_al_ary = array('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$b_al_ary = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
//위 두 배열은 엑셀 열 조합 값들이 들어있는 배열입니다.
$meta_field = array('cup_diameter', 'cup_shape', 'material', 'hardness', 'fitting_size', 'fitting_type', 'thread_type', 'fitting_option', 'etc');
//메타 필드 값 배열입니다.
//26

$j = 0;
for ($i = 1; $i < 115; $i++) {  //115 == count($sheetData[3])
    if (($i % 26) == 0) {
        $j++;
    }
    echo '<br/>' . $i . ' // 현재 열 : ' . $f_al_ary[$j] . $b_al_ary[$i % 26] . ' //j : ' . $j . '<br/>';


    /*for ($j=0; $j < count($meta_field) ; $j++) {
        if( $sheetData[2][$f_al_ary[$j].$b_al_ary[$i%26]] == $meta_field[$j] ){ //필드명이 같으면
            if ( ! empty($meta_field[$j]) ) {
                $meta_field[$j][1] = $i+1;
            }else{
                $meta_field[$j] = array( $i+1 );
            }
        }
    }*/ //end for

}

/*if($meta_field[$i][0]<= $num && $num <= $meta_field[$i][1]){
	update_post_meta($post_id, $key, $sheetData[][] );
}*/

$sub_cnt = 0;

$field = array('B'  => '1.5 mm',
               'C'  => '2  mm',
               'D'  => '2.5 mm',
               'E'  => '3  mm',
               'F'  => '4  mm',
               'G'  => '5 mm',
               'H'  => '6  mm',
               'I'  => '8  mm',
               'J'  => '10 mm',
               'K'  => '12 mm',
               'L'  => '13 mm',
               'M'  => '15 mm',
               'N'  => '16 mm',
               'O'  => '17 mm',
               'P'  => '20 mm',
               'Q'  => '22 mm',
               'R'  => '23 mm',
               'S'  => '25 mm',
               'T'  => '27 mm',
               'U'  => '28 mm',
               'V'  => '30 mm',
               'W'  => '32 mm',
               'X'  => '34 mm',
               'Y'  => '35 mm',
               'Z'  => '40 mm',
               'AA' => '45 mm',
               'AB' => '48 mm',
               'AC' => '50 mm',
               'AD' => '55 mm',
               'AE' => '60 mm',
               'AF' => '65 mm',
               'AG' => '68 mm',
               'AH' => '70 mm',
               'AI' => '72 mm',
               'AJ' => '75 mm',
               'AK' => '80 mm',
               'AL' => '85 mm',
               'AM' => '90 mm',
               'AN' => '100 mm',
               'AO' => '110 mm',
               'AP' => '125 mm',
               'AQ' => '150 mm',
               'AR' => '200 mm',
               'AS' => '250 mm',
               'AT' => '300 mm',
               'AU' => '400 mm',
               'AV' => '4x10 mm',
               'AW' => '4x20 mm',
               'AX' => '6x10 mm',
               'AY' => '6x20 mm',
               'AZ' => '8x20 mm',
               'BA' => '8x30 mm',
               'BB' => '10x30 mm',
               'BC' => '15x45 mm',
               'BD' => '20x60 mm',
               'BE' => '11x23 mm',
               'BF' => '15x45 mm',
               'BG' => '20 x 60 mm',
               'BH' => '30x60 mm',
               'BI' => '30x80 mm',
               'BJ' => '35x90 mm',
               'BK' => '35x110 mm',
               'BL' => '40x80 mm',
               'BM' => '55x110 mm',
               'BN' => '60x140 mm',
               'BO' => '60x180 mm',
               'BP' => 'Bellows',
               'BQ' => 'Deep',
               'BR' => 'Deep Concave',
               'BS' => 'Flat',
               'BT' => 'Flat Concave',
               'BU' => 'Multi-Bellows',
               'BV' => 'Oval',
               'BW' => 'Dual-Lip',
               'BX' => 'Sponge',
               'BY' => 'Nitrile',
               'BZ' => 'Silicone',
               'CA' => 'White Silicone',
               'CB' => 'Conductive',
               'CC' => 'Urethane',
               'CD' => 'Mark Free',
               'CE' => 'Polyurethane',
               'CF' => 'High Temp Silicone',
               'CG' => 'FDA Silicone',
               'CH' => 'EPDM',
               'CI' => '30',
               'CJ' => '50',
               'CK' => '60',
               'CL' => 'None',
               'CM' => '1/8',
               'CN' => '1/4',
               'CO' => '3/8',
               'CP' => '1/2',
               'CQ' => '3/4',
               'CR' => 'M5-1/8',
               'CS' => '1/8-3/8',
               'CT' => 'M2.5',
               'CU' => 'M5',
               'CV' => 'M6',
               'CW' => 'M8',
               'CX' => 'M10',
               'CY' => 'M16',
               'CZ' => '19 mm',
               'DA' => 'Female',
               'DB' => 'Male',
               'DC' => 'Male-Female',
               'DD' => 'T-Slot',
               'DE' => 'G-Thread',
               'DF' => 'NPSF-Thread',
               'DG' => 'NPT-Thread',
               'DH' => 'Mesh Filter',
               'DI' => 'Efficiency Valve',
               'DJ' => 'Button Valve',
               'DK' => 'Disc Filter');

$cup_diameter = array('B'  => '1.5 mm',
                      'C'  => '2  mm',
                      'D'  => '2.5 mm',
                      'E'  => '3  mm',
                      'F'  => '4  mm',
                      'G'  => '5 mm',
                      'H'  => '6  mm',
                      'I'  => '8  mm',
                      'J'  => '10 mm',
                      'K'  => '12 mm',
                      'L'  => '13 mm',
                      'M'  => '15 mm',
                      'N'  => '16 mm',
                      'O'  => '17 mm',
                      'P'  => '20 mm',
                      'Q'  => '22 mm',
                      'R'  => '23 mm',
                      'S'  => '25 mm',
                      'T'  => '27 mm',
                      'U'  => '28 mm',
                      'V'  => '30 mm',
                      'W'  => '32 mm',
                      'X'  => '34 mm',
                      'Y'  => '35 mm',
                      'Z'  => '40 mm',
                      'AA' => '45 mm',
                      'AB' => '48 mm',
                      'AC' => '50 mm',
                      'AD' => '55 mm',
                      'AE' => '60 mm',
                      'AF' => '65 mm',
                      'AG' => '68 mm',
                      'AH' => '70 mm',
                      'AI' => '72 mm',
                      'AJ' => '75 mm',
                      'AK' => '80 mm',
                      'AL' => '85 mm',
                      'AM' => '90 mm',
                      'AN' => '100 mm',
                      'AO' => '110 mm',
                      'AP' => '125 mm',
                      'AQ' => '150 mm',
                      'AR' => '200 mm',
                      'AS' => '250 mm',
                      'AT' => '300 mm',
                      'AU' => '400 mm',
                      'AV' => '4x10 mm',
                      'AW' => '4x20 mm',
                      'AX' => '6x10 mm',
                      'AY' => '6x20 mm',
                      'AZ' => '8x20 mm',
                      'BA' => '8x30 mm',
                      'BB' => '10x30 mm',
                      'BC' => '15x45 mm',
                      'BD' => '20x60 mm',
                      'BE' => '11x23 mm',
                      'BF' => '15x45 mm',
                      'BG' => '20 x 60 mm',
                      'BH' => '30x60 mm',
                      'BI' => '30x80 mm',
                      'BJ' => '35x90 mm',
                      'BK' => '35x110 mm',
                      'BL' => '40x80 mm',
                      'BM' => '55x110 mm',
                      'BN' => '60x140 mm',
                      'BO' => '60x180 mm');

$cup_shape = array('BP' => 'Bellows',
                   'BQ' => 'Deep',
                   'BR' => 'Deep Concave',
                   'BS' => 'Flat',
                   'BT' => 'Flat Concave',
                   'BU' => 'Multi-Bellows',
                   'BV' => 'Oval',
                   'BW' => 'Dual-Lip',
                   'BX' => 'Sponge');

$material = array('BY' => 'Nitrile',
                  'BZ' => 'Silicone',
                  'CA' => 'White Silicone',
                  'CB' => 'Conductive',
                  'CC' => 'Urethane',
                  'CD' => 'Mark Free',
                  'CE' => 'Polyurethane',
                  'CF' => 'High Temp Silicone',
                  'CG' => 'FDA Silicone',
                  'CH' => 'EPDM');

$hardness = array('CI' => '30', 'CJ' => '50', 'CK' => '60');

$fitting_size = array('CL' => 'None',
                      'CM' => '1/8',
                      'CN' => '1/4',
                      'CO' => '3/8',
                      'CP' => '1/2',
                      'CQ' => '3/4',
                      'CR' => 'M5-1/8',
                      'CS' => '1/8-3/8',
                      'CT' => 'M2.5',
                      'CU' => 'M5',
                      'CV' => 'M6',
                      'CW' => 'M8',
                      'CX' => 'M10',
                      'CY' => 'M16',
                      'CZ' => '19 mm');

$fitting_type = array('DA' => 'Female',
                      'DB' => 'Male',
                      'DC' => 'Male-Female',
                      'DD' => 'T-Slot');

$thread_type = array('DE' => 'G-Thread', 'DF' => 'NPSF-Thread', 'DG' => 'NPT-Thread');

$fitting_option = array('DH' => 'Mesh Filter', 'DI' => 'Efficiency Valve', 'DJ' => 'Button Valve');

$etc = array('DK' => 'Disc Filter');

/*for ($i=0; $i <= count($sheetData); $i++) { 
	# code...save the post_title, post_category
	for ($j=0; $j < count($al_ary) ; $j++) { 
		# code...
		if( $sheetData[$i][$al_ary[$j]] == $compare_str ){
			update_post_meta( $post_id, '메타 필드 명', $field[$al_ary[$j]] );
		} //end if
	} //end for j
} //end for i*/