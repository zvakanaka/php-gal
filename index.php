<?php
require('lib/load_config.php');
require('model/photo_fs.php');
require('lib/exec.php');
require('lib/string_tools.php');
require('lib/get_supported_format.php');
require('lib/admin_check.php');
$SUPPORTED_FORMAT = get_supported_format();
require('lib/stats.php');

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
  $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
  if ($action == NULL) {
    $action = 'home';
  }
}

function is_admin() {
  if (!same_subnet()) {
    $albums = get_albums($photo_dir, array());
    $error = "You do not have permission to do that.";
    include('views/home.php');
    die();
  }
}

function check_admin() {
  return same_subnet();
}

require('api.php');

if ($action == 'home') {
  $qs_photo = filter_input(INPUT_GET, 'photo', FILTER_SANITIZE_STRING);
  $albums = get_albums($photo_dir, array());
  include('views/home.php');
  die();
} else if ($action == 'album') {
  $album = filter_input(INPUT_GET, 'album', FILTER_SANITIZE_STRING);
  if ($album == NULL) {
    $error = "No album. Try again.";
    //TODO: redirect to home
  }
  $qs_photo = filter_input(INPUT_GET, 'photo', FILTER_SANITIZE_STRING);
  $images = get_images($photo_dir, $album, array());
  include('views/album.php');
  die();
} else if ($action == 'authenticate') {
  if (same_subnet()) {
    $_SESSION["is_admin"] = true;
    header("Location: .?action=home");
  } else {
    $_SESSION["is_admin"] = false;
  }
} else if ($action == 'dslr') {
  is_admin();
  $albums = get_albums($photo_dir, array());
  include('views/dslr.php');
} else if ($action == 'process_all_albums') {
  is_admin();
  $message = "You should probably go outside and skateboard or something.";
  $cmd = 'bash scripts/process_all_albums.sh '.escapeshellarg($photo_dir);
  shell_async($cmd);
  include('views/admin.php');
  die();
} else if ($action == 'download_from_dslr') {
  is_admin();
  $new_album = filter_input(INPUT_POST, 'new_album', FILTER_SANITIZE_STRING);
  if ($new_album == NULL || $new_album == FALSE) {
    $error = "No album name specified. Check all fields and try again.";
    include('views/admin.php');
    die();
  } else {
    $cmd = 'bash scripts/download_from_dslr.sh '.escapeshellarg($new_album).' '.escapeshellarg($photo_dir);
    shell_async($cmd);
  }
  $albums = get_albums($photo_dir, array());
  $message = "Breathe in. Breathe out. Repeat until photos are downloaded to ".$new_album.".";
  include('views/admin.php');
  die();
} else if ($action == 'download_and_process') {
  is_admin();
  $new_album = filter_input(INPUT_POST, 'new_album', FILTER_SANITIZE_STRING);
  if ($new_album == NULL || $new_album == FALSE) {
    $error = "No album name specified. Check all fields and try again.";
    include('views/admin.php');
    die();
  } else {
    $num_images_on_camera = getStat('images');
    $cmd = 'bash scripts/download_and_process.sh '.escapeshellarg($new_album).' '.escapeshellarg($photo_dir);
    shell_async($cmd);
  }
  $albums = get_albums($photo_dir, array());
  // $message = "Downloading $num_images_on_camera images to ".$new_album.".";
  $message = "$num_images_on_camera";
  include('views/admin.php');
  die();
} else if ($action == 'admin') {
  is_admin();
  $albums = get_albums($photo_dir, array());
  include('views/admin.php');
} else if ($action == 'optimize') {
  is_admin();
  $optimization_type = filter_input(INPUT_POST, 'optimization_type', FILTER_SANITIZE_STRING);
  $album_name = filter_input(INPUT_POST, 'album_name', FILTER_SANITIZE_STRING);

  $albums = get_albums($photo_dir, array());

  if ($optimization_type == NULL || $optimization_type == FALSE ||
      $album_name == NULL || $album_name == FALSE) {
    $error = "No album name specified. Check all fields and try again.";
    include('views/admin.php');
    die();
  } else {
    if ($optimization_type == 'thumbs') {
      $cmd = 'bash scripts/create_thumbs.sh '.escapeshellarg($album_name).' '.escapeshellarg($photo_dir).' '.escapeshellarg($_SERVER['REMOTE_ADDR']);
      shell_async($cmd);
    } else if ($optimization_type == 'webs') {
      $cmd = 'bash scripts/create_webs.sh '.escapeshellarg($album_name).' '.escapeshellarg($photo_dir).' '.escapeshellarg($_SERVER['REMOTE_ADDR']);
      shell_async($cmd);
    } else if ($optimization_type == 'delete_originals') {
      $cmd = 'bash scripts/delete_originals.sh '.escapeshellarg($album_name).' '.escapeshellarg($photo_dir).' '.escapeshellarg($_SERVER['REMOTE_ADDR']);
      shell_async($cmd);
    } else {
      $error = "No optimization type specified. Check all fields and try again.";
      include('views/admin.php');
      die();
    }
    $message = "Breathe in. Breathe out. Repeat until ".$optimization_type." for ".$album_name." are created.";
    include('views/admin.php');
    die();
  }
} else if ($action == 'upload_to_server') {
  is_admin();
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $server_name = filter_input(INPUT_POST, 'server_name', FILTER_SANITIZE_STRING);
  $album_name = filter_input(INPUT_POST, 'album_name', FILTER_SANITIZE_STRING);
  $port = filter_input(INPUT_POST, 'port', FILTER_VALIDATE_INT);

  $albums = get_albums($photo_dir, array());

  if ($username == NULL || $username == FALSE ||
      $server_name == NULL || $server_name == FALSE ||
      $album_name == NULL || $album_name == FALSE) {
    $error = "Missing upload field(s). Check all fields and try again.";
    include('views/admin.php');
    die();
  } else {
    $cmd = 'bash scripts/upload_to_server.sh '.escapeshellarg($album_name).' '.escapeshellarg($server_name).' '.escapeshellarg($username).' '.escapeshellarg($port).' '.escapeshellarg($photo_dir);
    shell_async($cmd);
    $message = "Breathe in. Breathe out. Repeat until ".$album_name." is uploaded to ".$server_name.".";
    include('views/admin.php');
    die();
  }
} else if ($action == 'set_album_thumb') {
  is_admin();
  $album_name = filter_input(INPUT_GET, 'album_name', FILTER_SANITIZE_STRING);
  $photo_name = filter_input(INPUT_GET, 'photo_name', FILTER_SANITIZE_STRING);

  $albums = get_albums($photo_dir, array());

  if ($album_name == NULL || $album_name == FALSE ||
      $photo_name == NULL || $photo_name == FALSE) {
    $error = "Missing album or photo name. Check all fields and try again.";
    include('views/admin.php');
    die();
  } else {
    $cmd = 'bash scripts/create_album_thumb.sh '.escapeshellarg($album_name).' '.escapeshellarg($photo_name).' '.escapeshellarg($photo_dir).' '.escapeshellarg($_SERVER['REMOTE_ADDR']);
    shell_async($cmd);
    $message = "Album thumb ".$album_name."/".$photo_name." created.";
    include('views/admin.php');
    die();
  }
} else if ($action == 'delete_photo') {
  is_admin();
  $album_name = filter_input(INPUT_GET, 'album_name', FILTER_SANITIZE_STRING);
  $photo_name = filter_input(INPUT_GET, 'photo_name', FILTER_SANITIZE_STRING);
  $next_photo = filter_input(INPUT_GET, 'next_photo', FILTER_SANITIZE_STRING);

  $albums = get_albums($photo_dir, array());

  if ($album_name == NULL || $album_name == FALSE ||
      $photo_name == NULL || $photo_name == FALSE) {
    echo "Error: Could not delete $album_name/$photo_name, missing information.";
    header("Refresh:2; url=.?action=album&album=$album_name", true, 303);
  } else {
    $cmd = 'bash scripts/delete_photo.sh '.escapeshellarg($album_name).' '.escapeshellarg($photo_name).' '.escapeshellarg($photo_dir).' '.escapeshellarg($_SERVER['REMOTE_ADDR']);
    shell_async($cmd);
    echo $album_name."/".$photo_name." deleted.";
    if ($next_photo == NULL || $next_photo == FALSE) {
      $referer = $_SERVER['HTTP_REFERER'];
      header("Refresh:1; url=$referer", true, 303);
    } else {
      header("Refresh:1; url=.?action=album&album=$album_name&photo=$next_photo", true, 303);
    }
  }
} else {
  // include('views/404.php');
  // die();
}
?>
