<?php require_once("../server/validation_functions.php"); ?>
<?php
if (isset($_POST["edit_profile"])) {
    $id_toupdate = $_SESSION['UserID'];
    $new_first_name = isset($_POST['first_name']) ? trim($_POST["first_name"]) : "";
    $new_last_name = isset($_POST['last_name']) ? trim($_POST["last_name"]) : "";
    $new_dob = isset($_POST['date_of_birth']) ? trim($_POST["date_of_birth"]) : "";
    $new_location = isset($_POST["location"]) ? trim($_POST["location"]) : null;
    $new_email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $new_phone = isset($_POST["phone_number"]) ? trim($_POST["phone_number"]) : null;

    // escaping and stripping tags from firstName, lastName, email and location
    $new_email = mysqli_real_escape_string($conn, $new_email);
    $new_email = strip_tags($new_email);
    $new_email = htmlentities($new_email);
    $new_location = mysqli_real_escape_string($conn, $new_location);
    $new_location = strip_tags($new_location);
    $new_location = htmlentities($new_location);
    $new_first_name = mysqli_real_escape_string($conn, $new_first_name);
    $new_first_name = strip_tags($new_first_name);
    $new_first_name = htmlentities($new_first_name);
    $new_last_name = mysqli_real_escape_string($conn, $new_last_name);
    $new_last_name = strip_tags($new_last_name);
    $new_last_name = htmlentities($new_last_name);

    // Regex check for the phone number
    $pattern = "/^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/";
    $match = preg_match($pattern, $new_phone);

    $fields_required = array("first_name", "last_name" ,"email", "date_of_birth");
    validate_presences($fields_required);

    if (empty($errors)) {
        // All necessary info is entered.
        if (validateDate($new_dob)) {
            if ($match != false) {
                $sql = "UPDATE user
                    SET FirstName = '$new_first_name', LastName = '$new_last_name', Email = '$new_email', DateOfBirth = '$new_dob', CurrentLocation = '$new_location', PhoneNumber = '$new_phone'
                    WHERE UserID = '$id_toupdate';";

                $result = mysqli_query($conn, $sql);
                confirm_query($sql);

                $found_user = find_user_by_id($id_toupdate);
                if ($found_user) {
                    $_SESSION["UserID"] = $found_user["UserID"];
                    $_SESSION["FirstName"] = $found_user["FirstName"];
                    $_SESSION["LastName"] = $found_user["LastName"];
                    $_SESSION["Email"] = $found_user["Email"];
                } else {
                    $message = "Updating Error. Please refresh the page.";
                }
            } else {
                $_SESSION['message'] = "Please enter a valid Phone Number like +44 7222 555 555.";
            }
        } else {
            $_SESSION['message'] = "Please enter a valid Date of Birth.";
        }

    } else {
        $_SESSION['message'] = "Please do not leave Email or Date of Birth blank!";
    }

} else {
    // Do nothing.
}
