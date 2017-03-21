(function ($, window) {

    $(document).ready(function () {

        $(window).bind('beforeunload', function (e) {
            var clean = true;
            $('form').each(function () {
                var $form = $(this);
                if ($form.data('dirty')) {
                    clean = false;
                }
            });
            if (!clean) {
                var message = 'You have unsaved changes.';
                e.returnValue = message;
                return message;
            }
        });

        $('form').each(function () {
            var $form = $(this);
            $form.data('dirty', false);
            $form.on('change', function () {
                $form.data('dirty', true);
            });
            $form.on('submit', function () {
                $(window).unbind('beforeunload');
            });
        });
    });

    function addFilterForm($container) {
        var prototype = $container.data('prototype');
        console.log(prototype);
        var index = $container.data('count');
        var $form = $(prototype.replace(/__name__/g, index).replace(/label__/g, ''));
        $container.append($form);
        $form.children('label').replaceWith('<div class="col-sm-2"><a class="btn btn-primary remove">Remove</a></div>');
        $form.find("a.remove").click(function(e){
            e.preventDefault();
            $form.remove();
        });
        $container.data('count', index+1);        
    }

    $(document).ready(function(){
        $('form div.collection').each(function(idx, element){
            var $e = $(element);
            $e.children("label").append('<a href="#" class="btn btn-primary">Add</a>');
            var $a = $e.find("a");
            var $container = $e.find('div[data-prototype]');
            $container.data('count', $container.find('div.form-group').length);
            $a.click(function(e){
                e.preventDefault();
                addFilterForm($container);
            });
        });
    });

})(jQuery, window);
