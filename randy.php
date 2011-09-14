<?php
/**
 *  The Exerciser class outputs to View objects, which are defined by the 
 *  subclass. 
 *
 *  The main data format for exercises is the following:
 *  Each exercise consists in a key, which is the name of the exercise 
 *  and the value, which is an array containing two cells. The first number 
 *  contains the minimum number of repetitions, and the second cell contains 
 *  the maximum number of repetitions.
 *  Example:
 *     array(1) {
 *       ["push-up"]=>
 *         array(2) {
 *           [0]=>
 *           string(1) "1"
 *           [1]=>
 *           string(3) "10"
 *         }
 *     }
 */
class Exerciser
{
    /**
     * @var array $_routines in the main data format. @see Exerciser
     */
    private $_routines = array();

    /**
     * @var array $_views array of View objects that actually decide where to 
     * output the assigned exercise.
     */
    private $_views = array();

    /**
     * @param string name of file containing exercise configuration.
     * Given file must contain one line per exercise. Each line must contain
     * the exercise name ending with a colon (:), minimum repetitions and maximum repetitions. 
     * The min and max numbers must be separated by spaces.
     *
     * Example:
     *
     *   squat:   1   10
     *   crunch:  4   14
     */
    public function __construct($filename = false)
    {
        if (!$filename) {
            $filename = 'exercises/default.txt';
        }
        $this->_routines = $this->_getExercisesFromFile($filename);
    }

    /**
     * @param string $filename filename containing configuration data. @see 
     * __construct for the format.
     */
    private function _getExercisesFromFile($filename)
    {
        $exercises = array();
        $lines = file($filename);
        foreach ($lines as $line) {
            $exerciseArray = preg_split('/:/', $line);
            $exerciseName = $exerciseArray[0];
            $trimmedMinAndMax = trim($exerciseArray[1]);
            $minAndMax = preg_split('/ +/', $trimmedMinAndMax);
            $minimum = $minAndMax[0];
            $maximum = $minAndMax[1];
            $maximum = str_replace("\n", "", $maximum);
            $routine = array($exerciseName => array($minimum, $maximum));
            $exercises[] = $routine;
        }
        return $exercises;
    }

    /**
     * @var array $exercises the view data format. @see View
     */
    private function _sendToViews(array $exercises)
    {
        foreach ($this->_views as $view) {
            $view->show($exercises);
        }
    }

    /**
     * @return string
     */
    private function _getRandomExercise()
    {
        $routineName = $this->_routines[mt_rand(0, count($this->_routines) - 1)];
        return $routineName;
    }

    /**
     * @param array $exerciseData the main data format. @see Exerciser
     */
    private function _getRandomRepetitions(array $exerciseData)
    {
        $repetitionData = array_pop($exerciseData);
        $repetitions =  mt_rand($repetitionData[0], $repetitionData[1]);
        return $repetitions;
    }

    /*
     * @param int $number
     */
    public function showExercises($number)
    {
        $exercises = array();
        for ($i=0; $i < $number; $i++) {
              $exerciseData = $this->_getRandomExercise();
              $reps = $this->_getRandomRepetitions($exerciseData);
              $exercise = key($exerciseData);

              $exercises[] = array($exercise => $reps);
        }
        $this->_sendToViews($exercises);
    }

    /**
     * @param View $view
     */
    public function addView(View $view)
    {
        $this->_views[] = $view;
    }
}

interface View {
    /**
     * @param array $exercises view data format.
     * Contains one exercise for each cell in the array. 
     * Each exercise consists in a key, which is the name of the exercise 
     * and the value, which is the number of repetitions.
     *
     * Example:
     *   array(1) {
     *    ["push-up"]=>
     *    int(3)
     *  }
     */
    public function show(array $exercises);
}

/**
 * Log the output to a file. The file will be named with a timestamp and will 
 * end with the extension txt. 
 */
class FileLoggingView implements View {
    private $startTime = 0;

    public function __construct()
    {
        $this->startTime = time();
    }

    /**
     * @param array $exercises the view data format. @see View
     */
    public function show(array $exercises)
    {
        foreach ($exercises as $exercise) {
            $name = key($exercise);
            $repetitions = array_pop($exercise);
            file_put_contents('exerciseLogs/' . $this->startTime . '.txt', 
                $name . ' ' . $repetitions . "\n", FILE_APPEND);
        }
    }
}
