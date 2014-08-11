(function($){
    $('body').on('click', 'a[data-method="POST"]', function(event) {
        event.preventDefault();
        $('<form action="'+$(this).attr('href')+'" method="POST"></form>').appendTo('body').submit();
    });
})(jQuery);