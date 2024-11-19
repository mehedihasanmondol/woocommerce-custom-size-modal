jQuery(document).ready(function($) {
    // Intercept the "Add to Cart" button click on WooCommerce single product pages
    $(document).on('click', '.single_add_to_cart_button', function(e) {
        var form = $(this).closest('form.cart');
        var variationsExist = form.find('.variations select').length > 0;
        var allSelected = true;

        // Only check if there are variations (e.g., size, color)
        if (variationsExist) {
            // Check each variation to see if a selection has been made
            form.find('.variations select').each(function() {
                if ($(this).val() === '') {
                    allSelected = false;
                }
            });

            // If not all variations are selected, prevent the default WooCommerce behavior
            if (!allSelected) {
                e.preventDefault(); // Prevent form submission
                $('#csm-modal').fadeIn(); // Show custom modal
            }
        }
    });

    // Close the custom modal when clicking the "OK" button
    $('#csm-close-modal').on('click', function() {
        $('#csm-modal').fadeOut();
    });
});
