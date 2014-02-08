(function(tinymce) {
        tinymce.create('tinymce.plugins.EzbbcodePlugin', {
                init : function(ed, url) {
                        //var n = ed.dom.getNode();
                        
               // Register commands
                        ed.addCommand('mceEzbbcode', function() {
                ed.execCommand('mceCustom', false, 'ezbbcode');
            });
                        
            // Register buttons
                        ed.addButton('ezbbcode', {title : 'ezbbcode', cmd : 'mceEzbbcode'});
            
            ed.onNodeChange.add(function(ed, cm, n) {
                cm.setActive('ezbbcode', n.nodeName === 'SPAN');
            });
 
                        
                },
 
                getInfo : function() {
                        return {
                                longname : 'Ezbbcode',
                                author : 'Carlos Revillo',
                                authorurl : 'http://www.tantacom.com',
                                infourl : 'http://www.tantacom.com',
                                version : tinymce.majorVersion + "." + tinymce.minorVersion
                        };
                }
        });
 
        // Register plugin
        tinymce.PluginManager.add('ezbbcode', tinymce.plugins.EzbbcodePlugin);
})(tinymce);
