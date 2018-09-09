<?php
require_once "randy.php";

class OutputCapturingView implements View
{
    public $messages = array();
    public function show(array $exercises)
    {
        $this->messages[] = $exercises;
    }
}

// Test inputting exercises in a file
file_put_contents('/tmp/test-exercise-file.txt', 'test: 4 9');
$exerciser = new Exerciser('/tmp/test-exercise-file.txt');
$outputCapturingView = new OutputCapturingView();
$exerciser->addView($outputCapturingView);
$exerciser->showExercises(3);
$exerciseSet = array_pop($outputCapturingView->messages);
foreach ($exerciseSet as $message) {
    echo key($message) === 'test';
}
unlink('/tmp/test-exercise-file.txt');

// Test number of exercises
$exerciser = new Exerciser();
$outputCapturingView = new OutputCapturingView();
$exerciser->addView($outputCapturingView);
$exerciser->showExercises(7);

// Test exercise count
$exercises = array_pop($outputCapturingView->messages);
echo count($exercises) === 7;

// Test exercise array data types
$firstExercise = array_pop($exercises);
echo is_string(key($firstExercise));
echo is_int(array_pop($firstExercise));
