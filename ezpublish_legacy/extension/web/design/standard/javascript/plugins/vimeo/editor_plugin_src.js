(function(tinymce) {
        tinymce.create('tinymce.plugins.VimeoPlugin', {
                init : function(ed, url) {
                        //var n = ed.dom.getNode();
                        
               // Register commands
                        ed.addCommand('mceVimeo', function() {
                ed.execCommand('mceCustom', false, 'vimeo');
            });
                        
            // Register buttons
                        ed.addButton('vimeo', {title : 'vimeo', cmd : 'mceVimeo'});
            
            ed.onNodeChange.add(function(ed, cm, n) {
                cm.setActive('vimeo', n.nodeName === 'SPAN');
            });
 
                        
                },
 
                getInfo : function() {
                        return {
                                longname : 'Vimeo',
                                author : 'Carlos Revillo',
                                authorurl : 'http://www.tantacom.com',
                                infourl : 'http://www.tantacom.com',
                                version : tinymce.majorVersion + "." + tinymce.minorVersion
                        };
                }
        });
 
        // Register plugin
        tinymce.PluginManager.add('vimeo', tinymce.plugins.VimeoPlugin);
})(tinymce);
