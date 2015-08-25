<?php

require __DIR__ . "/../vendor/autoload.php";

$tasks = [
	"amphp/amp"          => [0, 50],
	"amphp/mysql"        => [0, 125],
	"amphp/redis-server" => [0, 235],
	"amphp/socket"       => [0, 12],
];

$progress = new Kelunik\MultiProgress\MultiProgress($tasks);
$progress->start();

while (true) {
	$complete = 0;

	foreach ($tasks as &$task) {
		if ($task[0] === $task[1]) {
			$complete++;
			continue;
		}

		$task[0] = min($task[0] + mt_rand(0,5), $task[1]);
	}

	if ($complete === count($tasks)) {
		break;
	}

	$progress->update($tasks);
	usleep(100000);
}
