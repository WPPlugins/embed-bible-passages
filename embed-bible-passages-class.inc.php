<?php

class EmbedBiblePassages {
	/* Shortcode of the form [embed_bible_passage reading_plan='bcp'] are replaced by the scriptures from a Bible Reading Plan
	 * of http://www.esvapi.org/api#readingPlanQuery.
	 */

	// NOTE THAT THE FOLLOWING COPYRIGHT NOTICE FROM THE SOURCE OF THE TEXT CROSSWAY BIBLE MUST BE KEPT ON THE PAGE.
	protected $esv_copyright	= 'Scripture taken from The Holy Bible, English Standard Version. Copyright &copy;2001 by <a href="http://www.crosswaybibles.org" target="_blank">Crossway Bibles</a>, a publishing ministry of Good News Publishers. Used by permission. All rights reserved. Text provided by the <a href="http://www.gnpcb.org/esv/share/services/" target="_blank">Crossway Bibles Web Service</a>. Reader: David Cochran Heath.';
	protected $access_key		= 'IP';
	protected $ajax_url			= '';
	protected $audio_format		= 'flash'; // Defaults to Flash for backwards compatibity
	protected $calendar_in_text	= false;
	protected $date_format		= 'l j F Y';
	protected $loading_image	= '';
	protected $plan_source_link	= 'http://www.esvapi.org/v2/rest/readingPlanQuery?include-headings=false';
	protected $plugin_url		= '';
	protected $post_id			= 0;
	protected $powered_by		= 'Powered by<br /><a href="http://wordpress.org/extend/plugins/embed-bible-passages/" target="_blank" title="Embed Bible Passages">Embed Bible Passages</a><br />plugin for WordPress';
	protected $query_string		= '';
	protected $reading_plans	= array(
										'bcp'						=> 'Book of Common Prayer',
										'lsb'						=> 'Literary Study Bible',
										'esv-study-bible'			=> 'ESV Study Bible',
										'every-day-in-the-word'		=> 'Every Day in the Word',
										'one-year-tract'			=> 'M&#039;Cheyne One-Year Reading Plan',
										'outreach'					=> 'Outreach',
										'outreach-nt'				=> 'Outreach New Testament',
										'through-the-bible'			=> 'Through the Bible in a Year',
										);
	protected $show_poweredby	= false;
	protected $short_code_atts	= array(
										'reading_plan' 	=> 'bcp',
										);
	protected $use_calendar		= false;
	protected $dev_screen_width	= 999;
	protected $switch_cal_width = 480;
	
	public function __construct () {
		$this->document_root	= 'http://'.$_SERVER['SERVER_NAME'];
		if (function_exists('plugins_url')) {
			$this->plugin_url = plugins_url('/embed-bible-passages/');
		} else {
			// For earlier WordPress versions
			$this->plugin_url = $this->document_root.'/wp-content/plugins/embed-bible-passages/';
		}
		$this->loading_image	= '<img title="Please wait until screen completes loading." class="ebp_loading_img" src="'.$this->plugin_url.'images/ajax-loading.gif">';
		$this->access_key		= get_option('embed_bible_passages_access_key');
		if (!$this->access_key) {
			$this->access_key = 'IP';
			update_option('embed_bible_passages_access_key', $this->access_key);
		}
		$this->show_poweredby	= get_option('embed_bible_passages_show_poweredby');
		if ('' == $this->show_poweredby) {
			$this->show_poweredby = false;
			update_option('embed_bible_passages_show_poweredby', $this->show_poweredby);
		}
		$this->audio_format		= get_option('embed_bible_passages_audio_format');
		if ('' == $this->audio_format) {
			$this->audio_format = 'flash';
			update_option('embed_bible_passages_audio_format', $this->audio_format);
		}
		$this->use_calendar		= get_option('embed_bible_passages_use_calendar');
		if ('' == $this->use_calendar) {
			$this->use_calendar = false;
			update_option('embed_bible_passages_use_calendar', $this->use_calendar);
		}
		$this->calendar_in_text	= get_option('embed_bible_passages_calendar_in_text');
		if ('' == $this->calendar_in_text) {
			$this->calendar_in_text = false;
			update_option('embed_bible_passages_calendar_in_text', $this->calendar_in_text);
		}
		$this->ajax_url = admin_url('admin-ajax.php', 'relative').'?action=put_bible_passage&';
		add_shortcode('embed_bible_passage', array(&$this, 'embedBiblePassage'));
		add_shortcode('embed_passage_date', array(&$this, 'passageDate'));
		add_action('admin_init', array(&$this, 'initialize_admin'));
		add_action('admin_menu', array(&$this, 'admin_add_page'));
	}

