<?php
require_once("java/Java.inc");

$System = java("java.lang.System");
#echo $System->getProperties();

$time=intval(time());
echo $time;
echo '</br>';

$username = 'hello123';
echo $username;
echo '</br>';

$walletName = md5($username+$time);
echo $walletName;
echo '</br>';

$walletPath = 'C:\\Users\\Administrator\\Desktop\\custom\\'.$username.'\\'.$walletName.'.wallet';
echo $walletPath;
echo '</br>';

$myj = new Java("com.xin.wallet.BitCoin");
echo "Test Results are <b>" . $myj->updateWalletInfo('13') . "</b>";

?>