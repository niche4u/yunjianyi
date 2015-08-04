$(function() {

    $('html').on('click', function (e) {
        $(".search_input").popover('hide');
        $('[rel=\"popover\"]').each(function () {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('[rel=popover]').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
    $('.search_input_submit').click(function(){
        var keyword = $('.search_input').val();
        if (!/\S/.test(keyword)){
            $(".search_input").popover('show');
            return false;
        }
        $('#search_form').submit();
    });
    $('.search_input').keydown(function(event){
        if(event.keyCode == 13) {
            var keyword = $(this).val();
            if (!/\S/.test(keyword)){
                $(".search_input").popover('show');
                return false;
            }
            return true;
        }
    });

});