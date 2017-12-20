<?php

namespace Ivy\Vmeca\Initiators\Board;

use Ivy\Mu\Initiators\AutoHookInitiator;
use function Ivy\Mu\Functions\selectTag;
use function Ivy\Vmeca\Functions\getMetafieldSuctionCup;
use function Ivy\Vmeca\Functions\matchSlugFunctionName; //action or filter or shortcode 등을 자동호출해줌

//상품 사용자 페이지 
class BoardProductInitiator extends AutoHookInitiator
{
    /**
     * suction-cup 모양 필터(select box) 화면에 출력
     */
    public function action_vmeca_suction_cup_shape_filter()
    {
        ?>
      <form name="shape_filter_list" id="shape_filter_list" method="GET" action="">
        <input type="hidden" name="shape_filter_list"/>
        <input type="hidden" name="shape" id="shape" value="<?= $GET['shape'] ?>"/>
        <ul class="suctioncupLP">
            <?php
            $cupShape = getMetafieldSuctionCup('cup_shape');
            for ($i = 0; $i < count($cupShape); $i++) :
                $shape = str_replace(' ', '-', $cupShape[$i]);
                ?>
              <li><a href="#" class="shape_filter" shape="<?= $shape ?>"><img alt=""
                                                                              src="/wp-content/uploads/2017/09/vmeca-cup-icon-0<?= $i + 1 ?>.png"
                                                                              width="64"
                                                                              height="64"> <?= $cupShape[$i] ?></a></li>
            <?php endfor; ?>
        </ul>
      </form>
      <script type="text/javascript">
          jQuery(document).ready(function ($) {
              $(".shape_filter").click(function () {
                  var shape = $(this).attr('shape');
                  if (shape != '') {
                      $("#shape").val(shape);
                  }
                  $("#shape_filter_list").submit();
                  return false;
              });
          });
      </script>
        <?php
    } //end action function action_vmeca_suction_cup_shape_filter

    /**
     * 필터(select box) 화면에 출력
     */
    public function action_vmeca_select_filter()
    {
        ?>
      <form name="product_list" id="product_list" method="GET" action="">
        <input type="hidden" id="product_list_filter" name="product_list_filter" value="VacuumPump"/>
        <table cellpadding="0" cellspacing="0" width="100%" class="suc-filter-table">
          <tbody>
          <?php
          $parent_id    = get_term(get_queried_object_id())->parent; //카테고리 아이디에 해당되는 슬러그
          $get_parent   = get_term($parent_id)->slug;
          $functionName = matchSlugFunctionName($get_parent); //슬러그에 해당되는 메타필드 함수 명

          $metafieldNameFunc = '\\Ivy\\Vmeca\\Functions\\getMetafieldName' . $functionName;
          $metafieldFunc     = '\\Ivy\\Vmeca\\Functions\\getMetafield' . $functionName;

          foreach ($metafieldFunc() as $key => $value) :
              if ($cnt % 3 == 0) : ?>
                <tr>
              <?php endif;
              $cnt++;

              $selectKey = $metafieldNameFunc($key);
              $options   = array();
              $selected  = ($_GET[$key] != '') ? $_GET[$key] : '';

              $options[''] = strtolower($selectKey);


              for ($i = 0; $i < count($value); $i++) :
                  $optionKey = str_replace(' ', '-', $value[$i]);
                  $mm        = '';

                  if ($key == 'cup_diameter') {
                      $optionValue = str_replace('-', ' ', $value[$i]);
                      $mm          = (ICL_LANGUAGE_CODE == 'us') ? ' inch' : ' mm'; //미국 - inch, others - mm
                  } else {
                      $optionValue = $value[$i];
                  }

                  if (strpos($optionValue, 'kPa') || $key == 'vacuum_level') {
                      $optionValue = explode('x', $optionValue);
                      $optionValue = implode('x', $optionValue) . 'kPa';
                  } elseif (strpos($optionValue, 'mm') || $key == 'cup_diameter') {
                      $optionValue = explode('x', $optionValue);
                      $optionValue = array_map('\\Ivy\\Vmeca\\Functions\\mmToinch', $optionValue);
                      $optionValue = implode('x', $optionValue) . $mm;
                  }

                  $options[$optionKey] = $optionValue;
              endfor;

              $select = selectTag(
                  $options, // array($key => $value,,)
                  $selected,
                  array('id' => $key, 'name' => '', 'class' => 'parameter-value form-control'),
                  array(),
                  false //echo
              );

              ?>
            <th><?= $selectKey ?>:</th>
            <td><?= $select ?></td>
              <?php if ($cnt > 1 && $cnt % 3 == 0) :
              ?>
            <tr/>
          <?php endif;
          endforeach;
          ?>
          <tr>
            <td colspan="6"><input type="button" id="reset" value="Reset"/></td>
          </tr>
          </tbody>
        </table>
      </form>
      <script type="text/javascript">
          jQuery(document).ready(function ($) {
              $(".parameter-value").change(function () {
                  var form_action = '';
                  $(".parameter-value").each(function (i, item) {
                      var id = item.id;
                      var val = $('#' + id).val();

                      if (val != '') {
                          $(this).attr('name', id);
                      }
                  });

                  $("#product_list").submit();
              });

              $("#reset").click(function () {
                  $(".parameter-value").val('');
                  $("#product_list").submit();
              }); //reset button click
          });
      </script>
        <?php
    } //end funciton action_vmeca_suction_cup_filter

    public function action_pre_get_posts($query)
    {
        if ($query->is_main_query()) :

            $parent_id    = get_term(get_queried_object_id())->parent; //카테고리 아이디에 해당되는 슬러그
            $get_parent   = get_term($parent_id)->slug;
            $functionName = matchSlugFunctionName($get_parent); //슬러그에 해당되는 메타필드 함수 명

            $metafieldNameFunc = '\\Ivy\\Vmeca\\Functions\\getMetafieldName' . $functionName; //메타필드 select 명

            /**
             * 상품 필터대로 쿼리문에 적용하기
             */
            if (isset($_GET['product_list_filter'])) {
                $metaQuery = array();
                foreach ($metafieldNameFunc() as $key => $value) {
                    if ($_GET[$key] != '') {
                        $metaQuery[] = array(
                            'key'     => trim($key),
                            'value'   => serialize(array($_GET[$key])),
                            'compare' => 'IN',
                        );
                    }
                }

                $query->set('meta_query', $metaQuery);
            }
        endif;
    } //end action function action_pre_get_posts


} //end class BoardProductInitiator