	public function initialize_admin () {
		if (function_exists('register_setting')) {
			$page_for_settings		= 'embed_bible_passages_plugin';
			$section_for_settings	= 'embed_bible_passages_section';
			add_settings_section($section_for_settings, 'Embed Bible Passages Settings', array(&$this, 'embed_bible_passages_section_heading'), $page_for_settings);
			add_settings_field('embed_bible_passages_access_key_id', 'Access Key', array(&$this, 'embed_bible_passages_access_key_value'), $page_for_settings, $section_for_settings);
			add_settings_field('embed_bible_passages_audio_format_id', 'Default Audio Format', array(&$this, 'embed_bible_passages_audio_format_value'), $page_for_settings, $section_for_settings);
			add_settings_field('embed_bible_passages_use_calendar_id', 'Show Date Picker Calendar', array(&$this, 'embed_bible_passages_use_calendar_value'), $page_for_settings, $section_for_settings);
			add_settings_field('embed_bible_passages_show_powered_by_id', 'Show "Powered by" attribution at bottom of page', array(&$this, 'embed_bible_passages_show_powered_by_value'), $page_for_settings, $section_for_settings);
			register_setting('embed_bible_passages_settings', 'embed_bible_passages_access_key', 'wp_filter_nohtml_kses');
			register_setting('embed_bible_passages_settings', 'embed_bible_passages_audio_format');
			register_setting('embed_bible_passages_settings', 'embed_bible_passages_use_calendar');
			register_setting('embed_bible_passages_settings', 'embed_bible_passages_calendar_in_text');
			register_setting('embed_bible_passages_settings', 'embed_bible_passages_show_poweredby');
		}
	}

	public function embed_bible_passages_section_heading () {
		_e('');
	}

	public function embed_bible_passages_access_key_value () {
		echo '<input id="embed_bible_passages_access_key_input" name="embed_bible_passages_access_key" size="35" type="text" value="'.$this->access_key.'" />';
		_e('<span style="padding-left: 15px; font-size: 0.9em; text-align: right;">(To request an Access Key fill out the form at <a href="http://www.esvapi.org/signup" target="_blank" title="ESV Bible Web Service - Request an API Key">http://www.esvapi.org/signup</a>)</span>');
	}

	public function embed_bible_passages_audio_format_value () {
		echo '<input name="embed_bible_passages_audio_format" id="embed_bible_passages_audio_format_id" type="radio" value="mp3" class="code" '.checked('mp3', $this->audio_format, false).' /> MP3&nbsp;&nbsp;<input name="embed_bible_passages_audio_format" id="embed_bible_passages_audio_format_id" type="radio" value="flash" class="code" '.checked('flash', $this->audio_format, false).' /> Flash<ul style="margin: 5px 0 -20px 50px;"><li>MP3 will be used for Android, iPad, and iPhone in all cases, since Flash will not work there.</li><li>MP3 displays with the WordPress audio player, as implemented with the <a href="https://codex.wordpress.org/Audio_Shortcode" target="_blank">WordPress Audio Shortcode</a>.<br />Flash displays with a "Listen" link which opens a small Flash player.</li></ul>';
	}
	
