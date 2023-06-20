<?php
/*
Plugin Name: Sri lanka Phone Number Checker
Description: A plugin to check phone numbers and display the result.
Version: 1.0
Author: Madusanka Ranathunga
*/

// Register the shortcode
add_shortcode('phone_number_checker', 'phone_number_checker_shortcode');

// Shortcode callback function
function phone_number_checker_shortcode() {
    ob_start();
    ?>
    <input type="text" id="phone-number" placeholder="Enter phone number">
    <button id="check-button">Check</button>
    <p id="result"></p>
    <script>
        document.getElementById('check-button').addEventListener('click', function() {
            var phoneNumber = document.getElementById('phone-number').value;
            // Ajax request to the server
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('result').innerHTML = xhr.responseText;
                }
            };
            xhr.open('POST', '<?php echo admin_url('admin-ajax.php'); ?>');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('action=phone_number_checker&phone_number=' + phoneNumber);
        });
    </script>
    <?php
    return ob_get_clean();
}

// Ajax callback function
add_action('wp_ajax_phone_number_checker', 'phone_number_checker_ajax');
add_action('wp_ajax_nopriv_phone_number_checker', 'phone_number_checker_ajax');
function phone_number_checker_ajax() {
    $phoneNumber = $_POST['phone_number'];
    $response = '';
    
    // Check if the number has 10 digits and starts with 0
    if (strlen($phoneNumber) !== 10 || $phoneNumber[0] !== '0') {
        $response = 'Invalid phone number!';
    } else {
        $secondDigit = $phoneNumber[1];
        $thirdDigit = $phoneNumber[2];

        $device = '';
        $operator = '';

        if ($secondDigit === '7') {
            $device = 'Mobile';

            if ($thirdDigit === '0') {
                $operator = 'sltmobitel';
            } else if ($thirdDigit === '1') {
                $operator = 'sltmobitel';
            } else if ($thirdDigit === '2') {
                $operator = 'hutch';
            } else if ($thirdDigit === '4') {
                $operator = 'Dialog';
            } else if ($thirdDigit === '5') {
                $operator = 'Airtel';
            } else if ($thirdDigit === '6') {
                $operator = 'Dialog';
            } else if ($thirdDigit === '7') {
                $operator = 'Dialog';
            } else if ($thirdDigit === '8') {
                $operator = 'Hutch';
            }
        } else if (in_array($secondDigit, array('3', '8', '5', '6', '9', '4', '2', '1'))) {
            $device = 'Fixed';

            if ($thirdDigit === '0') {
                $operator = 'Lanka Bell';
            } else if ($thirdDigit === '2') {
                $operator = 'sltmobitel';
            } else if ($thirdDigit === '3') {
                $operator = 'sltmobitel';
            } else if ($thirdDigit === '4') {
                $operator = 'Dialog';
            } else if ($thirdDigit === '5') {
                $operator = 'Lanka Bell';
            } else if ($thirdDigit === '7') {
                $operator = 'Dialog';
            } else if ($thirdDigit === '9') {
                $operator = 'Tritel';
            }
        } else {
            $response = 'Invalid phone number!';
        }

        if (empty($response)) {
            $response = 'Device: ' . $device . '<br>Operator: ' . $operator . '<br>Country: Sri Lanka';
        }
    }

    echo $response;
    wp_die();
}
