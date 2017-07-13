<?php
/*
Plugin Name: Handle External Links
Plugin URI: http://www.getwebinspiration.com/wordpress-plugin-handle-external-links/
Description: Handle External Links helps you to control when to set rel="nofollow" on your external links. You can set nofollow depending on the PageRank, 404 status, whether it's blacklisted or not, and  many more flexible criterias!
Version: 1.1.5
Author: Sebastian Westberg
Author URI: http://www.getwebinspiration.com/wordpress-plugin-handle-external-links/
License: GPL2

    Copyright 2012 Sebastian Westberg (email : getwebinspiration@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
if ( ! class_exists('HandleELInit' ) ) :
/**
 * This class triggers functions that run during activation/deactivation & uninstallation
 */
class HandleELInit {
    // Set this to true to get the state of origin, so you don't need to always uninstall during development.
    const STATE_OF_ORIGIN = false;

    function __construct( $case = false ) {
        switch( $case ) {
            case 'activate' :
                // add_action calls and else
                add_action( 'init', array( &$this, 'activate_cb' ) );
                break;

            case 'deactivate' : 
                // reset the options
                add_action( 'init', array( &$this, 'deactivate_cb' ) );
                break;

            case 'uninstall' : 
                // delete the tables
                add_action( 'init', array( &$this, 'uninstall_cb' ) );
                break;
        }
    }

    /**
     * Set up tables, add options, etc. - All preparation that only needs to be done once
     */
    function on_activate() {
        new HandleELInit( 'activate' );
    }

    /**
     * Do nothing like removing settings, etc. 
     * The user could reactivate the plugin and wants everything in the state before activation.
     * Take a constant to remove everything, so you can develop & test easier.
     */
    function on_deactivate() {
        $case = 'deactivate';
        if ( STATE_OF_ORIGIN )
            $case = 'uninstall';

        new HandleELInit( $case );
    }

    /**
     * Remove/Delete everything - If the user wants to uninstall, then he wants the state of origin.
     * 
     * Will be called when the user clicks on the uninstall link that calls for the plugin to uninstall itself
     */
    function on_uninstall() {
        // important: check if the file is the one that was registered with the uninstall hook (function)
        if ( __FILE__ != WP_UNINSTALL_PLUGIN )
            return;

        new HandleELInit( 'uninstall' );
    }

    function activate_cb() {
        // Stuff like adding default option values to the DB
    }

    function deactivate_cb() {
        // if you need to output messages in the 'admin_notices' field, do it like this:
        $this->error( "Some message.<br />" );
        // if you need to output messages in the 'admin_notices' field AND stop further processing, do it like this:
        $this->error( "Some message.<br />", TRUE );
        // Stuff like remove_option(); etc.
    }

    function uninstall_cb() {
        // Stuff like delete tables, etc.
    }
    /**
     * trigger_error()
     * 
     * @param (string) $error_msg
     * @param (boolean) $fatal_error | catched a fatal error - when we exit, then we can't go further than this point
     * @param unknown_type $error_type
     * @return void
     */
    function error( $error_msg, $fatal_error = false, $error_type = E_USER_ERROR ) {
        if( isset( $_GET['action'] ) && 'error_scrape' == $_GET['action'] ) 
        {
            echo "{$error_msg}\n";
            if ( $fatal_error )
                exit;
        }
        else 
        {
            trigger_error( $error_msg, $error_type );
        }
    }
}
endif;

