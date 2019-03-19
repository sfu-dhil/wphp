(function ($, window, tinymce, editorUploadPath) {

    const mceSettings = {
        selector: '.tinymce',
        plugins: 'image',
        relative_urls: false,
        convert_urls: false,

        image_caption: true,
        images_upload_url: editorUploadPath,
        images_upload_credentials: true,
        image_advtab: true,
        image_title: true,

        style_formats_merge: true,
        style_formats: [{
            title: 'Image Left', selector: 'img, figure', styles: {
                'float': 'left',
                'margin': '0 10px 0 10px'
            }
        }, {
            title: 'Image Right', selector: 'img, figure', styles: {
                'float': 'right',
                'margin': '0 10px 0 10px'
            }
        }]
    };

    var hostname = window.location.hostname.replace('www.', '');

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

    function formPopup(e) {
        e.preventDefault();
        var url = $(this).prop('href');
        window.open(url, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=60,left=60,width=500,height=600");
    }

    function simpleCollection() {
        $('.collection-simple').collection({
            init_with_n_elements: 1,
            allow_up: false,
            allow_down: false,
            add: '<a href="#" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span></a>',
            remove: '<a href="#" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-minus"></span></a>',
            add_at_the_end: false,
        });
    }

    function complexCollection() {
        $('.collection-complex').collection({
            init_with_n_elements: 1,
            allow_up: false,
            allow_down: false,
            add: '<a href="#" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span></a>',
            remove: '<a href="#" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-minus"></span></a>',
            add_at_the_end: true,
            after_add: function (collection, element) {
                $(element).find('.select2entity').select2entity();
                $(element).find('.select2-container').css('width', '100%');
                return true;
            },
        });
    }

    function tinyMceSetup() {
        tinymce.on('AddEditor', function (e) {
            let $editor = tinymce.get(e.editor.id);
            $editor.on("change", function (e) {
                $editor.save();
            });
        });
        // $('form').submit(function () {
        //     tinymce.activeEditor.uploadImages(function (success) {
        //         $.post('ajax/post.php', tinymce.activeEditor.getContent()).done(function () {
        //             console.log("Uploaded images and posted content as an ajax request.");
        //         });
        //     });
        // });

        tinymce.init(mceSettings);
    }

    $(document).ready(function () {
        $(window).bind('beforeunload', windowBeforeUnload);
        $('form').each(formDirty);
        $("a.popup").click(formPopup);
        $("a").each(link);
        $("*[data-confirm]").each(confirm);
        $('[data-toggle="popover"]').popover({
            container: 'body',
            trigger: 'hover',
            placement: 'bottom',
        }); //add this line to enable bootstrap popover
        if (typeof $().collection === 'function') {
            simpleCollection();
            complexCollection();
        }
        tinyMceSetup();
    });

})(jQuery, window, tinymce, editorUploadPath);
