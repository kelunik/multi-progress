<?php

namespace Kelunik\MultiProgress;

class MultiProgress {
	private $taskMaxLen;
	private $tasks;
	private $options = [
		"unit"       => "%",
		"processing" => "processing",
		"processed"  => "processed",
	];

	public function __construct(array $tasks, array $options = []) {
		$this->tasks = $tasks;
		$this->options = array_merge($this->options, $options);

		foreach ($this->tasks as $task => $info) {
			$this->taskMaxLen = max($this->taskMaxLen, strlen($task));
		}
	}

	public function start() {
		$this->printTasks();
	}

	public function update(array $tasks) {
		$this->tasks = $tasks;
		print chr(27) . "[0G"; // cursor to first column
		print chr(27) . "[" . count($tasks) . "A"; // remove count($tasks) lines
		$this->printTasks();
	}

	private function printTasks() {
		foreach ($this->tasks as $task => list($curr, $max)) {
			print "{$task}";
			print str_repeat(" ", $this->taskMaxLen - strlen($task) + 2);

			if ($curr === $max) {
				print $this->options["processed"];
				$space = 80 - $this->taskMaxLen - strlen($this->options["processed"]) - 4;

				if ($space >= 0) {
					print str_repeat(" ", $space);
				}
			} else {
				print $this->options["processing"];

				if ($this->options["unit"] === "%") {
					$curr = (int) ($curr * 100 / $max);
					$max = 100;
				}

				$progress = "{$curr} / {$max} {$this->options['unit']}";
				$space = 80 - $this->taskMaxLen - strlen($this->options["processing"]) - strlen($progress) - 6;

				if ($space >= 0) {
					print str_repeat(" ", $space);
					print $progress;
				}
			}

			print "\n";
		}
	}
}
