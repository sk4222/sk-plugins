<?php
	
global $current_user;
get_currentuserinfo();
$user_info = cjfm_user_info($current_user->ID);
$item_name = cjfm_item_info('item_name');
$item_type = cjfm_item_info('item_type');

$quick_setup_quide_link = '<a href="'.cjfm_item_info('quick_start_guide_url').'" target="_blank">Quick Start Guide</a>';
$shortcode_generator_link = '<a href="http://docs.cssjockey.com/cjfm/using-shortcode-generator/" target="_blank">Shortcode Generator</a>';
$menu_icon = '<img src="'.cjfm_item_path('framework_url').'/assets/admin/img/menu-icon.png" width="16" />';

$welcome_msg = <<<EOF
<p>
<b>Hello {$user_info['display_name']}</b>,<br>
Thank you for using our {$item_name} WordPress plugin.
</p>
<p>
I am here to assist you setting up this plugin on your website and I'll also show you some awesome features that comes with this plugin.
</p>
<p>
If you like, you can also check out our {$quick_setup_quide_link} to setup this pugin and learn more about the features.
</p>
EOF;

$end_tour_msg = <<<EOF
<p>
<b>Thank You, {$user_info['display_name']}</b>,<br>
</p>
<p>
Its been nice interacting with you. In case you need me again,<br> you can call me from <b>Help & Support</b> menu from the navigation on plugin settigns page.
</p>
<p>
Here are a few useful links for further help and contact with CSSJockey team.
</p>
<ul>
<li><a href="http://docs.cssjockey.com/cjfm/quick-start-guide/" target="_blank">Quick Start Guide</a></li>
<li><a href="http://docs.cssjockey.com/cjfm" target="_blank">Documentation</a></li>
<li><a href="http://cssjockey.com/support/forums/front-end-membership-modules-wordpress-plugin/" target="_blank">Support Fourm</a></li>
<li><a href="http://cssjockey.com/support/forums/front-end-membership-modules-wordpress-plugin/new-feature-requests/" target="_blank">Feature Requests</a></li>
<li><a href="http://cssjockey.com/support/forums/front-end-membership-modules-wordpress-plugin/bugs-issues/" target="_blank">Report Bugs</a></li>
</ul>
<p>
<h4>Good luck with your project.</h4>
</p>
EOF;

$page_setup = <<<EOF
<h3>Page Setup</h3>
<p>
First things first, This plugin requires you to create a few pages and insert proper shortcodes for the forms and other functionality.
</p>
<p>
For quick setup, you can click <b>Create Pages</b> and this will create required pages with basic shortcode options automatically.
You can update the content for these pages as per your requirements and more options to the shortcodes via our {$shortcode_generator_link}.
</p>
<p>
You can see <a href="http://docs.cssjockey.com/cjfm/page-setup/" target="_blank">Page Setup Documentation</a> here.
</p>
EOF;

$show_pages = <<<EOF
<p>
Alright, feel free to re-arrange the pages if you like.<br>
Let's check out how you can use various shortcodes that come with this plugin.
</p>
EOF;

$shortcode_generator = <<<EOF
<h3>Using Shortcode Generator</h3>
<p>
In the WYSIWYG editor, do you'll see a leaf icon. {$menu_icon}
<br>
Please click that and you will see a lightbox panel with our Shortcode Generator. 
All shortcodes from all our products will be listed in this panel which will help you use these shortcodes easily and from single place.
</p>
<p>
From the first dropdown, select a product and then from the second dropdown, select the shortcode you would like to use. Specify shortcode values and click insert shortcode button and this will be added to the WYSIWYG editor.
</p>
<p>
<b>Update Shortcodes</b><br>
Please update shortcodes for all pages created under Page Setup section as per your requirements.
</p>
EOF;

$social_media_setup = <<<EOF
<h3>Social Media Connect</h3>
<p>
This plugin comes with in-built option to enable registeration and login via social media services like Facebook, Twitter, Google and LinkedIn.
</p>
<p>
Please folow the instructions for each social service setup on this page and supply the API credentials for each service you would like to use.
</p>
<p>
Once you are done with the API kyes setup, you use {$shortcode_generator_link} to include <a href="http://docs.cssjockey.com/cjfm/social-media-connect-shortcode/" target="_blank">Social Login shortcode</a> on any page or sidebar text widget.
</p>
EOF;

