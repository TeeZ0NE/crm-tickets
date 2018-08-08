<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 07.08.18
 * Time: 16:48
 */
use \Carbon\Carbon;
function setClass4lastreply(object $ticket, array $deadlineList, int $maxDeadline){
	$lastreply_class='';
	$lastreply = Carbon::now()->diffInMinutes($ticket->lastreply);
	if($ticket->has_deadline){
		if($lastreply<=$deadlineList[0]) $lastreply_class = 'lastreply-min';
		elseif ($lastreply<=$deadlineList[1] && $lastreply>$deadlineList[0]) $lastreply_class='lastreply-med';
		else $lastreply_class='lastreply-max';}
	else {
		if($lastreply>=$maxDeadline) $lastreply_class='lastreply-max';
	}
	return $lastreply_class;
}
