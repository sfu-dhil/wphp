function getTinyMceConfig(editorUploadPath) {

    return {
        branding: false,
        selector: '.tinymce',
        plugins: 'advlist anchor charmap code help hr image imagetools link ' +
            'lists paste preview searchreplace table wordcount',
        relative_urls: false,
        convert_urls: false,
        height: 320,
        menubar: 'edit insert view format table tools help',

        toolbar: [
            'undo redo | styleselect | pastetext | bold italic | alignleft aligncenter alignright alignjustify | table',
            'bullist numlist | outdent indent | link | charmap | code'],

        browser_spellcheck: true,

        image_caption: true,
        images_upload_url: editorUploadPath,
        images_upload_credentials: true,
        image_advtab: true,
        image_title: true,

        resize: true,
        paste_as_text: true,
        paste_block_drop: true,
        images_upload_handler: function (blobInfo, success, failure, progress) {
            const MAX_SIZE = 20 * 1024 * 1024 // 20 MB
            // image size in bytes
            const image_size = blobInfo.blob().size
            if (image_size > MAX_SIZE) {
                image_size_in_mb = (image_size * 1.0 / 1024 / 1024).toFixed(2)
                failure(`The image exceeds the maximum upload size of 20 MB. <br />Image size is: ${image_size_in_mb} MB.`)
                return
            }
            // taken from https://www.tiny.cloud/docs/tinymce/5/upload-images/#_example_using_images_upload_handler
            const xhr = new XMLHttpRequest()
            xhr.withCredentials = true
            xhr.open('POST', editorUploadPath)

            xhr.upload.onprogress = function (e) {
              progress(e.loaded / e.total * 100)
            }

            xhr.onload = function() {
              if (xhr.status === 403) {
                failure('HTTP Error: ' + xhr.status, { remove: true })
                return
              }
              if (xhr.status < 200 || xhr.status >= 300) {
                failure('HTTP Error: ' + xhr.status)
                return
              }
              const json = JSON.parse(xhr.responseText)
              if (!json || typeof json.location != 'string') {
                failure('Invalid JSON: ' + xhr.responseText)
                return
              }
              success(json.location)
            }

            xhr.onerror = function () {
              failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status)
            }

            const formData = new FormData()
            formData.append('file', blobInfo.blob(), blobInfo.filename())
            xhr.send(formData)
        },

        style_formats_merge: true,
        style_formats: [{
                title: 'Image Left',
                selector: 'img, figure',
                styles: {
                    'float': 'left',
                    'margin': '0 10px 0 10px',
                },
            },
            {
                title: 'Image Center',
                selector: 'img, figure',
                styles: {
                    position: 'relative',
                    transform: 'translateX(-50%)',
                    left: '50%',
                },
            },
            {
                title: 'Image Right',
                selector: 'img, figure',
                styles: {
                    'float': 'right',
                    'margin': '0 10px 0 10px',
                },
            },
        ],
    }
}
