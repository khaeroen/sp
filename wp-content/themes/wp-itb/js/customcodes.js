//ADD CUSTOM SHORTCODE BUTTONS TO TINYMCE EDITOR
(function() {	
	tinymce.create('tinymce.plugins.signoff', {
        init : function(ed, url) {
            ed.addButton('signoff', {
                title : 'Add signoff text - use 1, 2, or 3 for corresponding signoff text content',
                image : url+'/images/signoff.png',
                onclick : function() {
                     ed.selection.setContent('[signoff1]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });
	tinymce.create('tinymce.plugins.dropcap', {
        init : function(ed, url) {
            ed.addButton('dropcap', {
                title : 'Add dropcap',
                image : url+'/images/dropcap.png',
                onclick : function() {
                     ed.selection.setContent('[dropcap]' + ed.selection.getContent() + '[/dropcap]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });
	tinymce.create('tinymce.plugins.divider', {
        init : function(ed, url) {
            ed.addButton('divider', {
                title : 'Add divider',
                image : url+'/images/divider.png',
                onclick : function() {
                     ed.selection.setContent('[divider]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });
	tinymce.create('tinymce.plugins.quote', {
        init : function(ed, url) {
            ed.addButton('quote', {
                title : 'Add quote',
                image : url+'/images/quote.png',
                onclick : function() {
                     ed.selection.setContent('[quote]' + ed.selection.getContent() + '[/quote]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });
	tinymce.create('tinymce.plugins.pullquoteleft', {
        init : function(ed, url) {
            ed.addButton('pullquoteleft', {
                title : 'Add pullquote left',
                image : url+'/images/pullquote-left.png',
                onclick : function() {
                     ed.selection.setContent('[pullquote_left]' + ed.selection.getContent() + '[/pullquote_left]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });
	tinymce.create('tinymce.plugins.pullquoteright', {
        init : function(ed, url) {
            ed.addButton('pullquoteright', {
                title : 'Add pullquote right',
                image : url+'/images/pullquote-right.png',
                onclick : function() {
                     ed.selection.setContent('[pullquote_right]' + ed.selection.getContent() + '[/pullquote_right]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });
	// Creates a new plugin class and a custom listbox
    tinymce.create('tinymce.plugins.boxes', {
        createControl: function(n, cm) {
            switch (n) {
                case 'boxes':
                    var mlb = cm.createListBox('boxes', {
                        title : 'Boxes',
                        onselect : function(v) {
							// Set focus to WordPress editor
							tinyMCE.activeEditor.focus(); 
						
							// Get selected text
							var sel_txt = tinyMCE.activeEditor.selection.getContent();
						
							// If no text selected
							if( '' == sel_txt )
								sel_txt="Put your content here";	
						
							tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, '['+v+']'+sel_txt+'[/'+v+']' );

						}
					});

                    // Add some values to the list box
                    mlb.add('Light Box', 'box_light');
                    mlb.add('Dark Box', 'box_dark');
                    mlb.add('Info Box', 'box_info');
					mlb.add('Download Box', 'box_download');
					mlb.add('Help Box', 'box_help');
					mlb.add('Success Box', 'box_success');
					mlb.add('Alert Box', 'box_alert');
					mlb.add('Tip Box', 'box_tip');	
					mlb.add('Error Box', 'box_error');	
					mlb.add('Warning Box', 'box_warning');						

                // Return the new listbox instance
                return mlb;
               
            }
            return null;
        }
    });	
	tinymce.create('tinymce.plugins.togglesimple', {
        init : function(ed, url) {
            ed.addButton('togglesimple', {
                title : 'Add a simple toggle',
                image : url+'/images/toggle-simple.png',
                onclick : function() {
                     ed.selection.setContent('[toggle_simple title="Title of toggle box" width="Width of toggle box"]' + ed.selection.getContent() + '[/toggle_simple]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });
	tinymce.create('tinymce.plugins.togglebox', {
        init : function(ed, url) {
            ed.addButton('togglebox', {
                title : 'Add a box toggle',
                image : url+'/images/toggle-box.png',
                onclick : function() {
                     ed.selection.setContent('[toggle_box title="Title of toggle box" width="Width of toggle box"]' + ed.selection.getContent() + '[/toggle_box]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });
	tinymce.create('tinymce.plugins.tabs', {
        init : function(ed, url) {
            ed.addButton('tabs', {
                title : 'Add tabbed content',
                image : url+'/images/tabs.png',
                onclick : function() {
                     ed.selection.setContent('[tabgroup][tab title="Title of tab 1"]' + ed.selection.getContent() + '[/tab][tab title="Title of tab 2"]Tab 2 content goes here. You can add as many tabs as you want using this technique.[/tab][tab title="Title of tab 3"]Tab 3 content goes here. You can add as many tabs as you want using this technique.[/tab][/tabgroup]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });
	tinymce.create('tinymce.plugins.slider', {
        init : function(ed, url) {
            ed.addButton('slider', {
                title : 'Add content slider',
                image : url+'/images/slider.png',
                onclick : function() {
                     ed.selection.setContent('[slider][pane]' + ed.selection.getContent() + '[/pane][pane]Pane 2 content goes here. You can add as many panes as you want using this technique.[/pane][pane]Pane 3 content goes here. You can add as many panes as you want using this technique.[/pane][/slider]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });
	
	// Creates a new plugin class and a custom listbox
    tinymce.create('tinymce.plugins.lists', {
        createControl: function(n, cm) {
            switch (n) {
                case 'lists':
                    var mlb = cm.createListBox('lists', {
                        title : 'Lists',
                        onselect : function(v) {
							// Set focus to WordPress editor
							tinyMCE.activeEditor.focus(); 
						
							// Get selected text
							var sel_txt = tinyMCE.activeEditor.selection.getContent();
						
							// If no text selected
							if( '' == sel_txt )
								sel_txt="Put your bullet list here";	
						
							tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, '['+v+']'+sel_txt+'[/'+v+']' );

						}
					});

                    // Add some values to the list box
                    mlb.add('Fancy List', 'fancylist');
                    mlb.add('Arrow List', 'arrowlist');
                    mlb.add('Check List', 'checklist');
					mlb.add('Star List', 'starlist');
					mlb.add('Plus List', 'pluslist');
					mlb.add('Heart List', 'heartlist');
					mlb.add('Info List', 'infolist');					

                // Return the new listbox instance
                return mlb;
               
            }
            return null;
        }
    });	
		
	// Creates a new plugin class and a custom listbox
    tinymce.create('tinymce.plugins.columns', {
        createControl: function(n, cm) {
            switch (n) {
                case 'columns':
                    var mlb = cm.createListBox('columns', {
                        title : 'Columns',
                        onselect : function(v) {
							// Set focus to WordPress editor
							tinyMCE.activeEditor.focus(); 
						
							// Get selected text
							var sel_txt = tinyMCE.activeEditor.selection.getContent();
						
							// If no text selected
							if( '' == sel_txt )
								sel_txt="Column Content";	
						
							tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, '['+v+']'+sel_txt+'[/'+v+']' );

						}
					});

                    // Add some values to the list box
                    mlb.add('One Third', 'one_third');
                    mlb.add('One Third (last)', 'one_third_last');
                    mlb.add('Two Thirds', 'two_third');
					mlb.add('Two Thirds (last)', 'two_third_last');
					mlb.add('One Half', 'one_half');
					mlb.add('One Half (last)', 'one_half_last');
					mlb.add('One Fourth', 'one_fourth');
					mlb.add('One Fourth (last)', 'one_fourth_last');
					mlb.add('Three Fourths', 'three_fourth');
					mlb.add('Three Fourths (last)', 'three_fourth_last');
					mlb.add('One Fifth', 'one_fifth');
					mlb.add('One Fifth (last)', 'one_fifth_last');
					mlb.add('Two Fifths', 'two_fifth');
					mlb.add('Two Fifths (last)', 'two_fifth_last');
					mlb.add('Three Fifths', 'three_fifth');
					mlb.add('Three Fifths (last)', 'three_fifth_last');
					mlb.add('Four Fifths', 'four_fifth');
					mlb.add('Four Fifths (last)', 'four_fifth_last');
					mlb.add('One Sixth', 'one_sixth');
					mlb.add('One Sixth (last)', 'one_sixth_last');
					mlb.add('Five Sixths', 'five_sixth');
					mlb.add('Five Sixths (last)', 'five_sixth_last');					

                // Return the new listbox instance
                return mlb;
               
            }
            return null;
        }
    });	
	
	// Creates a new plugin class and a custom splitbutton
    tinymce.create('tinymce.plugins.smallbuttons', {
        createControl: function(n, cm) {
            switch (n) {                
                case 'smallbuttons':
                    var mlb = cm.createListBox('smallbuttons', {
                        title : 'Small Buttons',
                        onselect : function(v) {
							// Set focus to WordPress editor
							tinyMCE.activeEditor.focus(); 
						
							// Get selected text
							var sel_txt = tinyMCE.activeEditor.selection.getContent();
						
							// If no text selected
							if( '' == sel_txt )
								sel_txt="Button Text";
							
							tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, '[button link="The URL of the button" variation="'+v+'"]'+sel_txt+'[/button]' );
	
						}
					});

                    // Add some values to the list box
                    mlb.add('Light Grey', 'lightgrey');
					mlb.add('Grey', 'grey');
					mlb.add('Dark Grey', 'darkgrey');
					mlb.add('Black', 'black');
					mlb.add('Slate', 'slate');
					mlb.add('Blue', 'blue');
					mlb.add('Sky', 'sky');
					mlb.add('Green', 'green');
					mlb.add('Moss', 'moss');
					mlb.add('Red', 'red');
					mlb.add('Rust', 'rust');
					mlb.add('Brown', 'brown');
					mlb.add('Pink', 'pink');
					mlb.add('Purple', 'purple');
	                   					

                // Return the new listbox instance
                return mlb;              
               
            }
            return null;
        }
    });	
	
	// Creates a new plugin class and a custom splitbutton
    tinymce.create('tinymce.plugins.largebuttons', {
        createControl: function(n, cm) {
            switch (n) {                
                case 'largebuttons':
                    var mlb = cm.createListBox('largebuttons', {
                        title : 'Large Buttons',
                        onselect : function(v) {
							// Set focus to WordPress editor
							tinyMCE.activeEditor.focus(); 
						
							// Get selected text
							var sel_txt = tinyMCE.activeEditor.selection.getContent();
						
							// If no text selected
							if( '' == sel_txt )
								sel_txt="Button Text";
								
							//tinyMCE.activeEditor.windowManager.alert('Value selected:' + v);
						
							tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, '[button link="The URL of the button" variation="'+v+'" size="large"]'+sel_txt+'[/button]' );

						}
					});

                    // Add some values to the list box
                    mlb.add('Light Grey', 'lightgrey');
					mlb.add('Grey', 'grey');
					mlb.add('Dark Grey', 'darkgrey');
					mlb.add('Black', 'black');
					mlb.add('Slate', 'slate');
					mlb.add('Blue', 'blue');
					mlb.add('Sky', 'sky');
					mlb.add('Green', 'green');
					mlb.add('Moss', 'moss');
					mlb.add('Red', 'red');
					mlb.add('Rust', 'rust');
					mlb.add('Brown', 'brown');
					mlb.add('Pink', 'pink');
					mlb.add('Purple', 'purple');
	                   					

                // Return the new listbox instance
                return mlb;              
               
            }
            return null;
        }
    });	
	
	tinymce.PluginManager.add('dropcap', tinymce.plugins.dropcap);
	tinymce.PluginManager.add('divider', tinymce.plugins.divider);
	tinymce.PluginManager.add('quote', tinymce.plugins.quote);
	tinymce.PluginManager.add('pullquoteleft', tinymce.plugins.pullquoteleft);
	tinymce.PluginManager.add('pullquoteright', tinymce.plugins.pullquoteright);	
	tinymce.PluginManager.add('togglesimple', tinymce.plugins.togglesimple);
	tinymce.PluginManager.add('togglebox', tinymce.plugins.togglebox);	
	tinymce.PluginManager.add('signoff', tinymce.plugins.signoff);	
	tinymce.PluginManager.add('tabs', tinymce.plugins.tabs);
	tinymce.PluginManager.add('slider', tinymce.plugins.slider);
	tinymce.PluginManager.add('boxes', tinymce.plugins.boxes);		
	tinymce.PluginManager.add('columns', tinymce.plugins.columns);	
	tinymce.PluginManager.add('smallbuttons', tinymce.plugins.smallbuttons);
	tinymce.PluginManager.add('largebuttons', tinymce.plugins.largebuttons);
	tinymce.PluginManager.add('lists', tinymce.plugins.lists);		
})();