// Class HandleEL_options_page with methods for the plugin options page
class HandleEL_options_page {
	function __construct() {
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'admin_init', array( &$this, 'options_init' ) ); 
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts_init' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'style_init' ) );
	}
	// Add a custom page for the plugin
	function admin_menu() {
		add_options_page('Handle External Links','Handle External Links','manage_options','HandleEL',array($this, 'settings_page'));
	}
	function options_init() { 
		register_setting( 'HandleEL_options', 'HandleEL_entry' );
		// Set some default values to option if it doesn't already exist
		if( !get_option( 'HandleEL_entry' ) )
			add_option( 'HandleEL_entry', array( '404' => 'no', 'date' => '', 'pr' => 'no', 'whitelist' => 'no', 'pr_unknown' => 'no', 'blacklist' => 'no', 'blank' => 'no' ) );
			
	}
	// Attach js based scripts
	function scripts_init() {
		wp_enqueue_script(
			'jquery-ui-datepicker'
		);
		wp_enqueue_script(
			'HandleEL-admin',
			plugins_url( 'js/admin.js', __FILE__ )
		);
	}
	// Attach css
	function style_init() {
		wp_enqueue_style(
			'jquery-ui-style',
			plugins_url( 'css/jquery-ui.css', __FILE__ ),
			array()
		);
	}
	// Echo the settings page
	function settings_page() {
	?>
		<div class="wrap">
		<div class="icon32" id="icon-options-general"><br /></div>
			<h2>Handle External Links</h2>
			<!-- Introduction -->
			<div style="background:#f5fce3; padding:7px 15px; border:1px dashed #c5e17a; margin:10px 0; width:70%;">
				<ul style="width:30%;float:left;">
					<li><strong>Thank you for downloading this Plugin!</strong></li>
					<li>If you have any suggestions or need support you're welcome to contact me at:</li>
					<li>getwebinspiration@gmail.com</li>
					<li>Or visit my blog: <a href="http://www.getwebinspiration.com" target="_blank">getwebinspiration.com</a></li>
					<li>Happy nofollowing! ;-)</li>
				</ul>
				<div style="width:60%;float:right;">
					<h3>Donate to the Plugin author</h3>
					<p>I'm very thankful for every donation I receive (1€ is just fine).<br />I will use the donations to dedicate time for plugin updates and support.</p>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="983SY6DHQCCY4">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
				</div>
				<div style="clear:both;"></div>
			</div>
			<!-- End introduction -->
			<!-- Is cURL installed? -->
			<?php if( !is_callable( 'curl_init'  ) ) { ?>
			<div style="background:#feeded; padding:7px 15px; border:1px dashed #e07575; margin:10px 0; width:70%;">
				<ul>
					<li><strong>Warning - this plugin needs to have cURL installed in order to work properly</strong></li>
					<li>If you're running a GNU/Linux distribution, install by running this command:</li>
					<li><code>apt-get install php5-curl</code> and restart Apache.</li>
					<li><a href="http://www.tonyspencer.com/2003/10/22/curl-with-php-and-apache-on-windows/" target="_blank">Guide for Windows servers</a></li>
				</ul>
			</div>
			<!-- End Is cURL installed? -->
			<?php } ?>
			<form method="post" action="options.php">
				<?php settings_fields( 'HandleEL_options' ); // nonce settings page ?>
				<?php $options = get_option( 'HandleEL_entry' ); // populate $options array from database ?>
				<table class="form-table" style="width:70%;">
					<!-- Best before date -->
					<tr valign="top" style="border-bottom:1px dashed #ccc;">
						<th scope="row">
							<label for="HandleEL_entry[date]"><strong>Best before date</strong></label>
							<p>Set nofollow on external links published before this date.</p>
							<p style="color:#999;font-style:italic;">E.g. 2010-04-03</p>
							<p>Leave empty if you don't want to use this function.</p>
						</th>
						<td><input type="text" style="border:1px solid #CCC;" id="HandleEL_entry[date]" name="HandleEL_entry[date]" class="datepicker" value="<?php echo $options['date']; ?>" /></td>
					</tr>
					<!-- Nofollow on 404's -->
					<tr valign="top" style="border-bottom:1px dashed #ccc;">
						<th scope="row">
							<label for="HandleEL_entry[404]"><strong>Set Nofollow on 404's</strong></label>
							<p>Uncheck if you don't want to set nofollow on external 404 links.</p>
						</th>
						<td><input type="checkbox" style="border:1px solid #CCC;" id="HandleEL_entry[404]" name="HandleEL_entry[404]" <?php echo $options['404'] == 'yes' ? 'checked="checked"' : '';?> value="yes" /></td>
					</tr>
					<!-- Nofollow based on PR -->
					<tr valign="top">
						<th scope="row">
							<label for="HandleEL_entry[pr]"><strong>Set Nofollow based on PageRank</strong></label>
							<p>Set nofollow on links with PR lesser or equal <= to this value.</p>
							<p style="color:#999;font-style:italic;">E.g. PR 8 won't set nofollow to www.google.com, but PR 9 will.</p>
						</th>
						<td><select name="HandleEL_entry[pr]" id="HandleEL_entry[pr]"><option value="no" <?php echo $options['pr'] == 'no' ? 'selected="selected"' : '';?>>Disable</option><option value="0" <?php echo $options['pr'] == '0' ? 'selected="selected"' : ''; ?>>PR 0</option><option value="1" <?php echo $options['pr'] == '1' ? 'selected="selected"' : '';?>>PR 1</option><option value="2" <?php echo $options['pr'] == '2' ? 'selected="selected"' : '';?>>PR 2</option><option value="3" <?php echo $options['pr'] == '3' ? 'selected="selected"' : '';?>>PR 3</option><option value="4" <?php echo $options['pr'] == '4' ? 'selected="selected"' : '';?>>PR 4</option><option value="5" <?php echo $options['pr'] == '5' ? 'selected="selected"' : '';?>>PR 5</option><option value="6" <?php echo $options['pr'] == '6' ? 'selected="selected"' : '';?>>PR 6</option><option value="7" <?php echo $options['pr'] == '7' ? 'selected="selected"' : '';?>>PR 7</option><option value="8" <?php echo $options['pr'] == '8' ? 'selected="selected"' : '';?>>PR 8</option><option value="9" <?php echo $options['pr'] == '9' ? 'selected="selected"' : '';?>>PR 9</option></select></td>
					</tr>
					<!-- Nofollow domains where we can't determine the PR -->
					<tr valign="top" style="border-bottom:1px dashed #ccc;">
						<th scope="row">
							<label for="HandleEL_entry[pr_unknown]"><strong>Nofollow links where PR can't be determined</strong></label>
							<p>Some domains doesn't return a PR for different reasons. Would you like to set nofollow on those links?</p>
						</th>
						<td><input type="checkbox" style="border:1px solid #CCC;" id="HandleEL_entry[pr_unknown]" name="HandleEL_entry[pr_unknown]" <?php echo $options['pr_unknown'] == 'yes' ? 'checked="checked"' : '';?> value="yes" /></td>
					</tr>
					<!-- Whitelist -->
					<tr valign="top" style="border-bottom:1px dashed #ccc;">
						<th scope="row">
							<label for="HandleEL_entry[whitelist]"><strong>Nofollow Whitelist</strong></label>
							<p>Whitelist your external URLs here. The above settings will not affect domains specified here.</p>
							<p style="color:#999;font-style:italic;">E.g. www.google.com, www.getwebinspiration.com, www.example.org (without http://)</p>
						</th>
						<td><textarea name="HandleEL_entry[whitelist]" id="HandleEL_entry[whitelist]" placeholder="E.g. www.google.com, www.getwebinspiration.com, www.example.org" style="width:400px;height:200px;"><?php echo $options['whitelist']; ?></textarea></td>
					</tr>
					<!-- Blacklist -->
					<tr valign="top" style="border-bottom:1px dashed #ccc;">
						<th scope="row">
							<label for="HandleEL_entry[blacklist]"><span style="background:#000;color:#fff;padding:2px;">Nofollow Blacklist</span></label>
							<p>Blacklist any URL here. These URLs will always be set to nofollow, no matter what settings you've chosen above.</p>
							<p style="color:#999;font-style:italic;">E.g. www.google.com, www.getwebinspiration.com, www.example.org (without http://)</p>
						</th>
						<td><textarea name="HandleEL_entry[blacklist]" id="HandleEL_entry[blacklist]" placeholder="E.g. www.google.com, www.getwebinspiration.com, www.example.org" style="width:400px;height:200px;"><?php echo $options['blacklist']; ?></textarea></td>
					</tr>
					<!-- target="_blank" -->
					<tr valign="top" style="">
						<th scope="row">
							<label for="HandleEL_entry[blank]"><strong>Open links in new window</strong></label>
							<p>Sets target="_blank" to all external URLs</p>
						</th>
						<td><input type="checkbox" style="border:1px solid #CCC;" id="HandleEL_entry[blank]" name="HandleEL_entry[blank]" <?php echo $options['blank'] == 'yes' ? 'checked="checked"' : '';?> value="yes" /></td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
			</form>
		</div>
	<?php
	}
}
new HandleEL_options_page;

