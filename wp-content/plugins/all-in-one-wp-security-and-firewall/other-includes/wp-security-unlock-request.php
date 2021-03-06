<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php bloginfo('name'); ?></title>
<?php
global $aio_wp_security;
$display_form = true;
//Make this page look like the WP login page
wp_head(); 
wp_admin_css( 'wp-admin', true );
wp_admin_css( 'colors-fresh', true );
$login_header_url   = __( 'http://wordpress.org/' );
$login_header_title = __( 'Powered by WordPress' );
?>
</head>

<body class="login login-action-login wp-core-ui aiowps-unlock-request">
<div id="login">
<h1><a href="<?php echo esc_url( $login_header_url ); ?>" title="<?php echo esc_attr( $login_header_title ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
    
<?php
if (isset($_POST['aiowps_unlock_request']))
{
    //This catches the $_POST from the "Request Unlock" button on the main WP login page
    isset($_POST['aiowps-unlock-string-info'])?($unlock_encoded_info = strip_tags(trim($_POST['aiowps-unlock-string-info']))):($unlock_encoded_info = '');
    $unlock_secret_string = $aio_wp_security->configs->get_value('aiowps_unlock_request_secret_key');
    $submitted_encoded_string = base64_encode($_POST['aiowps-unlock-temp-string'].$unlock_secret_string);
    if($submitted_encoded_string !== $unlock_encoded_info)
    {
        //Someone somehow landed on this page directly without clicking the unlock button on login form
        echo '<div id="login_error"><strong>ERROR</strong>: Unable to process your request because you tried to access this page directly.</div>';
    }
    else if($display_form)
    {
        echo display_unlock_form();
    }
} //End if block

if (isset($_POST['aiowps_wp_submit_unlock_request']))
{
    //This catches the $_POST when someone submits the form from our special unlock request page where visitor enters email address
    $errors = '';

    $email = trim($_POST['aiowps_unlock_request_email']);
    if (empty($email) || !is_email($email))
    {
        $errors .= '<p>'.__('Please enter a valid email address','aiowpsecurity').'</p>';
    }
    
    if($errors){
        $display_form = true;
        echo '<div id="login_error">'.$errors.'</div>';
        echo display_unlock_form($email);
    }else{
        $locked_user = get_user_by('email', $email);
        if(!$locked_user){
            //user with this email does not exist in the system
            $errors .= '<p>'.__('User account not found!','aiowpsecurity').'</p>';
            echo '<div id="login_error">'.$errors.'</div>';
        }else{
            //Process unlock request
            //Generate a special code and unlock url
            $ip = AIOWPSecurity_Utility_IP::get_user_ip_address(); //Get the IP address of user
            $ip_range = AIOWPSecurity_Utility_IP::get_sanitized_ip_range($ip); //Get the IP range of the current user

            $unlock_url = AIOWPSecurity_User_Login::generate_unlock_request_link($ip_range);
            if (!$unlock_url){
                //No entry found in lockdown table with this IP range
                $error_msg = '<p>'.__('Error: No locked entry was found in the DB with your IP address range!','aiowpsecurity').'</p>';
                echo '<div id="login_error">'.$error_msg.'</div>';
            }else{
                //Send an email to the user
                AIOWPSecurity_User_Login::send_unlock_request_email($email, $unlock_url);
                echo '<p class="message">An email has been sent to you with the unlock instructions.</p>';
            }
        }
        $display_form = false;
    }
}
?>
</div> <!-- end #login -->

</body>
</html>
<?php 

function display_unlock_form($email='')
{
    ob_start();
            //Display the unlock request form
    $unlock_form_msg = '<p>You are here because you have been locked out due to too many incorrect login attempts.</p>
            <p>Please enter your email address and you will receive an email with instructions on how to unlock yourself.</p>'
?>
<div class="message"><?php echo $unlock_form_msg; ?></div>
<form name="loginform" id="loginform" action="<?php echo wp_login_url(); ?>" method="post">
	<p>
		<label for="aiowps_unlock_request_email"><?php _e('Email Address', 'aiowpsecurity'); ?><br>
		<input type="text" name="aiowps_unlock_request_email" id="aiowps_unlock_request_email" class="input" value="<?php echo $email; ?>" size="20"></label>
	</p>
        <p class="submit">
		<input type="submit" name="aiowps_wp_submit_unlock_request" id="aiowps_wp_submit_unlock_request" class="button button-primary button-large" value="Send Unlock Request">
	</p>
</form>
<?php    
    $output = ob_get_contents();
    ob_end_clean();  
    return $output;
}