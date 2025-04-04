jQuery(document).ready(function ($) {
    let isUpdating = false; // Flag to track if the dropdown is being updated.
    let previousCategories = []; // Keep track of previously selected categories to avoid unnecessary updates.

    // Wait for the Gutenberg editor to load.
    wp.data.subscribe(function () {
        if ($('.edit-post-meta-boxes-area.is-side').length > 0 && $('#carbon-primary-category-wrapper').length === 0) {
            // Inject your custom sidebar content.
            $('.edit-post-meta-boxes-area.is-side').append(`
                <div id="carbon-primary-category-wrapper" class="components-panel__body is-opened">
				<h2 class="components-panel__body-title"><button type="button" aria-expanded="true" class="components-button components-panel__body-toggle">Primary Category</button></h2>
				<div class="components-form-token-field">
					<label class="components-form-token-field__label carbon-custom-label-sidebar">Select Primary Category</label>
					<select id="carbon_primary_category_select" style="width:100%; margin-bottom: 10px;">
						<option value="">Select a Category</option>
					</select>
				</div>
				</div>
            `);

            function updateCategoryDropdown() {
                if (isUpdating) return; // Prevent multiple updates.
                isUpdating = true;

                // Get the categories currently selected for the post.
                const selectedCategories = wp.data.select('core/editor').getEditedPostAttribute('categories');

                // If the selected categories haven't changed, skip updating.
                if (JSON.stringify(selectedCategories) === JSON.stringify(previousCategories)) {
                    isUpdating = false;
                    return;
                }

                // Save the current state of selected categories.
                previousCategories = [...selectedCategories];

                // Clear the dropdown options except the first one.
                $('#carbon_primary_category_select').find('option:not(:first)').remove();

                // If no categories are selected, keep only the default option.
                if (!selectedCategories || selectedCategories.length === 0) {
                    isUpdating = false;
                    return;
                }

                // Fetch and populate the selected categories in the dropdown.
                let categoriesLoaded = 0;
                selectedCategories.forEach(function (categoryId) {
                    $.ajax({
                        url: wpApiSettings.root + 'wp/v2/categories/' + categoryId,
                        method: 'GET',
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
                        },
                        success: function (category) {
                            // Only add category if it hasn't already been added.
                            if ($('#carbon_primary_category_select option[value="' + category.id + '"]').length === 0) {
                                $('#carbon_primary_category_select').append(
                                    `<option value="${category.id}">${category.name}</option>`
                                );
                            }

                            // Track the loading process to avoid premature dropdown updates.
                            categoriesLoaded++;
                            if (categoriesLoaded === selectedCategories.length) {
                                // Set the currently saved primary category value.
                                const currentPrimaryCategory = wp.data.select('core/editor').getEditedPostAttribute('meta').carbon_primary_category;
                                $('#carbon_primary_category_select').val(currentPrimaryCategory);

                                // Set the updating flag to false after the process is done.
                                isUpdating = false;
                            }
                        },
                    });
                });
            }

            // Run the dropdown update function initially and whenever categories change.
            wp.data.subscribe(function () {
                if (wp.data.select('core/editor').getEditedPostAttribute('categories')) {
                    updateCategoryDropdown();
                }
            });

            // Save the selected value when it changes.
            $('#carbon_primary_category_select').change(function () {
                const selectedCategory = $(this).val();
                wp.data.dispatch('core/editor').editPost({
                    meta: { carbon_primary_category: selectedCategory },
                });
            });
        }
    });
});
