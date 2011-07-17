<?php
class Exerciser
{
    // @TODO Move these to configuration files.
    // Configurationable
    private $routines = array(
        array('sukelluspunnerrus' => array(1, 10)),
        array('kyykky' => array(1, 10)),
        array('istumaannousu' => array(2, 12)),
        array('selkäliike' => array(3, 15))
    );
    // End configurationable

    private $exercisesAssigned = array();
    private $views = array();

    private function sendToViews($exercises) {
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
    public function show($exercises);
}

class FileLoggingView implements View {
    private $startTime = 0;
    public function __construct() {
        $this->startTime = time();
    }
    public function show($exercises) {
        foreach ($exercises as $exercise) {
            $name = key($exercise);
            $repetitions = array_pop($exercise);
            file_put_contents('exerciseLogs/' . $this->startTime, 
                $name . ' ' . $repetitions . "\n", FILE_APPEND);
        }
    }
}
