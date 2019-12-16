/***************************************************************************
*                      www.mediatutorial.web.id
***************************************************************************/

/*Fungsi untuk loading ajax
 ingat, div_loading harus diluar div_result
*/
function get_html_data(url_page, data_get, div_loading, div_result){
    loading(div_loading,true); 
    setTimeout(function(){
        $.ajax({
            type: "GET",
            url: url_page,
            data: data_get, 
            cache: false,
            dataType: 'html',
            success: function(html){
                $("#"+div_result).slideUp('slow', function(){
                        $("#"+div_result).html(html);
                        $("#"+div_result).slideDown('slow');
                    }
                );
                loading(div_loading,false);
            }
        });
    }, 500);
}

function loading(div_container, is_show ){
    $("#"+div_container).css({
        'display':'none',
        'float':'right',
        'margin-right':'20px',
        'z-index':'5'
    });
    if(is_show == true)
        $("#"+div_container).html('<img src="'+base_session+'public/theme/images/icons/loading.gif" />').fadeIn(); //nanti diganti bila sudah dibuat system templates
    if(is_show == false)
        $("#"+div_container).html('<img src="'+base_session+'public/theme/images/icons/loading.gif" />').fadeOut();
}

function loading_bymouse(show){
    
}
