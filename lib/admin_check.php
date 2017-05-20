<?php
function same_subnet() {
  return (substr($_SERVER['REMOTE_ADDR'], 0, 4) === '192.' ||
      substr($_SERVER['REMOTE_ADDR'], 0, 3) === '10.' ||
      substr($_SERVER['REMOTE_ADDR'], 0, 4) === '172.' ||
      substr($_SERVER['REMOTE_ADDR'], 0, 4) === '127.' ||
      $_SERVER['REMOTE_ADDR'] == '::1');
}
?>
