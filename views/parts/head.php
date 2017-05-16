<!DOCTYPE html>
<html lang="en">
  <head>
    <style media="screen">
      html {
        background-color: #000;
      }
      .logo {
        display: none !important;
      }
    </style>
    <?php $action = (isset($action)) ? $action : filter_input(INPUT_GET, 'action'); ?>
    <title><?php echo ucfirst($action); ?></title>
    <meta name="description" content="Photo Gallery">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ($action === 'album') { ?>
      <link rel="manifest" href="<?php echo $project_dir."/manifest_json/?album=$album";?>">
    <?php } else { ?>
      <link rel="manifest" href="<?php echo $project_dir."/manifest_json/default.json";?>">
    <?php } ?>
  </head>
  <body>
  <!-- nav -->
    <?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/nav.php'; ?>
    <!-- nav -->
  <div class="singularity">
      <?php if (isset($error)) { ?>
        <p class="error">
          <?php echo $error; ?>
        </p>
      <?php } ?>
    </div>
    <div class="singularity">
    <?php if (isset($message)) { ?>
      <p class="message">
        <?php echo $message; ?>
      </p>
    <?php } ?>
  </div>
