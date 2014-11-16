<?php
/*
Plugin Name:    R3DF - Multisite Redirector
Description:    Redirect WordPress multisite main site to sub-site
Plugin URI:     http://r3df.com/
Version:        1.0.0
Text Domain:    r3df-multisite-redirector
Author:         R3DF
Author URI:     http://r3df.com
Author email:   plugin-support@r3df.com
Copyright:      R-Cubed Design Forge
*/

/*  Copyright 2012-2014  R-Cubed Design Forge

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

// Redirect
add_action( 'template_redirect',  'r3df_template_redirect' );
function r3df_template_redirect() {
  global $current_site;
  
  if( 1 == $current_site->id && ! is_admin() ) {
    if ( $options = get_option( 'r3df_msr_options' ) ) {
      if ( isset( $options['redirect_url'] ) ) {
        wp_redirect( $options['redirect_url'] );
        exit();
      }
    }
  }
}


// Settings page for UI to get url to redirect to
add_action('admin_menu', 'register_r3df_msr_settings_page' );
add_action('admin_init', 'r3df_msr_settings' );

// Settings page instantiation
function register_r3df_msr_settings_page() {
  add_submenu_page( 'options-general.php', 'Multisite Redirector Settings', 'Multisite Redirector', 'manage_options', 'r3df-msr-settings-page', 'r3df_msr_settings_page' ); 
}

// Settings page
function r3df_msr_settings_page() { ?>
  <div class="wrap"><div id="icon-tools" class="icon32"></div>
    <h2>Multisite Redirect Settings</h2>
    <form action="options.php" method="post">
    <?php settings_fields('r3df_msr_options'); ?>
    <?php do_settings_sections('r3df_msr'); ?>
    <input class="button button-primary" name="Submit" type="submit" value="<?php esc_attr_e('Save Changes', 'r3df-multisite-redirector'); ?>" />
    </form>
  </div> 
<?php }

// Add the settings
function r3df_msr_settings(){
  // Option name in db
  register_setting( 'r3df_msr_options', 'r3df_msr_options', 'r3df_msr_options_validate' );

  // Section for plugin settings
  add_settings_section( 'r3df_msr_options', 'Main Plugin Settings', null, 'r3df_msr' );
  add_settings_field( 'redirect_url', 'URL of sub-site to redirect to: ', 'r3df_redirect_url_form_item', 'r3df_msr', 'r3df_msr_options' );
}

// Settings validator
function r3df_msr_options_validate( $input ) {
  // BLOOT - sanitize inputs...
  $newinput['redirect_url'] = trim($input['redirect_url']);
  return $newinput;
}

// Settings html
function r3df_redirect_url_form_item() {
  $options = get_option('r3df_msr_options');
  echo "<input id='redirect_url' name='r3df_msr_options[redirect_url]' size='50' type='text' value='". (isset($options['redirect_url'])? $options['redirect_url']: '') ."' />";
}

