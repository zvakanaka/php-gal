<?php
// The following function is from here: http://php.net/manual/en/function.ip2long.php#94290
/**
* Check if a client IP is in our Server subnet
*
* @param string $client_ip
* @param string $server_ip
* @return boolean
*/
function clientInSameSubnet($client_ip=false,$server_ip=false) {
    if (!$client_ip)
        $client_ip = $_SERVER['REMOTE_ADDR'];
    if (!$server_ip)
        $server_ip = $_SERVER['SERVER_ADDR'];

    // Extract broadcast and netmask from ifconfig
    if (!($p = popen("ifconfig","r"))) return false;
    $out = "";
    while(!feof($p))
        $out .= fread($p,1024);
    fclose($p);

    // This is to avoid wrapping.
    $match  = "/^.*".$server_ip;
    $match .= ".*Bcast:(\d{1,3}\.\d{1,3}i\.\d{1,3}\.\d{1,3}).*";
    $match .= "Mask:(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})$/im";
    if (!preg_match($match,$out,$regs))
        return false;

    $bcast = ip2long($regs[1]);
    $smask = ip2long($regs[2]);
    $ipadr = ip2long($client_ip);
    $nmask = $bcast & $smask;

    return (($ipadr & $smask) == ($nmask & $smask));
}

function same_subnet() {
  return (clientInSameSubnet($_SERVER['REMOTE_ADDR'], $_SERVER['SERVER_ADDR'])
    || $_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR']
    || $_SERVER['REMOTE_ADDR'] == '::1');
}
?>
