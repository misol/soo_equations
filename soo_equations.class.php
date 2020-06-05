<?php
/* Math equation Component by MinSoo Kim. (c) 2014-2015 MinSoo Kim. (misol.kr@gmail.com) */
class soo_equations extends EditorHandler {
	var $editor_sequence = '0';
	var $component_path = '';
	var $mobile_set = false;

	/**
	 * @brief editor_sequence과 컴포넌트의 경로를 받음
	 **/
	function __construct($editor_sequence, $component_path) {
		$this->editor_sequence = $editor_sequence;
		$this->component_path = $component_path;
		Context::loadLang($component_path.'lang');
		if(Mobile::isFromMobilePhone()) {
			$this->mobile_set = true;
		}
	}

	/** @brief popup window요청시 popup window에 출력할 내용을 추가하면 된다**/
	function getPopupContent() {
		// 템플릿을 미리 컴파일해서 컴파일된 소스를 return. Compile the popup contents and return it.
		$tpl_path = $this->component_path.'tpl';
		$tpl_file = 'popup.html';

		$oTemplate = &TemplateHandler::getInstance();
		return $oTemplate->compile($tpl_path, $tpl_file);
	}

	/**
	 * @brief 에디터 컴포넌트가 별도의 고유 코드를 이용한다면 그 코드를 html로 변경하여 주는 method
	 * 이미지나 멀티미디어, 설문등 고유 코드가 필요한 에디터 컴포넌트는 고유코드를 내용에 추가하고 나서
	 * DocumentModule::transContent() 에서 해당 컴포넌트의 transHtml() method를 호출하여 고유코드를 html로 변경
	 * @brief If editor comp. need to translate the code, this func. would translate it to html.
	 * DocumentModule::transContent() would call the transHTML() method.
	 **/
	function transHTML($xml_obj) {
		
		//한 페이지 내에 수식 수
		$math_count = intval(Context::get('pub_math_count'));
		
		if(!isset($math_count)) {
			$math_count = 0;
		} else {
			$math_count = $math_count + 1;
		}
		Context::set('pub_math_count' , $math_count);
		$latex = str_replace('\\', '\\\\', htmlentities(strip_tags(urldecode($xml_obj->attrs->alt)), ENT_QUOTES));
		
		Context::loadFile(array('./modules/editor/components/soo_equations/libs/mathquill-0.10.1/mathquill.js', 'body', '', null), true);
		Context::loadFile(array('./modules/editor/components/soo_equations/libs/mathquill-0.10.1/mathquill.css', '', '', null), true);
		Context::loadFile(array('./modules/editor/components/soo_equations/fronts/soo_equation_view.scss', '', '', null), true);
		
		$view_code = sprintf('<div id="preview-field-%u" class="soo_math_equations_area xe_content"></div><script>$(window).load(function() {var soo_MQ_%u = MathQuill.noConflict().getInterface(2);var soo_mathfield%u = soo_MQ_%u.StaticMath(document.getElementById(\'preview-field-%u\'));soo_mathfield%u.latex("%s");});</script>', $math_count, $math_count, $math_count, $math_count, $math_count, $math_count, $latex);
		
		return $view_code;
	}

}
?>