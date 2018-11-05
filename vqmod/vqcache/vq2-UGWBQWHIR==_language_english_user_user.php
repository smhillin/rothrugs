<?php
// Heading
$_['heading_title']     = 'Users';

// Text
$_['text_success']      = 'Success: You have modified users!';
$_['text_list']         = 'User List';
$_['text_add']          = 'Add User';
$_['text_edit']         = 'Edit User';

// Column
$_['column_username']   = 'Username';
$_['column_status']     = 'Status';
$_['column_date_added'] = 'Date Added';
$_['column_action']     = 'Action';

// Entry
$_['entry_2fa_title']	= 'Two Factor Authentication';
$_['entry_2fa_enable']	= 'Enable Two Factor Authenitcation';
$_['entry_2fa_qr']	= 'QR Code';
$_['entry_2fa_secret']	= 'Backup Secret Key';
$_['help_2fa_info']	= 'To use Two Factor Authentication, you will need to download the free Google Authenticator app on your mobile device from you device\'s app store.<br /><br />The first time you use the app, you will be able to to scan the QR Code to add your login to your mobile device.<br /><br />Afterwhich, each time you go to login to your admin, use the app to get the One Time Login Code, which you enter in the login form along with your username and password as normal.';
$_['help_2fa_enable']	= 'Enabling two factor authentication for this user will prompt the user when logging in to enter the code given to them by the Google Authenticator App.<br /><br />Disabling Two Factor Authentication will clear any previously saved Secret Key.';
$_['help_2fa_qr']	= 'Scan the QR Code using your mobile Google Authenticator App';
$_['help_2fa_secret']	= 'This Secret Key should be written down and kept in a safe place.<br />It can be used in the Google Authenticator App if for some reason the data on your moble device gets lost and you have to reinstall the Google Authenticator app.';


$_['entry_username']   	= 'Username';
$_['entry_user_group'] 	= 'User Group';
$_['entry_password']   	= 'Password';
$_['entry_confirm']    	= 'Confirm';
$_['entry_firstname']  	= 'First Name';
$_['entry_lastname']   	= 'Last Name';
$_['entry_email']      	= 'E-Mail';
$_['entry_image']      	= 'Image';
$_['entry_status']     	= 'Status';

// Error
$_['error_permission'] 	= 'Warning: You do not have permission to modify users!';
$_['error_account']    	= 'Warning: You can not delete your own account!';
$_['error_exists']     	= 'Warning: Username is already in use!';
$_['error_username']   	= 'Username must be between 3 and 20 characters!';
$_['error_password']   	= 'Password must be between 4 and 20 characters!';
$_['error_confirm']    	= 'Password and password confirmation do not match!';
$_['error_firstname']  	= 'First Name must be between 1 and 32 characters!';
$_['error_lastname']   	= 'Last Name must be between 1 and 32 characters!';