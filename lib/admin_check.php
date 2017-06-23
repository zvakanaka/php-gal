<?php
function same_subnet() {
  return (substr($_SERVER['REMOTE_ADDR'], 0, 8) === '192.168.' ||
      substr($_SERVER['REMOTE_ADDR'], 0, 3) === '10.' ||
      substr($_SERVER['REMOTE_ADDR'], 0, 7) === '172.16.' ||
      substr($_SERVER['REMOTE_ADDR'], 0, 9) === '127.0.0.1' ||
      $_SERVER['REMOTE_ADDR'] == '::1');
}
?>
