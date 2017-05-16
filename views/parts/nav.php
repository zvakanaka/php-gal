<nav>
    <?php $action = (isset($action)) ? $action : filter_input(INPUT_GET, 'action'); ?>
      <!-- <img class="logo" src="<?php //echo $project_dir.'/img/gal.jpg'?>"> -->
      <?php
      $menu = array("Albums" => "home");
      if (check_admin()) {
        $menu["Admin"] = "admin";
      }
      ?>
        <?php
        foreach(array_keys($menu) as $key){
          echo '<a class="nav-link nav-a';
          if ( ''.$action == strip_extra_params($menu[$key]))
           echo " on";
           echo '" href="'.$project_dir."/?action=".$menu[$key].'" id="'.$menu[$key].'_tab">'.$key.'</a>';
        }
        ?>
</nav>
