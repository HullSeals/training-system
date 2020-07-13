<?php
$cloudflareIPRanges = array(
    '204.93.240.0/24',
    '204.93.177.0/24',
    '199.27.128.0/21',
    '173.245.48.0/20',
    '103.21.244.0/22',
    '103.22.200.0/22',
    '103.31.4.0/22',
    '141.101.64.0/18',
    '108.162.192.0/18',
    '190.93.240.0/20',
    '188.114.96.0/20',
    '197.234.240.0/22',
    '198.41.128.0/17',
    '162.158.0.0/15'
);

//NA by default.
$ip = 'NA';

//Check to see if the CF-Connecting-IP header exists.
if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
{

    //Assume that the request is invalid unless proven otherwise.
    $validCFRequest = false;

    //Make sure that the request came via Cloudflare.
    foreach ($cloudflareIPRanges as $range)
    {
        //Use the ip_in_range function from Joomla.
        if (ip_in_range($_SERVER['REMOTE_ADDR'], $range))
        {
            //IP is valid. Belongs to Cloudflare.
            $validCFRequest = true;
            break;
        }
    }

    //If it's a valid Cloudflare request
    if ($validCFRequest)
    {
        //Use the CF-Connecting-IP header.
        $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    else
    {
        //If it isn't valid, then use REMOTE_ADDR.
        $ip = $_SERVER['REMOTE_ADDR'];
    }

}
else
{
    //Otherwise, use REMOTE_ADDR.
    $ip = $_SERVER['REMOTE_ADDR'];
}

//Define it as a constant so that we can
//reference it throughout the app.
define('IP_ADDRESS', $ip);

//$lgd_ip='notLogged';
$lgd_ip = $ip;
?>
