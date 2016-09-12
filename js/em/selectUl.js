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

    if(isMobile || isPhone)
        return false;
    return true;
}

$.fn.selectUlCategorySearch = function(){

    var config = {
        over: function(){            
            
            if ($(this).parent().children().length > 1){
                $(this).parent().children('.catsearch-dropdown').children('ul').addClass('over');
            } else {
                $(this).addClass('over');
                // $('.toolbar-dropdown', this).css({width: $(this).width()+50});
            }

            $(this).parent().children('.catsearch-dropdown').children('ul').animate({opacity:1, height:'toggle'}, 100);
            //$('.toolbar-dropdown ul', this).animate({opacity:1, height:'toggle'}, 100);
        },
        timeout: 0, // number = milliseconds delay before onMouseOut
        out: function(){
            var that = this;
            $(this).parent().children('.catsearch-dropdown').children('ul').animate({opacity:0, height:'toggle'}, 100, function(){
                if ($(this).parent().children().length > 1){
                    $(that).parent().children('.catsearch-dropdown').children('ul').removeClass('over');
                } else {
                    $(that).removeClass('over');
                }
            });
        }
    };
    $('.input_cat select').css('display','none');
    $('.input_cat .catsearch-dropdown .current, .input_cat .catsearch-dropdown').hoverIntent( config );
}
$.fn.insertUlCategorySearch = function(){
    var $origSelect = $(this);
    var newId = $(this).attr('name') + '-ul';
    var numOptions = $(this).children().length;
    
    $('<div class="catsearch-dropdown"><span class="current"/><ul style="display:none" /></div>')
        .insertAfter($(this).toggleClass('.input_cat'));

    var divSpan = $(this).toggleClass('.input_cat').parent().parent().find('span.current');
    divSpan.append($(this).parent().children('select').find('option:selected').text());

    var divUl = $(this).toggleClass('.input_cat').parent().parent().find('ul');
    for (var i = 0; i < numOptions; i++) {
        var text = $(this).find('option').eq(i).text();
       $('<li />').text(text).appendTo(divUl);
    }
    
    $(this).parent().find('ul').find('li').click(function(){
        var newSelect = $(this).index();
        var valSelect = $(this).text();
        $(this)
            .parent()
            .find('.unselected')
            .removeClass('unselected');
        $(this)
            .parent()
            .find('li')
            .not(this)
            .addClass('unselected');
        $($origSelect)
            .find('option:selected')
            .removeAttr('selected');
        $($origSelect)
            .find('option:eq(' + newSelect + ')')
            .attr('selected',true);
        $(this)
            .parent().parent()
            .find('.current').text(valSelect);
        var cssObj = {
            'opacity':'0',
            'height':'toggle'
        };
        $(this)
            .parent()
            .removeClass('over');
        //window.location.href = $($origSelect).find('option:eq(' + newSelect + ')').val();
    });

    // assuming that you don't want the 'select' visible:
    //$(this).hide();
    
    return $(this);
}

})(jQuery);