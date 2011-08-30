<?php
/**
 *  The Exerciser class outputs to View objects, which are defined by the 
 *  subclass. 
 *
 * @TODO Add doc comments
 */
class Exerciser
{
    private $routines = array();
    private $exercisesAssigned = array();
    private $views = array();

    public function __construct() {
        $this->routines = array();
        // @TODO Get this file dynamically
        $exercises = file('exercises/default.txt');
        foreach ($exercises as $line) {
            $exerciseArray = preg_split('/ +/', $line);
            $routine = array($exerciseArray[0] => array($exerciseArray[1], $exerciseArray[2]));
            $this->routines[] = $routine;
        }
    }

    private function sendToViews(array $exercises) {
        foreach ($this->views as $view) {
            $view->show($exercises);
        }
    }

    private function getRandomExercise() {
        $routineName = $this->routines[mt_rand(0, count($this->routines) - 1)];
        return $routineName;
    }

    private function getRandomRepetitions($exerciseData) {
        $repetitionData = array_pop($exerciseData);
        $repetitions =  mt_rand($repetitionData[0], $repetitionData[1]);
        return $repetitions;
    }

    public function showExercises($number) {
        $exercises = array();
        for ($i=0; $i < $number; $i++) {
              $exerciseData              = $this->getRandomExercise();
              $reps                      = $this->getRandomRepetitions($exerciseData);
              $exercise                  = key($exerciseData);
              $this->exercisesAssigned[] = $exercise;

              $exercises[] = array($exercise => $reps);
        }
        $this->sendToViews($exercises);
    }

    public function addView(View $view) {
        $this->views[] = $view;
    }
}

interface View {
    public function show(array $exercises);
}

class FileLoggingView implements View {
    private $startTime = 0;

    public function __construct() {
        $this->startTime = time();
    }

    public function show(array $exercises) {
        foreach ($exercises as $exercise) {
            $name = key($exercise);
            $repetitions = array_pop($exercise);
            file_put_contents('exerciseLogs/' . $this->startTime, 
                $name . ' ' . $repetitions . "\n", FILE_APPEND);
        }
    }
}
