// Copyright (c) 2015, Fujana Solutions - Moritz Maleck. All rights reserved.
// For licensing, see LICENSE.md
var base_url=window.location.origin
CKEDITOR.plugins.add( 'imageuploader', {
    init: function( editor ) {
        editor.config.filebrowserBrowseUrl = ''+base_url+'/ckeditor/plugins/imageuploader/imgbrowser.php';
    }
});