$customize_form_fields = <<<EOF
<h3>Customize form fields</h3>
<p>
Here you can customize the registration and edit profile form fields as per your requirements. You can update sort order and even export custom fields data to use on another website.
</p>
<p>
Click <b>Add New Field</b> button and check out all the fields you can add to the form. Try adding and updating a custom field to know how it works.
</p>
<p>
Also check <b>Update Sort Order</b> and <b>Import Export custom fields</b> features on this page.
</p>
<p>
Please <a href="http://docs.cssjockey.com/cjfm/custom-registration-form-fields/" target="_blank">click here</a> to check out full documentation for this feature.
</p>
EOF;

$customize_emails = <<<EOF
<h3>Customize Email Messages</h3>
<p>
Here you can customize email messages sent to the user on registration, reset password and other actions taken by user or admin.
</p>
<p>
<b>Using Variables</b><br>
Scroll down to the bottom of this page to check out various variables you can use within the email messages to personalize the messages.
</p>
<p>
Please <a href="http://docs.cssjockey.com/cjfm/customize-email-messages/" target="_blank">click here</a> to check out full documentation on email message customization.
</p>
EOF;

$spam_protection = <<<EOF
<h3>Spam Protection</h3>
<p>
Here you can enable spam protection for the forms generated via the shortcodes. You can choose for custom Question and Answer check or enable Google reCaptcha and setup its theme.
</p>
<p>
Please <a href="http://docs.cssjockey.com/cjfm/configuring-spam-protection/" target="_blank">click here</a> to check out full documentation on spam protection.
</p>
EOF;

$basic_configuration = <<<EOF
<h3>Plugin Configuration</h3>
<p>
This plugin comes with a lot of configuration options. Please follow the links below to learn more about configuraiton options.
</p>
<ol>
    <li><a href="http://docs.cssjockey.com/cjfm/configure-registration-type/" target="_blank">Configure registration type</a></li>
    <li><a href="http://docs.cssjockey.com/cjfm/handling-password-generation-for-new-users/" target="_blank">Configure password generation</a></li>
    <li><a href="http://docs.cssjockey.com/cjfm/email-address-verification/" target="_blank">Configure email verification</a></li>
    <li><a href="http://docs.cssjockey.com/cjfm/sync-custom-form-fields-with-wordpress-profile-fields/" target="_blank">Configure custom fields sync with WordPress user profile fields</a></li>
    <li><a href="http://docs.cssjockey.com/cjfm/configuring-wordpress-defaults/" target="_blank">Configure WordPress Defaults</a></li>
    <li><a href="http://docs.cssjockey.com/cjfm/writing-custom-css-and-javascript/" target="_blank">Using custom CSS and Javascript</a></li>
    <li><a href="http://docs.cssjockey.com/cjfm/configuring-user-avatar-settings/" target="_blank">Configure User Avatar Type</a></li>
</ol>
EOF;

$restrict_content_setup = <<<EOF
<h3>Restrict Content</h3>
<p>
Here you can restrict pages, categories, tags, taxonomies content based on the user login status. Make selection for each section based on your requirements and configure the message users will see for restricted content.
</p>
<p>
You can also use these two shortcodes to toggle content for visitors and users on any page or post.
</p>
<p>
Check out full <a href="http://docs.cssjockey.com/cjfm/restricting-content-only-for-users/" target="_blank">documentation</a> on Restricting content.
</p>
EOF;




