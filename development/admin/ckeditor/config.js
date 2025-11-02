/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		/*'/',*/
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'align' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	//config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3';
	//config.justifyClasses = [ 'AlignLeft', 'AlignCenter', 'AlignRight', 'AlignJustify' ];

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';	

	config.filebrowserImageUploadUrl = 'http://localhost/projects/achi/ckupload.php';
	config.filebrowserUploadMethod = 'form';
	
	//config.disallowedContent = 'img[width,height,style]';
	config.disallowedContent = 'img{border*,margin*,width*,height*,align*,*{font*}}';
	
	//config.entities = false;
	config.enterMode = CKEDITOR.ENTER_P;
	config.width = '90%';
	config.height = '300px';
	//config.removeButtons = 'PasteText,PasteFromWord,Smiley,Table,Form,Maximize,About,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,Iframe,PageBreak,ShowBlocks,Find,Replace,SelectAll,SpellChecker,Scayt,Save,NewPage,DocProps,Preview,Print,Templates,FontSize,Font';
	config.removeButtons = 'Flash,PasteText,PasteFromWord,Smiley,Table,Form,Maximize,About,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,Iframe,PageBreak,ShowBlocks,Find,Replace,SelectAll,SpellChecker,Scayt,Save,NewPage,DocProps,Preview,Print,Templates,FontSize,Font';
	
	config.removePlugins = "iframe";
	config.allowedContent = true;
	config.forcePasteAsPlainText = true;
};
