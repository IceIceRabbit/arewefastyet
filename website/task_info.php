<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
 <meta http-equiv="content-type" content="text/html; charset=UTF-8">
 <meta http-equiv="content-language" content="en">
 <title>ARE WE FAST YET?</title>
 <link rel="stylesheet" title="Default Stylesheet" type="text/css" href="style.css">
 <link rel="shortcut icon" href="//www.arewefastyet.com/awfy_favicon.png">
<link href='//fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
 <script type="text/javascript" src="jquery/jquery-1.8.3.min.js"></script>
 <script type="text/javascript" src="jquery/jquery.ba-hashchange.min.js"></script>
 <script type="text/javascript" src="flot/jquery.flot.js"></script>
 <script type="text/javascript" src="flot/jquery.flot.selection.js"></script>
 <script type="text/javascript" src="data.php?file=master.js"></script>
 <script type="text/javascript" src="awfy.js"></script>
 <script type="text/javascript" src="frontpage.js"></script>
 <script type="text/javascript" src="tooltip.js"></script>
</head>
<body>

<header>
  <div class='container'>
    <h1><a href='#'>AreWeFastYet</a></h1>
    <div class='rightSide'>
	  <div><a href="http://h4writer.com"><span>Blog</span></a></div>
	  <div><a href="/overview"><span>Overview</span></a></div>
    </div>
  </div>
</header>

<div class='task_content'>
<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once("internals.php");

require_once("lib/RetriggerController.php");
require_once("lib/DB/TaskQueue.php");
require_once("lib/DB/QueuedTask.php");
require_once("lib/Slack.php");

init_database();

function time_ago($ptime, $reference = null) {
	if (!$reference)
		$reference = time();
    $etime = $reference - $ptime;
     
    if ($etime < 1) {
        return '0 seconds';
    }
     
    $interval = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
                );
     
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '');
        }
    }
}

function print_time($timestamp) {
	if ($timestamp == 0)
		return "";
	return "<span title='".date("G:i d/m/Y", $timestamp)."'>".time_ago($timestamp)." ago</span>";
}

if ($task_id = GET_int("id")) {

    $task = new QueuedTask($task_id);

	echo "<h1>Task ".$task->id."</h1>";
	echo "Available from: ".print_time($task->available_time())."</br>";
	echo "Started time: ".print_time($task->start_time())."</br>";
	echo "Finished time: ".print_time($task->finish_time())."</br>";

	echo "<h2>Task: </h2>";
	echo "<pre>";
	echo htmlspecialchars($task->task());
	echo "</pre>";

	if ($error = $task->error()) {
		echo "<h2>Error: </h2>";
		echo "<pre>";
		echo htmlspecialchars($task->error());
		echo "</pre>";
	}

	echo "<h2>Output: </h2>";
	echo "<pre>";
	echo htmlspecialchars($task->output());
	echo "</pre>";

    die();
}
?>
</div>
</body>
</html>