$cjfm_assistant_steps[] = array(
	'id' => 'welcome',
	'text' => $welcome_msg,
	'button_text' => 'Ok, Let\'s Get Started',
	'callback' => cjfm_callback_url('cjfm_page_setup'),
	'cancel_text' => 'No, I will check out my self.',
	'cancel_link' => cjfm_assistant_url('end-tour', cjfm_callback_url('core_welcome')),
);
$cjfm_assistant_steps[] = array(
	'id' => 'page setup',
	'text' => $page_setup,
	'button_text' => 'Ok, Page Setup done, let\'s move ahead.',
	'callback' => admin_url('edit.php?post_type=page'),
	'cancel_text' => 'End Tour',
	'cancel_link' => cjfm_assistant_url('end-tour', cjfm_callback_url('core_welcome')),
);
$cjfm_assistant_steps[] = array(
	'id' => 'show pages',
	'text' => $show_pages,
	'button_text' => 'Alright! Show me the Shortcode Generator.',
	'callback' => admin_url('post.php').'?post='.cjfm_get_option('page_login').'&action=edit',
	'cancel_text' => 'End Tour',
	'cancel_link' => cjfm_assistant_url('end-tour', cjfm_callback_url('core_welcome')),
);
$cjfm_assistant_steps[] = array(
	'id' => 'shortcode generator',
	'text' => $shortcode_generator,
	'button_text' => 'Got it, Lets move ahead',
	'callback' => cjfm_callback_url('cjfm_configuration'),
	'cancel_text' => 'End Tour',
	'cancel_link' => cjfm_assistant_url('end-tour', cjfm_callback_url('core_welcome')),
);
$cjfm_assistant_steps[] = array(
	'id' => 'configuration',
	'text' => $basic_configuration,
	'button_text' => 'Got it, let\'s move ahead.',
	'callback' => cjfm_callback_url('cjfm_social_login'),
	'cancel_text' => 'End Tour',
	'cancel_link' => cjfm_assistant_url('end-tour', cjfm_callback_url('core_welcome')),
);
$cjfm_assistant_steps[] = array(
	'id' => 'social-media',
	'text' => $social_media_setup,
	'button_text' => 'Cool, Lets go ahead',
	'callback' => cjfm_callback_url('cjfm_customize_fields'),
	'cancel_text' => 'End Tour',
	'cancel_link' => cjfm_assistant_url('end-tour', cjfm_callback_url('core_welcome')),
);
$cjfm_assistant_steps[] = array(
	'id' => 'customize-form-fields',
	'text' => $customize_form_fields,
	'button_text' => 'Got it, Lets move ahead',
	'callback' => cjfm_callback_url('cjfm_customize_emails'),
	'cancel_text' => 'End Tour',
	'cancel_link' => cjfm_assistant_url('end-tour', cjfm_callback_url('core_welcome')),
);
$cjfm_assistant_steps[] = array(
	'id' => 'customize-emails',
	'text' => $customize_emails,
	'button_text' => 'Got it, Lets go ahead',
	'callback' => cjfm_callback_url('cjfm_spam_protection'),
	'cancel_text' => 'End Tour',
	'cancel_link' => cjfm_assistant_url('end-tour', cjfm_callback_url('core_welcome')),
);
$cjfm_assistant_steps[] = array(
	'id' => 'spam-protection',
	'text' => $spam_protection,
	'button_text' => 'Alright, Lets go ahead',
	'callback' => cjfm_callback_url('cjfm_restrict_content'),
	'cancel_text' => 'End Tour',
	'cancel_link' => cjfm_assistant_url('end-tour', cjfm_callback_url('core_welcome')),
);
$cjfm_assistant_steps[] = array(
	'id' => 'restrict-content',
	'text' => $restrict_content_setup,
	'button_text' => 'Got It, Lets go ahead',
	'callback' => cjfm_callback_url('core_welcome'),
	'cancel_text' => 'End Tour',
	'cancel_link' => cjfm_assistant_url('end-tour', cjfm_callback_url('core_welcome')),
);
$cjfm_assistant_steps[] = array(
	'id' => 'support-msg',
	'text' => $end_tour_msg,
	'button_text' => 'Thanks, I\'ll take it from here.',
	'callback' => cjfm_assistant_url('end-tour', cjfm_callback_url('core_welcome')),
	'cancel_text' => 'End Tour',
	'cancel_link' => cjfm_assistant_url('end-tour', cjfm_callback_url('core_welcome')),
);










