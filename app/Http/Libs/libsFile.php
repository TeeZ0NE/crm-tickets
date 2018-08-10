<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 07.08.18
 * Time: 16:48
 */
use \Carbon\Carbon;

/**
 * Get class 4 tr tag in all tickets
 *
 * It get's lastreply from Ticket and compare it with deadline intervals and return CSS class 4 it
 * @param object $ticket
 * @param array $deadlineList
 * @param int $maxDeadline
 * @return string
 */
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