	public function embed_bible_passages_use_calendar_value () {
		echo '<div style="float: left;"><input name="embed_bible_passages_use_calendar" id="embed_bible_passages_use_calendar_id" type="checkbox" value="1" class="code" '.checked(true, $this->use_calendar, false).' /></div>';
		echo '<div id="embed_bible_passages_calendar_in_text_id" style="float: left; margin: 0;  display: ';
		if ($this->use_calendar) {
			echo 'inline';
		} else {
			echo 'none';
		}
		echo ';">';
		echo '<div style="float: left; margin: 0 0 0 10px;"><input name="embed_bible_passages_calendar_in_text" type="radio" value=1 class="code" ';
		if ($this->calendar_in_text)  echo 'checked';
		echo ' /> at top of Bible text, with text wrapped</div>';
		echo '<div style="float: left; margin: 0 0 0 10px;"><input name="embed_bible_passages_calendar_in_text" type="radio" value=0 class="code" ';
		if (!$this->calendar_in_text) echo 'checked';
		echo ' /> above text</div><div style="float: left; margin: 0 0 0 10px;">(Calendar will always be above the text for screens less than '.$this->switch_cal_width.'px wide.)</div></div>';
		echo '
<script type="text/javascript"> 
	/* <![CDATA[ */
		jQuery(document).ready(function($) {
			$("#embed_bible_passages_use_calendar_id").click(function() {
				$("#embed_bible_passages_calendar_in_text_id").toggle(this.checked);
			});
		});
	/* ]]> */ 
</script>
		';
	}
	
	public function embed_bible_passages_show_powered_by_value () {
		echo '<input name="embed_bible_passages_show_poweredby" id="embed_bible_passages_show_poweredby_id" type="checkbox" value="1" class="code" '.checked(true, $this->show_poweredby, false).' />';
	}
	
	public function admin_add_page() {
		add_options_page('Embed Bible Passages Settings', 'Embed Bible Passages', 'manage_options', 'embed_bible_passages_plugin', array(&$this, 'draw_options_page'));
	}

