(function() {
    tinymce.create('tinymce.plugins.NMUploader', {
        init : function(ed, url) {
        	/*ed.addCommand('insertmebubble', function() {
				ed.execCommand('mceInsertContent', false, 'just some text');
				});*/
        	
            ed.addButton('nmuploader', {
                title : 'Place Uploader',
                image : url+'/images/upload.png',
                //cmd : 'insertmebubble',
                onclick : function() {                    
                    
                	tb_show('N-Media File Uploader Manager', 'admin-ajax.php?action=nm_filemanager_load_shortcodes&width=625&height=600');
                	
                	/*set_send("#sendcontent");*/
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
			/*
			 * I intentionally left the information of
			 * Brett Terpstra, as his code was the
			 * foundation for this.
			*/
            return {
                longname : "",
                author : '',
                authorurl : '',
                infourl : '',
                version : "1.0"
            };
        }
        
        
    });
    tinymce.PluginManager.add('nmuploader', tinymce.plugins.NMUploader);
    
    
})();

