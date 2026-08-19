<?php
ossn_load_external_js('summernote.js', 'admin');
ossn_load_external_css('summernote.css', 'admin');

echo ossn_view_form('awesometheme/settings', array(
    'action' => ossn_site_url() . 'action/awesome/settings',
	'class' => 'awesome-form-admin',	
));
?>
<script type="application/javascript">
$(document).ready(function() {
        $('#awesome-editor').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', []],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', []],
                ['help', []]
            ],
            callbacks: {
                onPaste: function(e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    setTimeout(function() {
                        document.execCommand('insertText', false, bufferText);
                    }, 10);
                }
            },
        });
    });
</script>