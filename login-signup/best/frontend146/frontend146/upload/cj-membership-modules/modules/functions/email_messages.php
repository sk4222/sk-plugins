<?php 
$cjfm_email_message['send-password'] = <<<EOF
Hello %%display_name%%,
<p>We received a request to reset the password of your %%site_name%% account (%%user_login%%).</p>
<p>Please find your login details below:</p>
<p>
Username: %%user_login%%<br />
Password: %%cjfm_rp%%<br />
Login URL: %%login_url%%
</p>
<p>If you have not sent this request, please ignore this email and nothing will change on your account.</p>
<p>%%signature%%</p>
EOF;

$cjfm_email_message['verify-email-message'] = <<<EOF
<p>Dear User,</p>
<p>Thank you for creating an account on %%site_name%%.</p>
<p>Please follow the link below to confirm your email address and activate your account.</p>
<p><a href="%%verify_email_link%%">%%verify_email_link%%</a></p>
<p>%%signature%%</p>
EOF;

$cjfm_email_message['send-password-link'] = <<<EOF
Hello %%display_name%%,
<p>We received a request to reset the password of your %%site_name%% account (%%user_login%%).</p>
<p>Please follow the link below to reset your password:</p>
<p>
%%reset_password_confirmation_link%%
</p>
<p>If you have not sent this request, please ignore this email and nothing will change on your account.</p>
<p>%%signature%%</p>
EOF;

$cjfm_email_message['welcome-email-message'] = <<<EOF
Dear %%display_name%%,
<p>Congratulations!!! Your account has been successfully created on %%site_name%%.</p>
<p>Please find the login details below:</p>
<p>
Username: %%user_login%% <br />
Password: %%cjfm_rp%% <br />
Login URL: %%login_url%%
</p>
<p>Please feel free to contact us, We will be glad to help.</p>
<p>%%signature%%</p>
EOF;

$cjfm_email_message['account-awaiting-approval'] = <<<EOF
Dear %%display_name%%,
<p>Thank you for creating an account on %%site_name%%.</p>
<p>Your account is being verified by our quality team. We will get back to you with your account status shortly.</p>
<p>In the meantime, please feel free to contact us for any queries.</p>
<p>%%signature%%</p>
EOF;

$cjfm_email_message['account-awaiting-approval-admin'] = <<<EOF
Dear Admin,
<p>There are new accounts awaiting your approvals.</p>
<p>Please login to <a href="%%site_url%%/wp-admin">%%site_name%% dashboard</a> to approve or decline these accounts.</p>
EOF;

$cjfm_email_message['account-declined'] = <<<EOF
Dear User,
<p>Thank you for your request for creating an account on %%site_name%%.</p>
<p>Your account has been <b>declined</b> due to following reasons:</p>
<p>
Reason One
Reason Two
Reason Three
</p>
<p>%%signature%%</p>
EOF;

$cjfm_email_message['invitation-approved-message'] = <<<EOF
<p>Dear User,</p>
<p>You have been invited to get a new user account on <a href="%%site_url%%">%%site_name%%</a> by %%display_name%%.</p>
<p>To start your user registration, please follow this link:<br />
<a href="%%invitation_link%%">%%invitation_link%%</a></p>
<p>After you do this, you will be able setup your new %%site_name%% account.</p>
<p>%%signature%%</p>
EOF;

$cjfm_email_message['invitation-declined-message'] = <<<EOF
<p>Dear User,</p>
<p>Your invitation request is <b>not accepted</b> due to following reasons.</p>
<p>
Reason One
Reason Two
</p>
EOF;

