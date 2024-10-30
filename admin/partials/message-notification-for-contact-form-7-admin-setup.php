
<?php

global $admin_message_template;
$admin_message_template = "A new contact form submission is received and is published on {storename}. You can check it out Customer Email: {customeremail} , Subject: {customersubject} , Message: {customermessage} , Customer number: {customernumber} . Thank you.\n\n{storename}";

global $whatso_number;
$whatso_number = "918141001180";
$enable_notification="0";

$data = get_option('admin_settings');
$data = json_decode($data);
$whatso_email = $data->whatso_email;

if (isset($_POST['getbutton'])) {

    $email = isset($_POST['whatso_email']) ? sanitize_email(wp_unslash($_POST['whatso_email'])) : '';
    $from_number = isset($_POST['whatso_number']) ? sanitize_text_field(wp_unslash($_POST['whatso_number'])) : '';

    $flag = 1;
    if (empty($email)) {
        $flag = 0;
        $error_message = '';
        $error_message .= '<div class="notice notice-error is-dismissible">';
        $error_message .= '<p>' . esc_html('Please enter Email id.') . '</p>';
        $error_message .= '</div>';
        echo wp_kses_post($error_message);
    } else {

        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $flag = 0;
            $error_message = '';
            $error_message .= '<div class="notice notice-error is-dismissible">';
            $error_message .= '<p>' . esc_html('Please enter correct Email id.') . '</p>';
            $error_message .= '</div>';
            echo wp_kses_post($error_message);
        }
    }
    if ($flag === 1) {
        if (empty(get_option('admin_settings')) || !empty(get_option('admin_settings'))) {
            $update_notifications_arr = array(
                'whatso_email' => $email,
                'from_number' => $from_number,
            );
            $result = update_option('admin_settings', wp_json_encode($update_notifications_arr));
            if ($result) {
                $success = '';
                $success .= '<div class="notice notice-success is-dismissible">';
                $success .= '<p>' . esc_html('An email is sent to you with your username and password. Please insert the same in below form.') . '</p>';
                $success .= '</div>';
                echo wp_kses_post($success);

            }
            // call function to get credentials
            Message_Notification_For_Contact_Form_7::whatso_get_user_credentials("$email");
        }

    }
}

if (isset($_POST['submitbutton'])) {

    if (! isset( $_POST['save_admin_details'] )
        || ! wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['save_admin_details'])), 'save_details' )
    ) {
        return;
    }elseif($whatso_email==""){
        $flag = 0;
        $error_message1 = '';
        $error_message1 .= '<div class="notice notice-error is-dismissible">';
        $error_message1 .= '<p>' . esc_html('Please enter Email id.') . '</p>';
        $error_message1 .= '</div>';
        echo wp_kses_post($error_message1);

    }

    else {


        $whatso_username = isset($_POST['whatso_username']) ? sanitize_text_field(wp_unslash($_POST['whatso_username'])) : '';
        $whatso_password = isset($_POST['whatso_password']) ? sanitize_text_field(wp_unslash($_POST['whatso_password'])) : '';
        $admin_message = $admin_message_template;
        $from_number = $whatso_number;


        $flag = 1;

        if (strlen($whatso_username) > 32 || strlen($whatso_username) < 32) {
            $flag = 0;
            $error_username = '';
            $error_username .= '<div class="notice notice-error is-dismissible">';
            $error_username .= '<p>' . esc_html('Please copy API username properly from website.') . '</p>';
            $error_username .= '</div>';
            echo wp_kses_post($error_username);
        }
        if (strlen($whatso_password) > 32 || strlen($whatso_password) < 32) {
            $flag = 0;
            $error_password = '';
            $error_password .= '<div class="notice notice-error is-dismissible">';
            $error_password .= '<p>' . esc_html('Please copy API password properly from website.') . '</p>';
            $error_password .= '</div>';
            echo wp_kses_post($error_password);
        }
        if ($flag === 1) {

            $admin_mobileno = "";
           

            if (empty(get_option('admin_settings'))) {
                $update_notifications_arr1 = array(
                    'whatso_email' => $whatso_email,
                    'whatso_username' => $whatso_username,
                    'whatso_password' => $whatso_password,
                    'admin_mobileno' => $admin_mobileno,
                    'admin_message' => $admin_message,                  
                    'from_number' => $from_number,
                    'enable_notification' => $enable_notification,

                );

                $result = update_option('admin_settings', wp_json_encode($update_notifications_arr1));


            } else if (!empty(get_option('admin_settings'))) {

                $update_notifications_arr1 = array(
                    'whatso_email' => $whatso_email,
                    'whatso_username' => $whatso_username,
                    'whatso_password' => $whatso_password,
                    'admin_mobileno' => $admin_mobileno,
                    'admin_message' => $admin_message,
                    'from_number' => $from_number,
                    'enable_notification' => $enable_notification,
                );


                $result = update_option('admin_settings', wp_json_encode($update_notifications_arr1));
                wp_redirect('admin.php?page=message-notification-wpcf7');
            }
        }
    }
}
//get data
$data = get_option('admin_settings');
$data = json_decode($data);
$whatso_email = $data->whatso_email;
$whatso_username = $data->whatso_username;
$whatso_password = $data->whatso_password;
$admin_mobileno = $data->admin_mobileno;
$admin_message = $data->admin_message;
$from_number = $data->from_number;
$enable_notification=$data->enable_notification;

