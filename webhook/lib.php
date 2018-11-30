<?php

function __log($info,$location)
{
	$location = 'logs/'.$location;
	if (!file_exists($location)) {
		touch($location);
	}
	$inner = file_get_contents($location);
	file_put_contents($location, 
		$inner.'
'.date('[Y-m-d h:i:s]    ').json_encode($info));
}