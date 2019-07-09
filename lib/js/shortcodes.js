(function() {
    tinymce.PluginManager.add( 'cta_button_plugin', function( editor, url ) {
        // Add Button to Visual Editor Toolbar
        editor.addButton('cta_button_plugin', {
            title: 'Add CTA Button',
            cmd: 'cta_button_plugin',
            image: url + '/../img/ctabutton.svg',
        });

        editor.addCommand('cta_button_plugin',function(){
            editor.windowManager.open({
                width : 480,
                height : 'auto',
                wpDialog: true,
                id : 'button-wpdialog',
                title : 'Add CTA Button'
            }, {
                plugin_url : url,
            });
        });
    });
})();

(function() {
    
})();