$img_url = plugin_dir_url(__DIR__);
$logo = $img_url . 'images/whatso-logo.png';

?>
<div class="container">
    <div class="box w-50 m-auto">
        <div class="text-center m-2">
            <img src="<?php echo esc_url($logo);?>" class="imgclass">
        </div>
        <form method="post" name="form" id="form" action="">

            <h4 class="text-center"><?php esc_attr_e( 'Let us setup something amazing.' , 'whatso'); ?>
            </h4>

            <div class="row mb-3">
                <div class="col-12">
                    <label class="lbl1"><?php esc_attr_e( 'Email address ' , 'whatso'); ?> <sup class="required_star"> *</sup> </label>

                </div>

                <div class="col-12">
                    <input type="text" name="whatso_email" id="whatso_email_setup" placeholder='Enter E-mail ID' autocomplete="off" maxlength="64" class="text_input" value="<?php echo esc_html($whatso_email); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-theme" name="getbutton" form="form" id="getbutton"><?php esc_attr_e( ' GET ' ); ?></button></div>

            </div>
        </form>
        <form method="post" name="form1" id="form1" action="">
            <div class="row mb-3">
                <div class="col-12">
                    <label class="lbl1"><?php esc_attr_e( ' Username ', 'whatso' ); ?> <sup class="required_star"> *</sup></label>

                </div>
                <div class="col-12">
                    <input type="text" name="whatso_username" id="whatso_username" value="<?php echo esc_html($whatso_username); ?>" placeholder="Enter username" autocomplete="off" maxlength="32" class="text_input" required>

                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <label class="lbl1"><?php esc_attr_e( ' Password ' , 'whatso'); ?><sup class="required_star"> *</sup></label>

                </div>
                <div class="col-12">
                    <input type="text" name="whatso_password" id="whatso_password" value="<?php echo esc_html($whatso_password); ?>" placeholder='Enter Password' autocomplete="off" maxlength="32" class="text_input" required>

                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <label class="lbl1"><?php esc_attr_e( ' Message will go from below number ' , 'whatso'); ?></label>
                </div>
                <div class="col-12">
                    <input type="text" name="from_number" id="from_number" class="form-control" value="<?php echo esc_html($whatso_number); ?>" readonly="readonly" />
                </div>

            </div>

            <div class="row mb-3">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-theme" name="submitbutton" id="submitbutton" value="add_new_user" form="form1"><?php esc_attr_e( ' Continue ' ); ?></button>
                    <?php
                    wp_nonce_field('save_details', 'save_admin_details');
                    ?>
                </div>

            </div>
        </form>
        

    </div>
</div>
<script>
    jQuery(document).ready(function($){
    $("#submitbutton").click(function(){
        if($whatso_email===""){
            $("#getbutton").click();
            return false;
        }
        else{
            return true;
        }
    });
    });
</script>