	public function draw_options_page () {
		echo '<div><h2>Embed Bible Passages Options</h2>';
		echo '<h3>Shortcode Format</h3>
			<p>This plugin provides the ability to embed Bible readings from the <a href="http://www.esvapi.org/api#readingPlanQuery" target="_blank">ESV Bible Web Service</a> into a post or page using shortcode of the form [embed_bible_passage reading_plan=\'bcp\'].
			</p><p>
			The values of reading_plan can be:
			<ul style="text-indent: 20px;">
				<li>bcp						- Book of Common Prayer</li>
				<li>lsb						- Literary Study Bible</li>
				<li>esv-study-bible			- ESV Study Bible</li>
				<li> every-day-in-the-word	- Every Day in the Word</li>
				<li>one-year-tract			- M&#039;Cheyne One-Year Reading Plan</li>
				<li>outreach				- Outreach</li>
				<li>outreach-nt				- Outreach New Testament</li>
				<li>through-the-bible		- Through the Bible in a Year</li>
				</ul>
			The default reading plan is bcp.</p>
			<p>Note that only the bcp and through-the-bible options have been tested for this plugin. The other options are provided by the ESV Bible Web Service and should also work.</p>
			<p>For more information about these reading plans, please see the <a href="http://www.esvbible.org/search/?q=devotions" target="_blank">ESVBible.org Devotions area</a>.</p>
			<p>The page opens with the plan reading for the current date. A date picker calendar is available (see option below) to enable users to choose readings for other dates.</p>
			<p>A tag to embed the current date is also available [embed_passage_date], although this is deprecated in favor of using the date picker calendar.</p>';
		echo '<form method="post" action="options.php">';
		settings_fields('embed_bible_passages_settings');
		do_settings_sections('embed_bible_passages_plugin');
		echo '<p><input name="Submit" type="submit" value="';
		esc_attr_e('Save Changes');
		echo '" /></p>';
		echo '</form></div>';
		echo '<div style="width: 50%; margin-top: 25px;">If you find this plugin of value, please contribute to the cost of its developement:<div style="margin: auto; text-align: center"><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="XR9J849YUCJ3A">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form><div style="font-size: 0.8em;">"Do not muzzle an ox while it is treading out the grain." and "The worker deserves his wages." <a href="http://www.biblegateway.com/passage/?search=1%20Timothy+5:18&version=NIV" target="_blank">1 Timothy 5:18</a></div></div></div>';
		echo '<p>The support of "<a href="http://www.thebiblechallenge.org/" target="_blank">The Bible Challenge</a>" for the development of several important features of this plugin is gratefully acknowledged.</p>';
	}

	public function passageDate () {
		return date($this->date_format);
	}

	public function addCSS () {
		wp_register_style('embed-bible-passages-jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('embed-bible-passages-jquery-ui');
		wp_register_style('embed-bible-passages', $this->plugin_url.'css/embed-bible-passages.css', array('embed-bible-passages-jquery-ui',), null);
		wp_enqueue_style('embed-bible-passages');
	}
	
	protected function addDatepickerUI () {
		$rtn_str  = '<script type="text/javascript" src="'.includes_url().'js/jquery/ui/core.min.js"></script>';
		$rtn_str .= '<script type="text/javascript" src="'.includes_url().'js/jquery/ui/datepicker.min.js"></script>';
		$rtn_str .= '
<script type=\'text/javascript\'>
	jQuery(document).ready(function(jQuery){jQuery.datepicker.setDefaults({"closeText":"Close","currentText":"Today","monthNames":["January","February","March","April","May","June","July","August","September","October","November","December"],"monthNamesShort":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],"nextText":"Next","prevText":"Previous","dayNames":["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],"dayNamesShort":["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],"dayNamesMin":["S","M","T","W","T","F","S"],"dateFormat":"d MM yy","firstDay":0,"isRTL":false});});
</script>';
		return $rtn_str;
	}
	
	public function addScriptureLoader () {
		echo "
<script type=\"text/javascript\" defer> 
	/* <![CDATA[ */
		var ajaxurl = '{$this->ajax_url}{$this->query_string}&requested_date=';
		// Load Scriptures initially with the date set on the client's computer.
		var ebp_date_obj = new Date();
		jQuery(document).ready(function($) {
			$.ajax({
				type: 'GET',
				url: ajaxurl + encodeURI(ebp_date_obj.toDateString()) + '&device_screen_width=' + encodeURI($(window).width()), 
				data: {
					action: 'put_bible_passage',
				},
				success: function (response) {
					$('#scriptures').html(response);
				}
			})
		});
	/* ]]> */ 
</script>\n";
	}
	
	public function datePicker () {
		// Datepicker to load Scriptures for dates other than today
		$rtn_str  = $this->addDatepickerUI(); // This has to be added here, rather than by the normal wp_enqueue_scripts hook, since this code is not available when the datepicker is embedded in the text
		$rtn_str .= "<div title=\"".__('Click on a date to open the readings for that day.')."\" id=\"datepicker\"></div>
<script type=\"text/javascript\"> 
	/* <![CDATA[ */
		var datepicker_id		= '#datepicker_above';
		var calendar_in_text	= ".($this->calendar_in_text ? 'true' : 'false').";
		var switch_cal_width	= ".$this->switch_cal_width.";
		if (calendar_in_text && switch_cal_width < jQuery(window).width()) {
			datepicker_id = '#datepicker';
		}
		jQuery(document).ready(function($) {
			$(function() {
				$(datepicker_id).datepicker({
					autoSize:	true,
					onSelect:	function(dateText) {
									$('#scriptures').html('$this->loading_image');
									$.get(ajaxurl + dateText, function(data) {
										$('#scriptures').html(data);
									});
								}
				})
			})
		});
		jQuery(datepicker_id).datepicker('refresh');
	/* ]]> */ 
</script>\n";
		if ($this->calendar_in_text && $this->switch_cal_width < $this->dev_screen_width) {
			return "<div title=\"".__('Click on a date to open the readings for that day.')."\" id=\"datepicker\"></div>".$rtn_str;
		} else {
			echo $rtn_str;
		}
	}

	public function embedBiblePassage ($atts) {
		extract(shortcode_atts($this->short_code_atts, $atts));
		if (!in_array($reading_plan, array_keys($this->reading_plans))) {
			$reading_plan = 'bcp'; // default
		}
		$this->query_string = "reading-plan=$reading_plan";
		// If the audio format setting is mp3 and, also, for Android, iPad, or iPhone use mp3
		if ('mp3' == $this->audio_format || strpos($_SERVER["HTTP_USER_AGENT"], 'Android') !== false || strpos($_SERVER["HTTP_USER_AGENT"], 'iPad') !== false || strpos($_SERVER["HTTP_USER_AGENT"], 'iPhone') !== false) {
			$this->query_string .= '&audio-format=mp3';
		}
		return $this->getBiblePassage();
	}

	protected function getBiblePassage ($query_string = '', $error_message = 'ERROR: Could not retrieve readings') {
		if ($query_string) {
			$this->query_string  = $query_string;
		} else {
			$this->query_string .= '&date='.date('Y-m-d'); // This is most likely never reached in the current version
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$this->plan_source_link&key=$this->access_key&$this->query_string");
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$txt = trim(curl_exec($ch));
		curl_close($ch);
		parse_str($this->query_string);
		if ($date) {
			list($year, $month, $day) = explode('-', $date);
			$scriptures_date = date($this->date_format, mktime(0, 0, 1, $month, $day, $year));
		} else {
			$scriptures_date = date($this->date_format);
		}
		if ($txt && strpos($txt, 'ERROR') === false) {
			if ('mp3' == $this->audio_format) {
					// Utilize wp_audio_shortcode function to set up the audio tags
					$txt  = preg_replace("|<small class=\"audio\">\(<a href=\"http://([^\"]+)\">Listen</a>\)</small>|m", "<div class=\"ebp_audio_player\">[audio src='http://$1.mp3' type='audio/mpeg']</div>", $txt);
			}
			if ($this->use_calendar) {
				if ($this->calendar_in_text) {
					if ($this->switch_cal_width < $this->dev_screen_width) {
						$loc_txt	= '<p';
						$pos		= strpos($txt, $loc_txt);
						if ($pos !== false) {
							$txt = substr_replace($txt, $this->datePicker(), $pos, 0);
						}	
						$rtn_str = '<div class="scriptures-date">'.$scriptures_date.'</div>'.$txt;
					} else {
						$rtn_str = '<div class="scriptures-date">'.$scriptures_date.'</div>'.$this->datePicker().$txt;
					}
				} else {
					$rtn_str = '<div class="scriptures-date">'.$scriptures_date.'</div>'.$txt;
				}
			} else {
				$rtn_str = $txt;
			}
			$rtn_str .= '<div style="font-size: 0.8em; width: 50%; float: left; margin: 0;">'.$this->esv_copyright.'</div>';
			$rtn_str .= '<div style="font-size: 0.8em; width: 50%; float: left; margin: 0; text-align: right;">';
			if ($this->show_poweredby) {
				$rtn_str .= $this->powered_by;
			} else {
				$rtn_str .= '&nbsp;';
			}
			$rtn_str .= '</div>';
		} else {
			$rtn_str = "$error_message for $scriptures_date from <a href=\"http://www.gnpcb.org/esv/share/services/\" target=\"_blank\">Crossway Bibles Web Service</a>.";
		}
		$scriptures_div = '<div id="scriptures">'.$this->loading_image.'</div>';
		if ($query_string) {
			return $rtn_str; // with calendar, and loaded with calendar selection 
		} elseif ($this->use_calendar) {
			 // with calendar, but loaded without calendar selection
			return  '<div title="'.__('Click on a date to open the readings for that day.').'" id="datepicker_above"></div>'.$scriptures_div;
		} else {
			return $scriptures_div; // no calendar
		}
	}
	
	public function putBiblePassage () {
		$query_string = '';
		if (isset($_REQUEST['reading-plan']) && $_REQUEST['reading-plan']) {
			$query_string .= '&reading-plan='.$_REQUEST['reading-plan'];
		}
		if (isset($_REQUEST['audio-format']) && $_REQUEST['audio-format']) {
			$query_string .= '&audio-format='.$_REQUEST['audio-format'];
		}
		if (isset($_REQUEST['device_screen_width']) && $_REQUEST['device_screen_width']) {
			$query_string			.= '&device_screen_width='.$_REQUEST['device_screen_width'];
			$this->dev_screen_width  = $_REQUEST['device_screen_width'];
		}
		if (isset($_REQUEST['requested_date']) && $_REQUEST['requested_date']) {
			if (preg_match("/[a-zA-Z]+/", $_REQUEST['requested_date']) === false) {
				list($month, $day, $year) = explode('/', $_REQUEST['requested_date']);
				$query_string .= "&date=$year-$month-$day";
			} else {
				$query_string .= "&date=".date("Y-m-d", strtotime($_REQUEST['requested_date']));
			}
		}
		if ('mp3' == $this->audio_format) {
			echo do_shortcode($this->getBiblePassage($query_string));
			wp_footer();
		} else {
			echo $this->getBiblePassage($query_string);
		}
		wp_die();
	}

}

?>