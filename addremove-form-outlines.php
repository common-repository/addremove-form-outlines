<?php
/*
Plugin Name: Customize Form Outlines
Plugin URI: http://www.patrickgarman.com
Description: Some browsers add outlines to form elements, this plugin allows you to customize the form for all browsers.
Version: 1.0.1
Author: Patrick Garman
Author URI: http://www.patrickgarman.com/

    Copyright 2011 Patrick Garman (email : patrick@patrickgarman.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

add_action('plugins_loaded', 'init_addremove_form_elements');
function init_addremove_form_elements() { $addremove_form_elements = new addremove_form_elements; }

class addremove_form_elements {
	function __construct() {
		add_action('wp_head', array(&$this,'add_style'));
		add_action('admin_head', array(&$this,'add_style'));
		register_activation_hook(__FILE__, array(&$this, 'on_activation'));
		register_deactivation_hook(__FILE__, array(&$this, 'on_deactivation'));
		add_action('admin_init', array(&$this, 'register_settings'));
		if (is_admin()) {
			add_action('admin_menu', array(&$this,'admin_menus'));
			add_filter('plugin_action_links_'.plugin_basename(__FILE__), array(&$this,'settings_link'));
		}
	}

	function options_page() { ?>
		<div class="wrap">
			<div id="icon-themes" class="icon32"></div>
			<h2>Customize Form Element Outlines</h2>
			<form action="options.php" method="post">
					<div class="postbox-container" style="width:70%;">
					<div class="metabox-holder">	
						<form action="options.php" method="post">
							<div id="settings">
								<div class="inside">
									<?php settings_fields('outlines'); ?>
									<?php do_settings_sections(basename(__FILE__,'.php')); ?>
								</div>
							</div>
							<p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
						</form>
					</div>
				</div>
				<div class="postbox-container" style="width:29%;">
					<div class="metabox-holder">	
						<div id="support">
							<h3><span>Need Help?</span></h3>
							<div class="inside">
								<p>If you have any questions or problems with the plugin please contact me using the link below. I will help you get everything in order as quickly as I can.</p>
								<p><a href="https://patrickgarman.zendesk.com/anonymous_requests/new" target="_blank">Submit a support ticket, bug report, feature request.</a></p>
							</div>
						</div> 
						<div id="like-links">
							<h3><span>Like This Plugin?</span></h3>
							<div class="inside">
								<p><a href="http://wordpress.org/extend/plugins/addremove-form-outlines/" target="_blank">Rate the plugin here.</a></p>
								<p><a href="http://www.patrickgarman.com/donate/" target="_blank">Say "Thanks!" with a donation.</a></p>
								<div style="float:left;height:62px;">
									<div style="float:left;margin-top:2px;margin-right:5px;"><g:plusone size="tall" href="http://www.patrickgarman.com/"></g:plusone></div>
									<div style="float:left;margin-right:5px;"><a href="http://twitter.com/share" class="twitter-share-button" data-url="http://www.patrickgarman.com/" data-text="Check out the awesome #WordPress #plugins @pmgarman is developing!" data-count="vertical" data-via="pmgarman">Tweet</a></div>
									<div style="float:left;margin-top:2px;">
<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.patrickgarman.com%2F&amp;send=false&amp;layout=box_count&amp;width=50&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:90px;" allowTransparency="true"></iframe>
									</div>
								</div><div style="clear:both;"></div>
								<p><a href="http://www.twitter.com/pmgarman" target="_blank"><img src="http://twitter-badges.s3.amazonaws.com/follow_me-a.png" alt="Follow pmgarman on Twitter"/></a></p>
								<p><a href="http://profiles.wordpress.org/users/patrickgarman/" target="_blank">More Plugins by Patrick Garman</a></p>
							</div>
							<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
							<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
						</div> 
						<div id="subscribe">
							<h3><span>Keep Updated via Email</span></h3>
							<div class="inside">
								<p>Keep up to date with the latest updates to this and other plugins via email. Just enter your email address below and click "Subscribe"</p>
								<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=PatrickGarman', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true"><p><input type="text" style="width:110px" name="email"/><input type="submit" value="Subscribe" /></p><input type="hidden" value="PatrickGarman" name="uri"/><input type="hidden" name="loc" value="en_US"/></form>
								<p>PS. Your information will <strong>not</strong> be shared, sold, spammed, etc, etc, etc...</p>
							</div>
						</div> 



					</div>
				</div>
			</form>
		</div>
	<?php }

	function admin_menus() {
		add_options_page('Customize Form Outlines', 'Form Outlines', 'edit_theme_options', basename(__FILE__,'.php'), array(&$this,'options_page'));
	}
	
	function add_style() {
		$settings = $this->settings_list();
		foreach ($settings as $setting) { $options->$setting['name']=get_option($setting['name']); }
		if (is_admin()) { $show = $options->outlines_admin; } else { $show = $options->outlines_show; }
		if ($show==0) { $styles = "outline:none;"; }
		else {
			$styles = "outline:{$options->outlines_width}px {$options->outlines_style} {$options->outlines_color};";
			if($options->outlines_offset>0) { $styles .= "outline-offset:{$options->outlines_offset}px;"; }
		}
	?>
		<style type="text/css" media="all">
			*:focus { <?php echo $styles; ?> }
		</style>
	<?php }
	
	function register_settings() {
		$settings = $this->settings_list();
		add_settings_section('outlines_options', 'General Options', array(&$this,'settings_main'), basename(__FILE__,'.php'));
		foreach ($settings as $setting) {
			register_setting('outlines',$setting['name']);
			add_settings_field($setting['name'], $setting['display'], array(&$this,'settings_'.$setting['name']), basename(__FILE__,'.php'), 'outlines_options');
		}
	}

	function settings_main(){ echo '<p>Make your site outlines work the same across all browsers!</p>'; }
	function settings_outlines_show(){ $this->radio('outlines_show','do you to style your website form elements focus pseudo class'); }
	function settings_outlines_admin(){ $this->radio('outlines_admin','do you to style your admin panels form elements focus pseudo class'); }
	function settings_outlines_width(){ $this->textbox('outlines_width','width of your outline in pixels'); }
	function settings_outlines_offset(){ $this->textbox('outlines_offset','offset of your outline in pixels !!! CSS3 ONLY !!!'); }
	function settings_outlines_color(){ $this->colorpicker('outlines_color','color of your outline'); }
	function settings_outlines_style(){ $this->style_select('outlines_style','style of your outline'); }

	function radio($name,$hint) {
		$value = get_option($name);
		echo 'Yes <input type="radio" name="'.$name.'" value="1" ';
		if ($value==1) { echo 'checked="checked" />'; } else { echo ' />'; }
		echo 'No <input type="radio" name="'.$name.'" value="0" ';
		if ($value==0) { echo 'checked="checked" />'; } else { echo ' />'; }
		echo '<em>('.$hint.')</em>';
	}

	function textbox($name,$hint) {
		echo '<input type="textbox" name="'.$name.'" value="'.get_option($name).'" />';
		echo '<em>('.$hint.')</em>';
	}

	function colorpicker($name,$hint) {
		echo '<input type="textbox" name="'.$name.'" value="'.get_option($name).'" />';
		echo '<em>('.$hint.')</em>';
	}

	function style_select($name,$hint) {
		$value = get_option($name);
		$styles = array('dotted','dashed','solid','double','groove','ridge','inset','outset');
		echo '<select name="'.$name.'">';
		foreach ($styles as $style) {
			echo '<option type="radio" name="'.$name.'" value="'.$style.'" ';
			if ($value==$style) { echo ' selected="selected">'; } else { echo '>'; }
			echo $style.'</option>';
		}
		echo '</select>';
		echo '<em>('.$hint.')</em>';
	}

	function on_activation(){
		$settings = $this->settings_list();
		foreach ($settings as $setting) {
			add_option($setting['name'], $setting['value']);
		}
	}
	
	function on_deactivation(){
		$settings = $this->settings_list();
		foreach ($settings as $setting) {
			delete_option($setting['name']);
		}
	}

	function settings_list() {
		$settings = array(
			array(
				'display' => 'Add Styles to Website',
				'name' => 'outlines_show',
				'value' => '0',
			),
			array(
				'display' => 'Add Styles to Admin',
				'name' => 'outlines_admin',
				'value' => '0',
			),
			array(
				'display' => 'Outline Width',
				'name' => 'outlines_width',
				'value' => '1',
			),
			array(
				'display' => 'Outline Offset',
				'name' => 'outlines_offset',
				'value' => '0',
			),
			array(
				'display' => 'Outline Color',
				'name' => 'outlines_color',
				'value' => '#1E90FF',
			),
			array(
				'display' => 'Outline Style',
				'name' => 'outlines_style',
				'value' => 'solid',
			),
		);
		return $settings;
	}
	
	function plugin_data($key){
		//[Name],[PluginURI],[Version],[Description] ,[Author],[AuthorURI],[TextDomain],[DomainPath],[Network],[Title],[AuthorName]
		$data = get_plugin_data(__FILE__);
		return $data[$key];
	}
	
	function settings_link($links) {
		$support_link = '<a href="https://patrickgarman.zendesk.com/" target="_blank">Support</a>';
		array_unshift($links, $support_link);
		$settings_link = '<a href="options-general.php?page='.basename(__FILE__,'.php').'">Settings</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

}