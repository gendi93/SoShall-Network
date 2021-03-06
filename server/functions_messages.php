<?php
/**
 * Created by PhpStorm.
 * User: Tom
 * Date: 28/02/2017
 * Time: 13:38
 */

require_once("../server/validation_functions.php");
require_once "../server/db_connection.php";
require_once "functions.php";


// Check for new messages
function check_new_mail_friends($userid)
{
    global $conn;


    $sqlCommand = "SELECT COUNT(ReceiverID) AS numbers
                   FROM message
                   WHERE (ReceiverID='{$userid}' AND ReceiverType LIKE 1)
                   AND Status = '0';";

    $query = mysqli_query($conn, $sqlCommand);
    confirm_query($query);
    $result = mysqli_fetch_assoc($query);

    $inboxNewFriend = $result['numbers'];

    if ($inboxNewFriend > 0) {
        return $inboxNewFriend;
    } else if ($inboxNewFriend == null) {
        return null;
    } else {
        return 0;
    }
}

// Check for new messages
function check_new_mail_circles($userid)
{
    global $conn;

    $sqlCommand = "SELECT COUNT(ReceiverID) AS numbers
                   FROM message m, circle_member cm
                   WHERE (m.ReceiverID=cm.CircleID AND ReceiverType LIKE 0 AND cm.MemberUserID= '{$userid}')
                   AND (NOT m.SenderUserID = '{$userid}')
                   AND Status = '0';";

    $query = mysqli_query($conn, $sqlCommand);
    confirm_query($query);
    $result = mysqli_fetch_assoc($query);

    $inboxNewCircle = $result['numbers'];

    if ($inboxNewCircle > 0) {
        return $inboxNewCircle;
    } else if ($inboxNewCircle == null) {
        return null;
    } else {
        return 0;
    }
}


// Check for all inbox messages
function check_all_inbox($userid) {
    global $conn;

    $sql = "SELECT DISTINCT MessageID, Title, Content, Status, TimeSent, SenderUserID, ReceiverID, FirstName, LastName
            FROM message m, circle_member cm, user u
            WHERE (NOT SenderUserID = '{$userid}')
            AND (m.ReceiverType LIKE '0' 
                 AND (m.ReceiverID = cm.CircleID AND cm.MemberUserID = '{$userid}')
                 AND (SenderUserID = u.UserID)) 
            OR (m.ReceiverType LIKE '1' AND (m.ReceiverID = '{$userid}')) AND (SenderUserID = u.UserID)
            ORDER by TimeSent DESC;";

    $query = mysqli_query($conn, $sql);
    confirm_query($query);

    return $query;
}

function check_circle_messages($circleID){
    global $conn;

    //enum for circles i =0

    $sql = "SELECT Title, Content, SenderUserID, TimeSent FROM message 
    WHERE ReceiverType='0' AND ReceiverID = '{$circleID}'";

    $circle_messages_results_db = mysqli_query($conn, $sql);
    confirm_query($circle_messages_results_db);

    return $circle_messages_results_db;
}


// Check for all outbox messages
function check_all_outbox($userid)
{
    global $conn;

    $sql = "(SELECT MessageID, Title, Content, TimeSent, SenderUserID, ReceiverID, ReceiverType, FirstName, LastName
            FROM message m, user u
            WHERE m.SenderUserID='{$userid}' AND (m.ReceiverType LIKE '1' AND m.ReceiverID=u.UserID))
            UNION
            (SELECT MessageID, Title, Content, TimeSent, SenderUserID, ReceiverID, ReceiverType, CircleTitle as FirstName, CircleTitle as LastName
            FROM message m, circle c
            WHERE m.ReceiverType LIKE '0' AND m.ReceiverID=c.CircleId AND m.SenderUserID='{$userid}')
            ORDER by TimeSent DESC;";

    $query = mysqli_query($conn, $sql);
    confirm_query($query);

    return $query;
}


function search_recipient($userid) {
    global $conn;

    $sql = "SELECT DISTINCT UserID, FirstName, LastName
            FROM user u, friendship f
            WHERE ((u.UserID = f.User1ID AND f.User2ID = '{$userid}' AND Status LIKE '1') OR
            (u.UserID = f.User2ID AND f.User1ID='{$userid}' AND Status LIKE '1'));";


    $result = mysqli_query($conn, $sql);
    confirm_query($result);

    return $result;
}


function search_circles($userid) {
    global $conn;

    $sql = "SELECT CircleTitle, c.CircleID
            FROM user u, circle c, circle_member cm
            WHERE (u.UserID = cm.MemberUserID LIKE '{$userid}' AND cm.CircleID = c.CircleID);";

    $result = mysqli_query($conn, $sql);
    confirm_query($result);

    return $result;
}



function retrieve_message_inbox($MessageID) {
    global $conn;

    $sql = "SELECT MessageID, Title, Content, Status, TimeSent, SenderUserID, ReceiverID, FirstName, LastName
            FROM message m, user u
            WHERE SenderUserID = u.UserID AND MessageID = '$MessageID';";

    $query = mysqli_query($conn, $sql);
    confirm_query($query);

    return $query;
}

function retrieve_message_outbox($MessageID, $userid) {
    global $conn;

    $sql = "SELECT MessageID, Title, Content, Status, TimeSent, SenderUserID, ReceiverType, ReceiverID, FirstName, LastName, CircleTitle
            FROM message m, user u, circle c
            WHERE (SenderUserID = '{$userid}' AND MessageID = '$MessageID' AND ReceiverID = u.UserID)
            OR (SenderUserID = '{$userid}' AND MessageID = '$MessageID' AND ReceiverID = c.CircleID);";

    $query = mysqli_query($conn, $sql);
    confirm_query($query);

    return $query;
}


function update_status($MessageID) {
    global $conn;

    $sql = "UPDATE message
            SET Status = '1'
            WHERE MessageID = '$MessageID'";

    $query = mysqli_query($conn, $sql);
    confirm_query($query);
}

function send_reply($userid, $message_title, $message_content, $receiver_type, $receiver_id) {
    global $conn;
    $date_time = date('Y-m-d H:i:s');


    $sql = "INSERT INTO message
            (SenderUserID, ReceiverType, ReceiverID, Title, Content, TimeSent)
            VALUES ('{$userid}', '{$receiver_type}', '{$receiver_id}', '{$message_title}', '{$message_content}', '{$date_time}')";

    $result = mysqli_query($conn, $sql);
}