class HandleEL_handle_links {
	// On initiate
	function __construct() {
		add_action( 'the_content' , array( &$this, 'check_links' ) );
	}
	function get_options() {
		$options = get_option( 'HandleEL_entry' );
		return $options;
	}
	// Returns true if the destination URL has the status '404'
	function check_404( $url ) {
		$handle = curl_init( $url );
		curl_setopt( $handle,  CURLOPT_RETURNTRANSFER, TRUE );

		// Get the data from $url
		$response = curl_exec( $handle );

		// Check for 404
		$httpCode = curl_getinfo( $handle, CURLINFO_HTTP_CODE );
		
		// Close the connection
		curl_close( $handle );
		
		// Return true if the destination URL is a 404
		if( $httpCode == 404 ) {
			return true;
		}
	}
	function verify_url( $url ) {
		if ( !preg_match( "~^(?:f|ht)tps?://~i", $url ) ) {
			return $url = "http://" . $url;
		}		
	}
	function check_links( $content ) {
		// Load simplehtmldom_1_5 class
		require_once ( 'simplehtmldom_1_5/simple_html_dom.php' );
		require_once( 'handleEL-classes.php' );
		
		// Get all options for the Plugin
		$get_options = $this->get_options();
		
		// Get the date option
		$option_date = $get_options['date'];
		
		// Get the PR option
		$option_pr = $get_options['pr'];
		
		$option_pr_unknown = $get_options['pr_unknown'];
		
		// Get 404 options
		$option_404 = $get_options['404'];
		
		// Get nofollow Whitelist options
		$option_whitelist = $get_options['whitelist'];
		
		// Get nofollow Blacklist options
		$option_blacklist = $get_options['blacklist'];
		
		$option_blank = $get_options['blank'];
		
		// Get the date of the current post
		$post_date = get_the_date( "Y-m-d h:i:s" );

		// Find out what is internal links
		$blog_url = parse_url( site_url() ); $blog_url = $this->verify_url( $blog_url['host'] );
		
		$nofollow = array();
		
		// Go through the whitelisted URLs to see that they contain a protocol ELSE we're adding http://
		// Whitelist = on
		if( $option_whitelist != 'no' ) {
			// Seperate by comma
			$option_whitelist = explode( ',', preg_replace('/\s+/', '', $option_whitelist ) );
			// Loop through the URL list and look for the protocol
			foreach( $option_whitelist as $key => $url ) {
				if( !preg_match( "~^(?:f|ht)tps?://~i", $url ) ) {
					// Protocol wasn't found, add http://
					$whitelist_url[] = 'http://' . $url;
				}
			}
			unset($url);
		}
		
		// Go through the blacklisted URLs to see that they contain a protocol ELSE we're adding http://
		// Blacklist = on
		if( $option_blacklist != 'no' ) {
			// Seperate by comma
			$option_blacklist = explode( ',', preg_replace('/\s+/', '', $option_blacklist ) );
			// Loop through the URL list and look for the protocol
			foreach( $option_blacklist as $key => $url ) {
				if( !preg_match( "~^(?:f|ht)tps?://~i", $url ) ) {
					// Protocol wasn't found, add http://
					$blacklist_url[] = 'http://' . $url;
				}
			}
		}
		// Get HTML of the $content variable
		$html = str_get_html( $content );
		
		// Find all the a elements and loop through
		foreach ( $html->find( "a" ) as $a ) {
		
			// Parse the URLs and add a protocol if it does not exist
			$parsed_url = parse_url($a->href);
			$parsed_url = $this->verify_url($parsed_url['host']);
		
			// 404 checker activated?
			if( $option_404 == 'yes' ) {
				// Check for 404 status
				if($this->check_404( $a->href )) {
					// We found a link leading to a 404 page
					$nofollow[] = 1;
				}
			}
		
			// True if the date is mot unspecified in the admin panel
			if( !is_null( $option_date ) ) {
				// Check if the post date is older than the one specified in the settings
				if( $post_date < $option_date ) {
					// If true, set a nofollow value for the rel attribute
					$nofollow[] = 2;
				}
			}
			
			// Is PR checker activated?
			if($option_pr != 'no') {
				// New instance of the HandlePR class
				$check_pr = new HandlePR;
				
				// The feature for Nofollow on PR unknown enabled?
				if( $option_pr_unknown == 'yes' ) {
					if( is_null( $link_pr ) ) {
						$nofollow[] = 3;
					}
				}
				
				// Check if the PR value is numeric
				if( is_numeric( $link_pr = $check_pr->getPagerank( $a->href ) ) ) {
					// If the PR is <= to the value specified in the admin settings
					if( $link_pr <= $option_pr ) {
						$nofollow[] = 4;
					}
				}
			}
			
			// Check if the link is blacklisted
			foreach( $blacklist_url as $blacklist_val ) {
				if( $parsed_url == $blacklist_val ) {
					// It is blacklisted, add nofollow to it
					$nofollow[] = 5;
				}
			}
			
			/**
			* We do not want our settings to affect internal URLs       
			* We also don't want to apply nofollow to whitelisted URLs 
			* If we find a match we'll destroy the nofollow array       
			*/
			foreach ($whitelist_url as $whitelist_val) {
				if( $parsed_url == $whitelist_val ) {
					// We found a whitelisted URL
					unset( $nofollow );
				}
			}
			if( $parsed_url == $blog_url ) {
				// We found an internal link
				unset( $nofollow );
			} else {
				if( $option_blank == 'yes' ) {
					$a->target = '_blank';
				}
			}
			
			// Add nofollow if we've found a match
			if( count( $nofollow ) > 0 ) {
				// We found 1 or more matches
				$a->rel = 'nofollow';
			}
			
			unset( $nofollow );
		}
		// Save the changes
		return $html->save();
	}
}
new HandleEL_handle_links;
?>