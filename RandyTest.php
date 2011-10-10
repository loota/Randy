<?php
require_once 'PHPUnit/Framework.php';
require_once "Randy.php";

class OutputCapturingView implements View
{
    public $messages = array();
    public function show(array $exercises)
    {
        $this->messages[] = $exercises;
    }
}
 
class randyTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        file_put_contents('/tmp/test-exercise-file.txt', 'test: 4 9');
        $this->exerciser = new Exerciser('/tmp/test-exercise-file.txt');
        $this->outputCapturingView = new OutputCapturingView();
        $this->exerciser->addView($this->outputCapturingView);
        unlink('/tmp/test-exercise-file.txt');
    }

    public function testUsingConfigurationFile()
    {
        $exerciser = $this->exerciser;
        $exerciser->showExercises(3);
        $exerciseSet = array_pop($this->outputCapturingView->messages);
        foreach ($exerciseSet as $message) {
            $this->assertEquals(key($message), 'test');
        }
    }

    public function testNumberOfExercises()
    {
        $exerciser = $this->exerciser;
        $exerciser->showExercises(7);
        $exercises = array_pop($this->outputCapturingView->messages);
        $this->assertEquals(count($exercises), 7);
    }

    public function testDataFormat()
    {
        $exerciser = $this->exerciser;
        $exerciser->showExercises(1);
        $firstExerciseSeriesData = array_pop($this->outputCapturingView->messages);
        $firstExerciseData = array_pop($firstExerciseSeriesData);
        $firstExerciseName = key($firstExerciseData);
        $firstExerciseReps = array_pop($firstExerciseData);
        $this->assertGreaterThan(3, $firstExerciseReps);
        $this->assertLessThan(10, $firstExerciseReps);
        $this->assertEquals('test', $firstExerciseName);
    }
}
