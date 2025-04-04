jQuery(document).ready(function($) {        
	function movePrimaryCategoryDropdown() {
		var $dropdownContainer = $('#carbon-primary-category-dropdown');
		var $nonceField = $('input[name="carbon_primary_category_nonce"]');

		// Check if the container and category box exist
		if ($('#categorydiv').length > 0 && $dropdownContainer.length > 0) {
			// Add a heading to the primary select field
			var $heading = $('<h4/>', {
				text: 'Primary Category'
			}).css({
				'margin-top': '20px',
				'margin-bottom': '5px',
				'font-size': '14px'
			});

			var $desctiptionText = $('<span/>', {
				text: 'Select when you have multiple categories. Leave default to use the first one.'
			}).css({
				'margin-top': '10px',
				'font-style': 'italic',
				'display'	: 'block',
				'font-size': '13px'
			});

			// Move the dropdown to the end of the category list and prepend the heading
			$('#categorydiv .inside').append($dropdownContainer.prepend($heading).append($desctiptionText));

			$dropdownContainer.append($nonceField);

			// Optional: Adjust the styling of the dropdown to match the admin UI
			$dropdownContainer.find('select').css({
				'width': '100%',
				'margin-top': '5px'
			});
		}

		// Remove the old placeholder if it exists
		$('#carbon_primary_category').remove();
	}

	// Function to update the primary category dropdown
	function updatePrimaryCategoryDropdown() {
		var selectedCategories = [];
		var savedPrimaryCategory = $('#carbon_primary_category_select').val(); // Fetch the saved primary category value

		$('#categorychecklist input:checked').each(function() {
			var categoryId = $(this).val();
			var categoryName = $(this).parent().text().trim();
			selectedCategories.push({id: categoryId, name: categoryName});
		});

		// Clear existing options except the placeholder
		$('#carbon_primary_category_select').find('option:not(:first)').remove();

		// Append new options from selected categories
		$.each(selectedCategories, function(index, category) {
			var isSelected = category.id == savedPrimaryCategory; // Check if this category is the saved primary category
			$('#carbon_primary_category_select').append($('<option>', {
				value: category.id,
				text: category.name,
				selected: isSelected // Maintain the selected status based on the saved value
			}));
		});
	}

	// Run the function on document ready to move the dropdown
	movePrimaryCategoryDropdown();

	// Initial update
	updatePrimaryCategoryDropdown();

	// Update dropdown on category selection change
	$('#categorychecklist').on('change', 'input[type="checkbox"]', updatePrimaryCategoryDropdown);
});