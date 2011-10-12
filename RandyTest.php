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
 
class RandyTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $exercises = array(array('test' => array('4', '9')));
        $this->exerciser = new Exerciser();
        $this->exerciser->setExercises($exercises);
        $this->outputCapturingView = new OutputCapturingView();
        $this->exerciser->addView($this->outputCapturingView);
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
