(function($) {

$.fn.selectUl = function(){

    var config = {
        over: function(){            
            
            if ($(this).parent().children().length > 1){
                $(this).parent().children('.toolbar-dropdown').children('ul').addClass('over');
            } else {
                $(this).addClass('over');
                // $('.toolbar-dropdown', this).css({width: $(this).width()+50});
            }

            $(this).parent().children('.toolbar-dropdown').children('ul').animate({opacity:1, height:'toggle'}, 100);
            //$('.toolbar-dropdown ul', this).animate({opacity:1, height:'toggle'}, 100);
        },
        timeout: 0, // number = milliseconds delay before onMouseOut
        out: function(){
            var that = this;
            $(this).parent().children('.toolbar-dropdown').children('ul').animate({opacity:0, height:'toggle'}, 100, function(){
                if ($(this).parent().children().length > 1){
                    $(that).parent().children('.toolbar-dropdown').children('ul').removeClass('over');
                } else {
                    $(that).removeClass('over');
                }
            });
        }
    };
    $('.toolbar-title select').css('display','none');
    $('.toolbar-switch .toolbar-dropdown .current, .toolbar-switch .toolbar-dropdown').hoverIntent( config );
}
$.fn.insertTitle = function(){
    $('<span class="current"/>').text($(this).find('option:selected').text())
    .insertBefore($(this));
}
$.fn.insertUl = function(){
    var numOptions = $(this).children().length;
                    
    $('<div class="toolbar-dropdown"><span class="current"/><ul style="display:none" /></div>')
        .insertAfter($(this).toggleClass('.toolbar-switch').parent());

    var divSpan = $(this).toggleClass('.toolbar-switch').parent().parent().find('span');
    divSpan.append($(this).parent().children('select').find('option:selected').text());

    var divUl = $(this).toggleClass('.toolbar-switch').parent().parent().find('ul');
    for (var i = 0; i < numOptions; i++) {
        var text = '<li><a href ="'+$(this).find('option').eq(i).val()+'">'+$(this).find('option').eq(i).text()+'</a></li>';
       //$('<li />').text(text).appendTo(divUl);
       divUl.append(text);
    }
}
$.fn.viewPC = function(){

var isMobile = /iPhone|iPod|iPad|Phone|Mobile|Android|hpwos/i.test(navigator.userAgent);
var isPhone = /iPhone|iPod|Phone|Android/i.test(navigator.userAgent);

    /*if($(this).hasClass('adapt-0'))
        return true;
    else{ 
        if($(this).hasClass('adapt-1'))
            return true;
        else{
            if($(this).hasClass('adapt-2'))
                return true;            
            else{ 
                if($(this).hasClass('adapt-3'))
                    return true;
            }
        }
    }
    return false;*/
    if(isMobile || isPhone)
        return false;
    return true;
}

})(jQuery);