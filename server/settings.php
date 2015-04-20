<?php

$user_id = $_POST['settings_user_id'];

if ($_FILES['settings_image']['name']) {
    // Check if image file is a actual image or fake image
    $allowed_filetypes = array('.jpg','.jpeg','.png','.gif');
    $max_filesize = 10485760;
    $upload_path = '../images/profile/';
    $description = $_POST['imgdesc'];
    $filename_complete = $_FILES['settings_image']['name'];
    $ext = substr($filename_complete, strpos($filename_complete,'.'), strlen($filename_complete)-1);

    if(!in_array($ext,$allowed_filetypes))
      header( 'Location: ../settings.php?success=0' );

    if(filesize($_FILES['settings_image']['tmp_name']) > $max_filesize)
      header( 'Location: ../settings.php?success=0' );

    if(!is_writable($upload_path))
      header( 'Location: ../settings.php?success=0' );

    if(!move_uploaded_file($_FILES['settings_image']['tmp_name'],$upload_path . $user_id)) {
         header( 'Location: ../settings.php?success=0' );
    }
}


$db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
        mysql_select_db('bluecu6_dehari', $db_dehari);

if ($_POST['settings_full_name']) {
    $query = 'UPDATE dehari_user SET user_full_name = "' . $_POST['settings_full_name'] . '" WHERE user_id = ' . $user_id;
    // Perform Query
    $result = mysql_query($query, $db_dehari);

    // Check result
    // This shows the actual query sent to MySQL, and the error. Useful for debugging.

    if (!$result) {
        header( 'Location: ../settings.php?success=0' );
    } 
}

if ($_POST['settings_description']) {
    $query = 'UPDATE dehari_user SET user_description = "' . $_POST['settings_description'] . '" WHERE user_id = ' . $user_id;
    // Perform Query
    $result = mysql_query($query, $db_dehari);

    // Check result
    // This shows the actual query sent to MySQL, and the error. Useful for debugging.

    if (!$result) {
        header( 'Location: ../settings.php?success=0' );
    } 
}

header( 'Location: ../settings.php?success=1' );

?>