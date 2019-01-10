<?php
	function isWin()
	{
		$sys = strtoupper(PHP_OS);
	 
		if(substr($sys,0,3) == "WIN")
		{
			return TRUE;
		}
		return FALSE;
	}
 
$localString = "it_IT";
 
if(isWin())
{
    $localString = "ita_ITA";
}
 
setlocale(LC_TIME, $localString);
?>