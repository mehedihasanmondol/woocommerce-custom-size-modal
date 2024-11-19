<?php
/*
Plugin Name: Custom Size Selection Modal
Description: Displays a custom modal asking users to select a size before adding a product to the cart.
Version: 1.1
Author: Sabbir
*/

class VariationValidationModifier {
    private $file_path; // Path to the target file
    private $original_code = '.prototype.onAddToCart=function(i){t(this).is(".disabled")&&(i.preventDefault(),t(this).is(".wc-variation-is-unavailable")?a.alert(wc_add_to_cart_variation_params.i18n_unavailable_text):t(this).is(".wc-variation-selection-needed")&&a.alert(wc_add_to_cart_variation_params.i18n_make_a_selection_text))}';
    private $replacement_code = '.prototype.onAddToCart=function(i){/*variation_validation_function_code*/}';

    public function __construct() {
        // Define the target file path (adjust as needed)
        $this->file_path = ABSPATH . 'wp-content/plugins/woocommerce/assets/js/frontend/add-to-cart-variation.min.js';

        // Hook into plugin activation and deactivation
        register_activation_hook(__FILE__, [$this, 'replace_code']);
        register_deactivation_hook(__FILE__, [$this, 'restore_code']);
    }

    // Replace the specific code
    public function replace_code() {
        if (file_exists($this->file_path)) {
            $file_contents = file_get_contents($this->file_path);
            $modified_contents = str_replace($this->original_code, $this->replacement_code, $file_contents);
            file_put_contents($this->file_path, $modified_contents);
        }
    }

    // Restore the original code
    public function restore_code() {
        if (file_exists($this->file_path)) {
            $file_contents = file_get_contents($this->file_path);
            $restored_contents = str_replace($this->replacement_code, $this->original_code, $file_contents);
            file_put_contents($this->file_path, $restored_contents);
        }
    }
}

// Initialize the plugin
new VariationValidationModifier();


// Enqueue custom styles and scripts for the modal
function csm_enqueue_scripts() {
    // Enqueue jQuery if not already included
    wp_enqueue_script('jquery');
    
    // Enqueue custom modal CSS
    wp_enqueue_style('csm-custom-style', plugin_dir_url(__FILE__) . 'css/custom-modal-style.css');
    
    // Enqueue custom modal JS
    wp_enqueue_script('csm-custom-script', plugin_dir_url(__FILE__) . 'js/custom-modal-script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'csm_enqueue_scripts');

// Add the modal HTML to the footer
function csm_add_custom_modal() {
    ?>
    <div id="csm-modal" style="display: none;">
        <div id="csm-modal-content">
            <div class="csm-icon">&#33;</div>
            <h2>Sorry!</h2>
            <p>Please Select Size and try again</p>
            <button id="csm-close-modal">OK</button>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'csm_add_custom_modal');
