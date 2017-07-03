<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/head.php'; ?>

<main>
<h1>Albums</h1>
<div class="gal">

<?php foreach ($albums as $album) { ?>
  <div class="album-thumb">
    <a class="thumb-link" href="?action=album&amp;album=<?php echo $album;?>">
      <img class="album-img"
        data-fullsize="<?php echo "$photo_dir/$album/.album/thumb_big.".$SUPPORTED_FORMAT; ?>"
        src="<?php echo "$photo_dir/$album/.album/thumb.".$SUPPORTED_FORMAT; ?>"
        alt="Album thumb for <?php echo $album;?>"/>
    </a>
    <div class="album-desc">
      <?php echo ucfirst($album); ?>
    </div>
  </div>
<?php } ?>
</div>
<script src="<?php echo $project_dir."/js/little-big-img.js";?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $project_dir;?>/styles/little-big-img.css"/>
</main>
<?php if (isset($_SESSION['is_admin'])) { ?>
  <?php
  if (filter_input(INPUT_GET, "hidden") == NULL) { ?>
    <div class="singularity">
      <a href="?action=home&hidden=true">Show Hidden Albums</a>
    </div><br>
    <?php } else { ?>
      <div class="singularity">
        <a href="?action=home">Hide Hidden Albums</a>
      </div><br>
      <?php }} ?>
<?php include $_SERVER['DOCUMENT_ROOT'].$project_dir.'/views/parts/toes.php'; ?>
