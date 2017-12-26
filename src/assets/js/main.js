jQuery(document).ready(function ($) {
    var select_1 = main.choiceSelect1;
    var select_2 = main.choiceSelect2;
    var select_3 = main.choiceSelect3;

    md_meta_field($('#category_parent_1').val(), 'custom_meta_field');
    md_category($('#category_parent_1').val(), 'category_parent_2', 'Select Parent Category');
    md_category($('#category_parent_2').val(), 'category_parent_3', 'Select Product Line');

    if (select_2 != '') {
        md_category(select_1, 'category_parent_2', 'Select Parent Category', select_2);
    }
    if (select_3 != '') {
        md_category(select_2, 'category_parent_3', 'Select Product Line', select_3); //3번째 셀렉트 박스 만드는 함수 호출
    }

    $('#category_parent_1').change(function () {
        $('#category_parent_3').val('').prop('selected', true);
        md_category($(this).val(), 'category_parent_2', 'Select Parent Category'); //2번째 셀렉트 박스 만드는 함수 호출
        md_meta_field($(this).val(), 'custom_meta_field');
    }); //change event

    $('#category_parent_2').change(function () {
        md_category($(this).val(), 'category_parent_3', 'Select Product Line'); //3번째 셀렉트 박스 만드는 함수 호출
    });

    /**
     * 셀렉트 박스 만드는 함수
     * [int]term_id : 상위 카테고리 아이디
     * [string]obj_id : 카테고리 select box id
     * [string]first_sel_text : select box default option value
     * [int]selected_data : select box selected value
     */
    function md_category(term_id, obj_id, first_sel_text, selected_data) {

        if (term_id != '') {
            $.ajax({
                type: 'POST',
                dataType: 'json', //배열형태로 받아오기  //default : text
                url: main.ajaxUrl,
                data: {
                    'action': 'get_list_category',
                    'term_id': term_id,
                    'nonce': main.categoryNonce
                },
                success: function (response) {
                    var obj = document.getElementById(obj_id);
                    obj.innerHTML = '';
                    var html = '';

                    html += '<option value=>' + first_sel_text + '</option>'
                    $.each(response, function (i, item) {
                        html += '<option value=' + item.term_id + '>' + item.name + '</option>';
                    });
                    obj.innerHTML = html;
                    $('#' + obj_id).css('display', 'block');

                    if (selected_data != '') {
                        $('#' + obj_id).val(selected_data).prop('selected', true);
                    }
                },
                error: function (r) {
                    alert(JSON.stringify(r));
                    alert('Failed1');
                }
            });//end call ajax
        }//end if
    } //end function md_category

    /**
     * 상위 카테고리에 해당되는 상품 메타 필드 리턴 함수
     * [int]term_id : 상위 카테고리 아이디
     * [String]obj_id : 메타 필드가 리턴될 div id
     */
    function md_meta_field(term_id, obj_id) {

        if (term_id != '') {
            $.ajax({
                type: 'POST',
                dataType: 'html', //html 형태로 받아오기  //default : text
                url: main.ajaxUrl,
                data: {
                    'action': 'get_custom_field',
                    'term_id': term_id,
                    'post_id': main.postId,
                    'nonce': main.metaFieldNonce
                },
                success: function (response) {
                    $('#custom_meta_field').html(response);
                    $('#custom_meta_field').css('display', 'block');
                },
                error: function (r) {
                    alert(JSON.stringify(r));
                    alert('Failed2');
                }
            });//end call ajax
        }
    } //end function md_meta_field

});