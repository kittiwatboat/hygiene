CKEDITOR.editorConfig = function( config ) {
	config.language = 'th';
	config.height = 300;
	config.toolbarCanCollapse = true;
    config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] },
	];
	config.extraPlugins = 'ckeditor_wiris,mathjax';
    config.ckeditor_wiris = {
        mathTypeParameters: {
            baseURL: '/path/to/mathtype',
            javaScript: 'configinfo=1',
        },
    };
	config.mathJaxLib = 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML';
	config.removeButtons = 'Save,NewPage,ExportPdf,Preview,Print,Templates,Cut,Scayt,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CopyFormatting,RemoveFormat,Flash,Smiley,SpecialChar,About';
};