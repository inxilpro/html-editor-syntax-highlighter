<?php
/*
Plugin Name: HTML Editor Syntax Highligher
Version: 0.1
Plugin URI: http://cmorrell.com/
Description: Edit HTML using syntax highlighting
Author: Chris Morrell
Author URI: http://cmorrell.com/
*/

add_action('admin_enqueue_scripts', function($hook) {
	if ('post.php' != $hook || 'edit' != $_REQUEST['action']) {
		return;
	}
	
	// Register JS
	wp_register_script('codemirror', plugins_url('codemirror/lib/codemirror.js', __FILE__));
	wp_register_script('codemirror_xml', plugins_url('codemirror/mode/xml/xml.js', __FILE__));
	wp_register_script('codemirror_javascript', plugins_url('codemirror/mode/javascript/javascript.js', __FILE__));
	wp_register_script('codemirror_css', plugins_url('codemirror/mode/css/css.js', __FILE__));
	wp_register_script('codemirror_htmlmixed', plugins_url('codemirror/mode/htmlmixed/htmlmixed.js', __FILE__));
	
	// Register CSS
	wp_register_style('codemirror', plugins_url('codemirror/lib/codemirror.css', __FILE__));
	wp_register_style('codemirror_theme', plugins_url('codemirror/theme/default.css', __FILE__));
	
	// Enqueue JS
	wp_enqueue_script('codemirror');
	wp_enqueue_script('codemirror_xml');
	wp_enqueue_script('codemirror_javascript');
	wp_enqueue_script('codemirror_css');
	wp_enqueue_script('codemirror_htmlmixed');
	
	// Enqueue CSS
	wp_enqueue_style('codemirror');
	wp_enqueue_style('codemirror_theme');
	
	// Action
	add_action('after_wp_tiny_mce', function() {
		?>
		<script type="text/javascript">
		var codeMirrorObj;
		function showCodeMirror()
		{
			codeMirrorObj = CodeMirror.fromTextArea(document.getElementById('content'), {
				mode: 'text/html',
				tabMode: 'indent',
				lineNumbers: true,
				lineWrapping: true
			});
			
			jQuery('#ed_toolbar').hide();
			jQuery('#quicktags').hide();
		}
		
		var _switchEditorsGo = switchEditors.go;
		switchEditors.go = function(id, mode) {
			if ('tinymce' == mode && codeMirrorObj) {
				codeMirrorObj.toTextArea();
				
				jQuery('#ed_toolbar').show();
				jQuery('#quicktags').show();
			}
			
			_switchEditorsGo.call(switchEditors, id, mode);
			
			if ('html' == mode) {
				showCodeMirror();
			}
		};
		
		setTimeout(function() {
			if (!tinyMCE.get('content')) {
				showCodeMirror();
			}
		}, 100);
		</script>
		<?php
	}, 999);
});