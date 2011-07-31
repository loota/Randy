<?php
class Exerciser
{
    // @TODO Move these to configuration files.
    // Configurable
    //private $routines = array(
        //array('sukelluspunnerrus' => array(1, 10)),
        //array('kyykky' => array(1, 10)),
        //array('istumaannousu' => array(2, 12)),
        //array('selkäliike' => array(3, 15))
    //);
    //private $routines = array(
        //array('sormipunnerrus' => array(1, 3)),
        //array('punnerrus' => array(1, 10)),
        //array('leveä punnerrus' => array(1, 10)),
        //array('kapea punnerrus' => array(1, 3)),
        //array('sukelluspunnerrus' => array(1, 5)),
        //array('yhden käden punnerrus' => array(1, 3)),
        //array('nyrkkipunnerrus' => array(1, 5)),
        //array('helpotettu punnerrus' => array(1, 10))
    //);
    
    private $routines = array(
        array('istumaannousu' => array(1, 8)),
        array('rutistus' => array(2, 10)),
        array('jalkojen nosto' => array(1, 5)),
        array('sivuttainen istumaannousu' => array(1, 5)),
        array('lankku' => array(3, 15))
    );
    // End configurable

    private $exercisesAssigned = array();
    private $views = array();

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
