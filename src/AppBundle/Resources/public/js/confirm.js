(function ($) {

    $(document).ready(function () {
        $("*[data-confirm]").each(function () {
            var $this = $(this);
            $this.click(function () {
                return window.confirm($this.data('confirm'));
            });
        });
    });

})(jQuery);
