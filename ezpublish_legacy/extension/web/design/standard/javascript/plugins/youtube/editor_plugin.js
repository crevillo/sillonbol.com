(function(tinymce){tinymce.create('tinymce.plugins.YoutubePlugin',{init:function(ed,url){ed.addCommand('mceYoutube',function(){ed.execCommand('mceCustom',false,'youtube')});ed.addButton('youtube',{title:'youtube',cmd:'mceYoutube'});ed.onNodeChange.add(function(ed,cm,n){cm.setActive('youtube',n.nodeName==='SPAN')})},getInfo:function(){return{longname:'Youtube',author:'Carlos Revillo',authorurl:'http://www.tantacom.com',infourl:'http://www.tantacom.com',version:tinymce.majorVersion+"."+tinymce.minorVersion}}});tinymce.PluginManager.add('youtube',tinymce.plugins.YoutubePlugin)})(tinymce);