<?php

namespace Ivy\Vmeca\Views\Admin\Metaboxes;

use Ivy\Mu\Views\Abstracts\AbstractPropertyMetaboxView;
use Ivy\Mu\Views\FieldWidgets\InputWidget;
use Ivy\Vmeca\Models\CustomPosts\AppliedModel;
use function Ivy\Mu\Functions\inputTag;
use function Ivy\Vmeca\Functions\prefixed;

/**
 * Class AppliedRelatedProductView
 *
 * @package Ivy\Vmeca\Views\Admin\Metaboxes
 */
final class AppliedRelatedProductView extends AbstractPropertyMetaboxView
{
    public function __construct($args = array())
    {
        if (isset($args['model'])) {
            $this->setModel($args['model']);
        } else {
            $this->setModel(new AppliedModel());
        }
    }

    public function getId(): string
    {
        return prefixed('applied-related-product', true);
    }

    public function getTitle(): string
    {
        return __('Select Related Product', 'vmeca');
    }

    public function getNonceAction()
    {
        return 'applied-related-product-action';
    }

    public function getNonceParam()
    {
        return prefixed('applied-related-product', 1); //vmeca-applied-related-product
    }

    /**
     * 관련 상품 메타박스 - 관련 상품 검색 필드
     */
    public function getTemplateContext($post)
    {
        $search_product = inputTag(
            array_merge(
                array(
                    'id'          => 'search_product',
                    'name'        => 'search_product',
                    'placeholder' => __('Enter search product title', 'vmeca'),
                    'style'       => 'width:30%;',
                )
            ),
            false
        ); //관련 상품 입력 필드 
        return array('form_table_header' => '<tr><th>Related Product Search</th><td>' . $search_product . '</td></tr><tr><td></td><td><div id="click_search_product" style="display:none;"></div></td></tr>');
    }

    /**
     * 관련 상품 메타박스 뷰
     */
    public function getFieldWidgets($post)
    {
        $nonce   = wp_create_nonce($this->getNonceParam());
        $model   = $this->getModel();
        $widgets = array();

        $widgets[] = new InputWidget(
            $model->getFieldSearchProductNumber(),
            array('objectId' => $post->ID,
                  'attrs'    => array(
                      'style' => 'width:50%;'))
        ); //관련 상품 아이디(,로 구분)

        $widgets[] = new InputWidget(
            $model->getFieldOtherProductUrl(),
            array('objectId' => $post->ID,
                  'attrs'    => array(
                      'style' => 'width:50%;'))
        ); //관련 상품 url

        $widgets[] = new InputWidget(
            $model->getFieldOtherProductTitle(),
            array('objectId' => $post->ID,
                  'attrs'    => array(
                      'style' => 'width:50%;'))
        ); //관련 상품 제목

        $widgets[] = new InputWidget(
            $model->getFieldOtherProductImage(),
            array(
                'attrs' => array(
                    'type' => 'file',
                ))
        ); //관련 상품 이미지 
        ?>
      <script type="text/javascript">
          /**
           * 관련 상품 아이디 정렬 함수
           * [int]product_id : 방금 입력받은 상품 아이디
           */
          function change_product_number(product_id) {
              var product_number = jQuery("#vmeca_search_product_number").val();
              product_number += product_id.toString();
              product_number = product_number.split(',');
              var result_number = '';
              var key = '';

              product_number = product_number.filter(function (item, i, a) { //중복된 수 거르기
                  return i == a.indexOf(item);
              });

              product_number.sort(function (a, b) {
                  return a - b; //정렬 - 오름차순
              });

              for (var i = 0; i < product_number.length; i++) {
                  result_number += product_number[i] + ',';
              }


              jQuery("#vmeca_search_product_number").val(result_number);
              jQuery("#vmeca_search_product_number").focus();
              return false;
          }

          jQuery(document).ready(function ($) {

              $("#search_product").change(function () { //관련 상품 검색한 경우
                  var search_product = $("#search_product").val();
                  var nonce = "<?php echo $nonce?>";

                  $("#click_search_product").css('display', 'none');
                  if (search_product != '') {
                      $.ajax({
                          type: "POST",
                          dataType: 'json',
                          url: "<?php echo admin_url('admin-ajax.php'); ?>",
                          data: {
                              'action': 'search_related_product',
                              'search_product': search_product,
                              'nonce': nonce,
                              'nonce_param': '<?=$this->getNonceParam()?>'
                          },
                          success: function (response) {
                              var result_html = '';
                              if (response.success) {
                                  $.each(response.data, function (i, item) {
                                      result_html += '<a onclick="change_product_number(' + item.ID + ')" style="margin-left:2%;">' + item.post_title + '</a><br/>';
                                  });
                                  $("#click_search_product").html('');
                                  if (result_html == '') {
                                      result_html += '검색된 상품이 없습니다.';
                                  }
                                  $("#click_search_product").html(result_html);
                                  $("#click_search_product").css('display', 'block');
                              }
                          },
                          error: function (r) {
                              alert(JSON.stringify(r));
                              alert("Failed");
                          }
                      });//end call ajax
                  }
              });
          });
      </script>
        <?php

        return $widgets;
    } //end function getFieldWidgets

} //end class AppliedRelatedProductView
