<?php

if (class_exists('EmbedBiblePassages')) {
	$bible_passage = new EmbedBiblePassages();
	if (isset($bible_passage)) {
		if (is_admin()) { // http://codex.wordpress.org/AJAX_in_Plugins#Ajax_on_the_Viewer-Facing_Side
			add_action('wp_ajax_put_bible_passage', array(&$bible_passage, 'putBiblePassage'));
			add_action('wp_ajax_nopriv_put_bible_passage', array(&$bible_passage, 'putBiblePassage'));
		} else {
			add_action('wp_head', array(&$bible_passage, 'addCSS'), 1);
			add_action('wp_footer', array(&$bible_passage, 'addScriptureLoader'), 98);
			add_action('wp_footer', array(&$bible_passage, 'datePicker'), 99);
		}
	}
}

?>