const ckEditor = document.querySelectorAll('.ckeditor')

if (ckEditor) {
    [].forEach.call(ckEditor, function (elem) {
        ClassicEditor
            .create(elem, {
                language: 'fr',
                toolbar: {
                    items: [
                        'bold',
                        'italic',
                        'underline',
                        '|',
                        'numberedList',
                        'bulletedList',
                        'link',
                        'fontSize',
                        'blockQuote',
                    ]
                }
            })
            .catch(error => {
                console.error(error);
            });
    })
}