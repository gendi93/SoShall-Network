<?php
// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    $target_dir = "../userarea/img/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $_SESSION["message"] .= "File is not an image.";
        $uploadOk = 0;
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        unlink($target_file);
        // $uploadOk = 0;
    }

// Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $_SESSION["message"] .= "Sorry, your file is too large.";
        $uploadOk = 0;
    }

// Allow certain file formats
    $imageFileType = strtolower($imageFileType);
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $_SESSION["message"] .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $_SESSION["message"] .= "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
            $temp_ext = explode(".", $_FILES["fileToUpload"]["name"]);
            $userid = $_SESSION["UserID"];
            $new_filename = $userid . "_profilepicture." . end($temp_ext);

            // Check if user already has a profile picture. Insert if no, else update
            $pic_result = find_profile_pic($userid);
        if (!($profile_picture = mysqli_fetch_assoc($pic_result))) {
            mysqli_free_result($pic_result);
            // Insert into SQL
            $query = "INSERT INTO Photo (PhotoID, UserID) ";
            $query .= "VALUES (";
            $query .= "'{$new_filename}', '{$userid}'";
            $query .= ")";
            $result = mysqli_query($conn, $query);
        } else {
            mysqli_free_result($pic_result);
            // Update table
            $query = "UPDATE Photo ";
            $query .= "SET PhotoID=";
            $query .= "'{$new_filename}' ";
            $query .= "WHERE UserID='{$userid}'";
            $query .= ";";
            $result = mysqli_query($conn, $query);
        }
        if ($result) {
            // Store in directory
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $new_filename);
            $_SESSION["message"] .= "Upload successful.";
        } else {
            // Failure
            $_SESSION["message"] = "Sorry, there was an error uploading your file.";
        }
                    
            // Deleted test if storing work. Reinstate?
            // if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            // }
            // else {
            //     $_SESSION["message"] .= "Sorry, there was an error uploading your file.";
            // }
    }
        redirect_to("profile.php");
}