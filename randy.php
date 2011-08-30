<?php
/**
 *  The Exerciser class outputs to View objects, which are defined by the 
 *  subclass. 
 *
 *  @TODO Add doc comments
 * 
 *   The main data format for exercises is the following:
 *   Each exercise consists in the key, which is the name of the exercise 
 *   and the value, which is an array containing two cells. The first number 
 *   contains the minimum number of repetitions, and the second cell contains 
 *   the maximum number of repetitions.
 *   Example:
 *      array(1) {
 *        ["push-up"]=>
 *          array(2) {
 *            [0]=>
 *            string(1) "1"
 *            [1]=>
 *            string(3) "10"
 *          }
 *      }
 *
 */
class Exerciser
{
    /**
     * @var array $routines the main data format. See the class documentation
     */
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

    /**
     * @param array $exerciseData the main data format. See the class documentation
     */
    private function getRandomRepetitions(array $exerciseData) {
        $repetitionData = array_pop($exerciseData);
        $repetitions =  mt_rand($repetitionData[0], $repetitionData[1]);
        return $repetitions;
    }
    /*
     * @param int $number
     */
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
    /**
     * @aparam array $exercises Contains an exercise for each cell in the array. 
     *   Each exercise consists in the key, which is the name of the exercise 
     *   and the value, which is the number of repetitions.
     *   Example:
     *     array(1) {
     *      ["push-up"]=>
     *      int(3)
     *    }
     */
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
