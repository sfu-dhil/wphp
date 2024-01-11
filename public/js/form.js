(function ($, window, document) {

    const hostname = window.location.hostname.replace('www.', '');

    function confirm() {
        var $this = $(this);
        $this.click(function () {
            return window.confirm($this.data('confirm'));
        });
    }

    function link() {
        if (this.hostname.replace('www.', '') === hostname) {
            return;
        }
        $(this).attr('target', '_blank');
    }

    function windowBeforeUnload(e) {
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
    }

    function formDirty() {
        var $form = $(this);
        $form.data('dirty', false);
        $form.on('change', function () {
            $form.data('dirty', true);
        });
        $form.on('submit', function () {
            $(window).unbind('beforeunload');
        });
    }

    function simpleCollection() {
        if ( $('.collection-simple').length == 0 ) {
            return
        }
        $('.collection-simple').collection({
            init_with_n_elements: 0,
            allow_up: false,
            allow_down: false,
            max: 400,
            add_at_the_end: true,
            add: '<a href="#" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i></a>',
            remove: '<a href="#" class="btn btn-primary btn-sm"><i class="bi bi-dash-circle"></i></a>',
        });
    }

    function complexCollection() {
        if ( $('.collection-complex').length == 0 ) {
            return
        }
        $('.collection-complex').collection({
            init_with_n_elements: 0,
            allow_up: false,
            allow_down: false,
            max: 400,
            add_at_the_end: true,
            add: '<a href="#" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i></a>',
            remove: '<a href="#" class="btn btn-primary btn-sm"><i class="bi bi-dash-circle"></i></a>',
            after_add: function(collection, element){
                $(element).find('.select2entity').select2entity();
                return true;
            },
        });
    }

    function configureSelect2() {
        // when one select2 element is opening, all others must close
        // or the focus thing fails.
        $(document).on('select2:opening', function(e) {
            $('.select2entity').select2('close');
        });

        // set focus to the input
        $(document).on('select2:open', function (e) {
            document.querySelector('.select2-container--open input').focus();
        });
    }

    $(document).ready(function () {
        $(window).bind('beforeunload', windowBeforeUnload);
        $('form').each(formDirty);
        $("a").each(link);
        $("*[data-confirm]").each(confirm);
        let popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl, {
                html: true,
                trigger: 'focus',
            })
        }) // add this line to enable bootstrap popover
        let alertList = document.querySelectorAll('.alert')
        alertList.forEach(function (alert) {
            new bootstrap.Alert(alert);
        }); // add alert dismissal
        if (typeof $().collection === 'function') {
            simpleCollection();
            complexCollection();
        }
        configureSelect2();
    });

})(jQuery, window, document);
