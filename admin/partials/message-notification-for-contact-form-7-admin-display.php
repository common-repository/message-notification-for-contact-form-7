<?php
//set data
if (isset($_POST) && !empty($_POST)) {

    $whatso_username = isset($_POST['whatso_username']) ? sanitize_text_field(wp_unslash($_POST['whatso_username'])) : '';
    $whatso_password = isset($_POST['whatso_password']) ? sanitize_text_field(wp_unslash($_POST['whatso_password'])) : '';
    $from_number = isset($_POST['from_number']) ? sanitize_text_field(wp_unslash($_POST['from_number'])) : '';
    $whatso_email = isset($_POST['whatso_email']) ? sanitize_email(wp_unslash($_POST['whatso_email'])) : '';
    $admin_mobileno = isset($_POST['admin_mobileno']) ? sanitize_text_field(wp_unslash($_POST['admin_mobileno'])) : '';
    $admin_mobileno = preg_replace('/[^0-9,]/u', '', $admin_mobileno);
    $admin_message = isset($_POST['admin_message']) ? sanitize_textarea_field(wp_unslash($_POST['admin_message'])) : '';
    
    if (isset($_POST['customer_checkbox'])) {
        $enable_notification = "1";
    } else {
        $enable_notification = "0";
    }

    $update_notifications_arr = array();
    $flag = 1;
    if (strlen($whatso_username) > 32 || strlen($whatso_username) < 32) {
        $flag = 0;
        $error_username = '';
        $error_username .= '<div class="notice notice-error is-dismissible">';
        $error_username .= '<p>' . esc_html('Please copy API username properly from website.','whatso' ) . '</p>';
        $error_username .= '</div>';
        echo wp_kses_post($error_username);
    }
    if (strlen($whatso_password) > 32 || strlen($whatso_password) < 32) {
        $flag = 0;
        $error_password = '';
        $error_password .= '<div class="notice notice-error is-dismissible">';
        $error_password .= '<p>' . esc_html('Please copy API password properly from website.','whatso' ) . '</p>';
        $error_password .= '</div>';
        echo wp_kses_post($error_password);
    }
    if (empty($admin_mobileno)) {
        $flag = 0;
        $error_mobileno = '';
        $error_mobileno .= '<div class="notice notice-error is-dismissible">';
        $error_mobileno .= '<p>' . esc_html('Please Enter Mobile Number.','whatso' ) . '</p>';
        $error_mobileno .= '</div>';
        echo wp_kses_post($error_mobileno);
    } elseif (strlen($admin_mobileno) <= 7) {
        $flag = 0;
        $error_mobileno = '';
        $error_mobileno .= '<div class="notice notice-error is-dismissible">';
        $error_mobileno .= '<p>' . esc_html('Please enter atleast 7 digit number.','whatso' ) . '</p>';
        $error_mobileno .= '</div>';
        echo wp_kses_post($error_mobileno);
    } else {
        $numbers = explode(',', $admin_mobileno);
        $numbers = array_filter($numbers);
        $numbers = array_map('trim', $numbers);
        $error = 0;
        $inValidNumbers = array();
        foreach ($numbers as $number) {
            if (is_numeric($number)) {
                if (strlen($number) < 7) {
                    $error++;
                    array_push($inValidNumbers, $number);
                }
            } else {
                $error++;
                array_push($inValidNumbers, $number);
                $flag = 0;
                $error_message = '';
                $error_message .= '<div class="notice notice-error is-dismissible">';
                $error_message .= '<p>' . esc_html('Please enter valid number') . ' ' . implode(", ", $inValidNumbers) . '</p>';
                $error_message .= '</div>';
                echo wp_kses_post($error_message);
            }
        }
        if ($error != 0) {
            $flag = 0;
            $error_message = '';
            $error_message .= '<div class="notice notice-error is-dismissible">';
            $error_message .= '<p>' . esc_html('Please enter 7 digit number of') . ' ' . implode(", ", $inValidNumbers) . '</p>';
            $error_message .= '</div>';
            echo wp_kses_post($error_message);
        }
        if (count($numbers) > 10) {
            $flag = 0;
            $error_message = '';
            $error_message .= '<div class="notice notice-error is-dismissible">';
            $error_message .= '<p>' . esc_html('You cannot enter more then 10 numbers') . '</p>';
            $error_message .= '</div>';
            echo wp_kses_post($error_message);
        }
    }

    if ($flag === 1) {
        $admin_mobileno = implode(", ", $numbers);

        $update_notifications_arr = array(
            'whatso_email' => $whatso_email,
            'whatso_username'   =>  $whatso_username,
            'whatso_password'   =>  $whatso_password,
            'admin_mobileno'   =>  $admin_mobileno,
            'admin_message'    =>  $admin_message,
            'enable_notification' => $enable_notification,
            'from_number' => $from_number,
        );
        $result = update_option('admin_settings', wp_json_encode($update_notifications_arr));

        if ($result) {
            $success = '';
            $success .= '<div class="notice notice-success is-dismissible">';
            $success .= '<p>' . esc_html('Details update successfully.') . '</p>';
            $success .= '</div>';
            echo wp_kses_post($success);
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
$enable_notification =$data->enable_notification;
$from_number = $data->from_number;

$img_url = plugin_dir_url(__DIR__);
$logo = $img_url . 'images/whatso-logo.png';

?>
<div class="container">
    <div>
    <img src="<?php echo esc_url($logo);?>" class="imgclass">
    </div>

    <ul class="breadcrumb">
        <li><b><?php esc_attr_e( 'Message Notification via Contact Form 7' ); ?></b></li>
    </ul>
    <div class="box">
        <form class="form_div" method="post">
            <div class="row mb-3">
                <div class="col-10">
                    <label class="lbl1"><?php esc_attr_e( 'WhatsApp number with country code ','whatso' ); ?> <span class="required_star">*</span>(You will receive notifications on this number)</label>
                    <input type="text" name="admin_mobileno" id="admin_mobileno" autocomplete="off" maxlength="200" placeholder="Enter Mobile Number with country code. Do not prefix with a 0 or +" class="text_input form-control" value="<?php echo esc_html($admin_mobileno); ?>" />

                </div>
            </div>
            <div class="row mb-3">
                <div class="col-5">
                    <label class="lbl1"><?php esc_attr_e( 'Username','whatso' ); ?> <sup class="required_star"> *</sup></label>
                    <input type="text" name="whatso_username" id="whatso_username" value="<?php echo esc_html($whatso_username); ?>" placeholder="Enter username" autocomplete="off" maxlength="32" class="text_input" required>
                </div>
                <div class="col-5">
                    <label class="lbl1"><?php esc_attr_e( 'Password','whatso'  ); ?> <sup class="required_star"> *</sup></label>
                    <input type="text" name="whatso_password" id="whatso_password" value="<?php echo esc_html($whatso_password); ?>" placeholder='Enter Password' autocomplete="off" maxlength="32" class="text_input" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-5">
                    <label class="lbl1"><?php esc_attr_e( 'Message will go from below number','whatso'  ); ?></label>
                    <input type="text" name="from_number" id="from_number" class="form-control" value="<?php echo esc_html($from_number); ?>" readonly="readonly"/>
                </div>
                <div class="col-5">
                    <label class="lbl1"><?php esc_attr_e( 'Email address ','whatso'  ); ?><sup class="required_star"> *</sup> </label>
                    <input type="text" name="whatso_email" id="whatso_email" placeholder='Enter E-mail ID' autocomplete="off" maxlength="64" class="text_input" value="<?php echo esc_html($whatso_email); ?>" required>
                </div>
            </div>

            <div class="row mb-3 ">
                <div class="col-10 ">
                    <label class="lbl1"><?php esc_html_e('Message', 'whatso'); ?></label><span class="required_star">*</span>

                    <textarea class="form-control message"  name="admin_message" id="admin_message" autocomplete="off" placeholder="Enter message that you want to be sent when the order is placed."  readonly rows="7"><?php  esc_attr_e($admin_message); ?></textarea>

                </div>
            </div>

            <div class="row mb-3">
                <div class="col-10">
                    <?php if ($enable_notification === '1') { ?>
                        <input type="checkbox" id="customer_checkbox" name="customer_checkbox" value="customer_checkbox" checked />
                    <?php } else {  ?>
                        <input type="checkbox" id="customer_checkbox" name="customer_checkbox" value="customer_checkbox" />
                    <?php } ?>
                    <label class="lbl1 mb-1" > <?php esc_attr_e( 'Enable Notification ' ,'whatso' ); ?></label>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-10">
                    <input type="submit" class="btn btn-theme" name="notification_submit" value="Submit"  />
                </div>
            </div>
            
        </form>
    </div>
</div>
<script>
    function isNumber(evt) {

        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            //key = event.clipboardData.getData('text/plain');
            theEvent.returnValue = false;
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]/;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
            document.getElementById('mobile_number_error').style.display = 'block';
            return false
        } else {
            document.getElementById('mobile_number_error').style.display = 'none';
            return true;
        }

    }

</script>