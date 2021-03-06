<?php require_once("../server/sessions.php"); ?>
<?php require_once("../server/functions.php");?>
<?php require_once("../server/functions_photos.php");?>
<?php require_once("../server/functions_friends.php");?>
<?php require_once("../server/db_connection.php");?>
<?php require_once("../server/validation_friends.php");?>
<?php $page_title="{$_SESSION["FirstName"]} {$_SESSION["LastName"]}'s Friends"?>
<?php confirm_logged_in(); ?>
<?php include("../includes/header.php"); ?>
<?php include("navbar.php"); ?>


<section class="jumbotron jumbotron-friends">

    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                <h1> Your Friends </h1>
                <?php echo message()?>
            </div>
        </div>
    </div>
</section>

<?php 
    // Display pending requests if exist
    $pending = find_pending($_SESSION["UserID"]);
    if (mysqli_num_rows($pending)>0) { 
?>

        <h3>Pending Friend Requests:</h3>
        <div class="container">
            <div class="row ">
    <?php
        while ($p_friend = mysqli_fetch_assoc($pending)) {
            $pic_result = find_profile_pic($p_friend["UserID"]);
            $profile_picture = mysqli_fetch_assoc($pic_result);
            $profile_picture_src = file_exists("img/Profilepictures" . $p_friend["UserID"] . "/" . $profile_picture["FileSource"]) ? "img/Profilepictures" . $p_friend["UserID"] . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
            $uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);
            mysqli_free_result($pic_result);
    ?>

            <a href="user_profile.php?id=<?php echo $p_friend['UserID']?>">
                <div class="col-md-6 polaroid">
            <div class="col-md-7">
                <img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Friend's profile picture'">
            </div>
            <div class="col-md-5">
                <h4><?php echo $p_friend["FirstName"] . " " . $p_friend["LastName"]?></h4><br /><br />
                <form method="post">
                    <button class="btn btn-primary" type="submit" name="add_friend" value="<?php echo $p_friend['FriendshipID']?>">
                        Accept
                    </button>
                    <button class="btn" type="submit" name="decline_friend" value="<?php echo $p_friend['FriendshipID']?>">
                        Decline
                    </button>
                </form>
            </div>
                </div>
            </a>


    <?php
        }//closing the while look
    ?>
            </div>
    </div>
<?php
    }
    mysqli_free_result($pending);
?>


<?php
    echo message();
    $accepted_friends = find_accepted($_SESSION["UserID"]);


    ?>
<div class="container">
    <div class="row text-center">
        <?php
        $friend_count = count_friends_output($accepted_friends);
        echo $friend_count;

        ?>
    </div>
    <div class="row">


<?php

    while ($a_friend = mysqli_fetch_assoc($accepted_friends)) {
            $pic_result = find_profile_pic($a_friend["UserID"]);
            $profile_picture = mysqli_fetch_assoc($pic_result);
            $profile_picture_src = file_exists("img/Profilepictures" . $a_friend["UserID"] . "/" . $profile_picture["FileSource"]) ? "img/Profilepictures" . $a_friend["UserID"] . "/" . $profile_picture["FileSource"] : "img/" . $profile_picture["FileSource"];
            $uncached_src = $profile_picture_src . "?" . filemtime($profile_picture_src);

    ?>
        <a href="user_profile.php?id=<?php echo $a_friend['UserID']?>">
     <div class="col-md-6 polaroid">
            <div class="col-md-7">
                <img src="<?php echo $uncached_src ?>" class="img-responsive" alt="Friend's profile picture'">
            </div>
            <div class="col-md-5">
                <h4><?php echo $a_friend["FirstName"] . " " . $a_friend["LastName"]?></h4><br /><br />
            </div>
            <?php $exist_relation = find_friendship($_SESSION["UserID"], $a_friend["UserID"]);
                  $rows =mysqli_num_rows($exist_relation);
                  if (mysqli_num_rows($exist_relation)>0) {
                      $friendship = mysqli_fetch_assoc($exist_relation);
                  }?>
            <form method="post" style="display: inline">
              <button type="submit" name="decline_friend" value="<?php echo $friendship['FriendshipID'] ?>" class="btn">Unfriend</button>
            </form>
     </div>
</a>
<?php

        }//closing while

?>
    </div>
</div>

<?php
//release the results outside the loop
mysqli_free_result($pic_result);
mysqli_free_result($accepted_friends);
?>


<br>
<!--  RECOMMENDATIONS-->
<h4>People you may know</h4>
<br>

<?php include("recommendations2.php");?>
<?php include("../includes/footer.php"); ?>
