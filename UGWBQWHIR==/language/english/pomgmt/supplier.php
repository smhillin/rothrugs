<?php
// Heading
$_['heading_title']      = 'Suppliers';
$_['button_insert']       = 'Add';
// Text
$_['text_success']       = 'Success: You have modified suppliers!';
$_['text_list']             = 'Supplier List';
$_['text_form']             = 'Add Supplier';
$_['text_default']       = 'Default';
$_['text_image_manager'] = 'Image Manager';
$_['text_percent']       = 'Percentage';
$_['text_amount']        = 'Fixed Amount';
$_['text_viewpo']        = 'View Orders';
$_['text_edit']        = 'Edit';



// Column
$_['column_id']        = 'Supplier ID';
$_['column_name']        = 'Supplier Name';
$_['column_currency']    = 'Currency';
$_['column_email']  	 = 'Email';
$_['column_action']      = 'Action';

// Entry
$_['entry_id']        = 'Supplier ID';
$_['entry_name']         = 'Supplier Name';
$_['entry_firstname']    = 'Contact person First Name';
$_['entry_lastname']     = 'Contact person Last Name';
$_['entry_store']        = 'Stores';
$_['entry_currency']     = 'Currency';
$_['entry_email']   	 = 'Email';
$_['entry_telephone']    = 'Telephone';
$_['entry_comments']    = 'Comments';
$_['entry_notes']    = 'Notes';
$_['entry_address1']   	 = 'Address 1';
$_['entry_address2']   	 = 'Address 2';
$_['entry_country']   	 = 'Country';
$_['entry_city']   	     = 'City';
$_['entry_zone']   	     = 'Region / State';
$_['entry_postcode']     = 'Postcode';
$_['entry_status']       = 'Status';
$_['entry_tax']          = 'Tax Enabled';
$_['entry_taxrate']          = 'Tax Rate';
$_['entry_maxshipdays']          = 'Maximum shipping days';
$_['entry_dropshipfee']          = 'Delivery Charge';
$_['entry_itemdel_fee']          = 'Additional Item <br> delivery charge';
$_['entry_username']          = 'User Name';
$_['entry_gp_percent']          = 'Gross Profit%';
$_['entry_exportpath']         = 'Export Path';
$_['entry_fileattach']         = 'Email attachment';
$_['entry_supplierurl']         = 'Supplier Store URL';
$_['entry_orderurl']           = 'Supplier order page URL';
$_['entry_im']                 = 'Supplier IM ID <br>(Skype, GTalk etc)';

// Help
$_['help_dropshipfee']       = 'This field can be used to store the basic minimum delivery charge or the dropship fees for each order.';
$_['help_username'] 		 ='A user account will be created for this supplier and he will be able to login to your admin to view the orders for him.';
$_['help_grossprofit'] 	     ='The sales price of the product will be calculated automatically based on your GP setting. This overrides the setting assigned for the store.';
$_['help_exportpath'] 		 ='This is the path where the PO will be exported as CSV when it is generated. Please specify the full path including the file name.';
$_['help_email'] 			 ='The specified file will be attached to the PO emails sent to this supplier. Provide the full path of the file.';
$_['help_comments'] 		 ='This field will appear in PO sent to the supplier';
$_['help_itemdel_fee']		 ='This field can be used to set delivery charge applicable for each additional additional item ordered. This is applied only when quantity ordered is more than 1.';
// Error
$_['error_permission']   = 'Warning: You do not have permission to modify suppliers!';
$_['error_name']         = 'Supplier Name must be between 3 and 64 characters!';
$_['error_firstname']    = 'First Name must be between 2 and 32 characters!';
$_['error_lastname']     = 'Last Name must be between 2 and 32 characters!';
$_['error_address1']     = 'Address 1 must be between 3 and 128 characters!';
$_['error_city']         = 'City must be between 2 and 128 characters!';
$_['error_country']      = 'Please select a country!';
$_['error_email']        = 'Please enter valid email!';
$_['error_currency']     = 'Please select a currency!';
$_['error_taxrate']     = 'Tax rate must be selected!';
$_['error_zone']         = 'Please select a region / state!';
$_['error_telephone']    = 'Telephone must be between 3 and 32 characters!';
$_['error_supplierurl']    = 'Supplier URL is mandatory!';
$_['error_product']      = 'Warning: This supplier cannot be deleted as it is currently assigned to %s products!';
?>
