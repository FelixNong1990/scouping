<?php
class SNP_NHP_Options_color extends SNP_NHP_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since SNP_NHP_Options 1.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since SNP_NHP_Options 1.0
	*/
	function render(){
		
		$class = (isset($this->field['class']))?$this->field['class']:'';
		
		if(!$this->value)
		{
			$this->value=isset($this->field['std']) ? $this->field['std'] : '';
		}
		
		echo '<div class="farb-popup-wrapper">';		
		echo '<div class="colorSelector" id="'.$this->field['id'].'picker"><div></div></div>';
		echo '<input type="text" id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" value="'.$this->value.'" class="'.$class.' popup-colorpicker2 colorSelectorInput" style="width:70px;"/>';
		echo (isset($this->field['desc']) && !empty($this->field['desc']))?' <span class="description">'.$this->field['desc'].'</span>':'';
		echo '</div>';
		?>
		<script>
		jQuery(document).ready(function(){
			$colorpicker_inputs = jQuery('input.popup-colorpicker2');

			$colorpicker_inputs.each(function(){
					var $input = jQuery(this);
					var sIdSelector = "#" + jQuery(this).attr('id') + "picker";
					var sIdSelectorInput = "#" + jQuery(this).attr('id') + "";
					var initialColor = jQuery(sIdSelectorInput).val().replace('#','');
					jQuery(sIdSelector).children('div').css('background-color', '#' + initialColor);
					jQuery(sIdSelector).ColorPicker({
						color: initialColor,
						onShow: function (colpkr) {
							jQuery(colpkr).fadeIn(500);
							return false;
						},
						onHide: function (colpkr) {
							jQuery(colpkr).fadeOut(500);
							return false;
						},
						//onSubmit: function(hsb, hex, rgb, el) {
						///	jQuery(el).ColorPickerHide();
						///},
						//onBeforeShow: function () {
						//	jQuery(this).ColorPickerSetColor(this.value);
						//},
						onChange: function (hsb, hex, rgb) {
							jQuery(sIdSelectorInput).val('#' + hex);
							jQuery(sIdSelector).children('div').css('background-color', '#' + hex);
						}
					});
					jQuery(sIdSelectorInput).bind('keyup', function(){
						jQuery(sIdSelector).ColorPickerSetColor(this.value);
						jQuery(sIdSelector).children('div').css('background-color', '#' + this.value.replace('#',''));
					});
			});
		});
		</script>
		 <?php
		
	}//function
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since SNP_NHP_Options 1.0
	*/
	function enqueue(){
		
		wp_enqueue_script(
			'nhp-opts-field-color-js', 
			SNP_NHP_OPTIONS_URL.'fields/color/field_color.js', 
			array('jquery', 'farbtastic'),
			time(),
			true
		);
		
	}//function
	
}//class
?>