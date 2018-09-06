<?php
/**
 * Performs all sanitization functions required to save the option values to
 * the database.
 *
 * @package Custom_Admin_Settings
 */

/**
 * Performs all sanitization functions required to save the option values to
 * the database.
 *
 * This will also check the specified nonce and verify that the current user has
 * permission to save the data.
 *
 * @package Custom_Admin_Settings
 */
class Serializer {

  public function init() {
    add_action( 'admin_post', array( $this, 'save' ) );
  }

  public function save() {

    // First, validate the nonce.
    // Secondly, verify the user has permission to save.
    if ( ! ( $this->has_valid_nonce() && current_user_can( 'manage_options' ) ) ) {
      // TODO: Display an error message.
    }
    // If the above are valid, save the option.
    if ( null !== wp_unslash( $_POST['acme-message'] ) ) {

      $value = sanitize_text_field( $_POST['acme-message'] );
      update_option( 'tc-custom-data', $value );

    }

    self::var_error_log(get_taxonomies());

    $this->redirect();

  }

  private static function var_error_log( $object=null ){
    ob_start();                    // start buffer capture
    var_dump( $object );           // dump the values
    $contents = ob_get_contents(); // put the buffer into a variable
    ob_end_clean();                // end capture
    error_log( $contents );        // log contents of the result of var_dump( $object )
  }



  /**
   * Determines if the nonce variable associated with the options page is set
   * and is valid.
   *
   * @access private
   *
   * @return boolean False if the field isn't set or the nonce value is invalid;
   *                 otherwise, true.
   */
  private function has_valid_nonce() {

    // If the field isn't even in the $_POST, then it's invalid.
    if ( ! isset( $_POST['acme-custom-message'] ) ) { // Input var okay.
      return false;
    }

    $field  = wp_unslash( $_POST['acme-custom-message'] );
    $action = 'acme-settings-save';

    return wp_verify_nonce( $field, $action );

  }

  /**
   * Redirect to the page from which we came (which should always be the
   * admin page. If the referred isn't set, then we redirect the user to
   * the login page.
   *
   * @access private
   */
  private function redirect() {

    // To make the Coding Standards happy, we have to initialize this.
    if ( ! isset( $_POST['_wp_http_referer'] ) ) { // Input var okay.
      $_POST['_wp_http_referer'] = wp_login_url();
    }

    // Sanitize the value of the $_POST collection for the Coding Standards.
    $url = sanitize_text_field(
      wp_unslash( $_POST['_wp_http_referer'] ) // Input var okay.
    );

    // Finally, redirect back to the admin page.
    wp_safe_redirect( urldecode( $url ) );
    exit;

  }


}