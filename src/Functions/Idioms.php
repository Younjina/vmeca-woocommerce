<?php

namespace Ivy\Vmeca\Functions;

function getPrefix($forFrontEnd = false)
{
    return $forFrontEnd ? 'vmeca-' : 'vmeca_';
    //vmeca- : front-end
    //vmeca_ : back-end
}

function prefixed($string, $forFrontend = false)
{
    return getPrefix($forFrontend) . $string;
}

/**
 * 페이지네이션 생성 함수
 * [int]$pages : 총 페이지수
 * [int]$paged : 현재 페이지
 * [int]$range : 한 화면에 출력할 행 수
 */
function pagination($pages = '', $paged = '', $range = 4)
{
    $showitems = ($range * 2) + 1;

    if (empty($paged))
        $paged = 1;

    if ($pages == '') {
        if (!$pages) {
            $pages = 1;
        }
    }
    if (1 != $pages) {
        echo "<div class=\"pagination clearfix mypage-pagination2\">";
        // <span>Page ".$paged." of ".$pages."</span>";
        // if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
        // <a class="pagination-prev" href="http://type-a.oxion.co.kr/lecture-info/">	<span class="page-prev"></span>	<span class="page-text">이전</span></a>
        if ($paged > 1 && $showitems < $pages) echo "<a class=\"pagination-prev\" href='" . get_pagenum_link($paged - 1) . "'>	<span class=\"page-prev\"></span>	<span class=\"page-text\">이전</span></a>";

        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                echo ($paged == $i) ? "<span class=\"current\">" . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class=\"inactive\">" . $i . "</a>";
            }
        }

        if ($paged < $pages && $showitems < $pages)
            echo "<a class=\"pagination-next\" href=\"" . get_pagenum_link($paged + 1) . "\"><span class=\"page-text\">다음</span><span class=\"page-next\"></span></a>";
        // if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages)
        // 	echo "<a class=\"pagination-next\" href='".get_pagenum_link($pages)."'><span class=\"page-text\">마지막</span> &raquo;</a>";

        echo "</div>\n";
    }
} // end function pagination
