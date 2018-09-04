<?php
// overload their default function to allow sending pageview at the very end
// filter would be better, but somehow they decided this way.
if(!function_exists('googleAnalyticsScript')) :
	function googleAnalyticsScript($return = false) {

	}
endif;