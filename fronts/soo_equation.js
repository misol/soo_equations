var latex_container = '';
var latexSpan = document.getElementById('latex');

var input_field = document.getElementById('math-field');
var input_field_config = {
	spaceBehavesLikeTab: true, // configurable
	handlers: {
		edit: function() { // useful event handlers
			latex_container = mathField.latex();
			$(latexSpan).val(latex_container); // simple API
			previewField.latex(latex_container);
			setFixedPopupSize();
		}
	}
};

var preview_field = document.getElementById('preview-field');

var MQ = MathQuill.getInterface(2); // for backcompat
var mathField = MQ.MathField(input_field, input_field_config);
var previewField = MQ.StaticMath(preview_field);

$(latexSpan).on("change keyup paste", function() {
	var currentVal = $(this).val();
	if(currentVal == latex_container) {
		return;
	}
	latex_container = currentVal;
	mathField.latex(latex_container);
	previewField.latex(latex_container);
	setFixedPopupSize();
});


function getEquation() {
	var node;

	if(typeof(opener) !="undefined" && opener !== null)
	{
		node = opener.editorPrevNode;
	}

	if(typeof(node) !="undefined" && node && node.nodeName == "IMG" && $(node).attr('editor_component') == 'soo_equations') {
		latex_container = decodeURIComponent(node.getAttribute('alt'));
		mathField.latex(latex_container);
		$(latexSpan).val(latex_container);
		previewField.latex(latex_container);
	} else {
	}

}

function insertEquation(obj) {
	if(typeof(opener)=="undefined" || !opener) return;
	var height = $(preview_field).height();
	
	var html = "<img  src=\"../../../../common/img/blank.gif\" editor_component=\"soo_equations\" style=\"display:block;width:100%;height:"+parseInt(height, 10)+"px;border:2px dotted #ff3b30;background:url(./modules/editor/components/soo_equations/component_icon.gif) no-repeat center;\" alt=\""+encodeURIComponent(latex_container)+"\" />";
	
	opener.editorFocus(opener.editorPrevSrl);
	var iframe_obj = opener.editorGetIFrame(opener.editorPrevSrl);
	opener.editorReplaceHTML(iframe_obj, html);
	opener.editorFocus(opener.editorPrevSrl);
	window.close();
}


jQuery(window).load(function() {
	getEquation();
	setFixedPopupSize();
});