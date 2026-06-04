CKEDITOR.editorConfig = function( config ) {
	config.language = 'th';
	config.height = 300;
	config.toolbarCanCollapse = true;
    config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		
	];

	config.removeButtons = 'Save,NewPage,ExportPdf,Preview,Print,Templates,Cut,Scayt,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CopyFormatting,RemoveFormat,Flash,Smiley,SpecialChar,About';
};