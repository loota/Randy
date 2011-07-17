<?php
// Usage:
//  show <number> of exercises
//   php randy.php <number>
//
//  start interactive mode
//   php randy.php
//
//  In the interactive mode: 
//  q quits
//  enter key shows one exercise. 
//  Typing a number and  then enter shows <number> exercises

class Exerciser
{
    // @TODO Move these to configuration files.
    // Configurationable
    private $routines = array(
        array('sukelluspunnerrus' => array(1,10)),
        array('kyykky' => array(1,10)),
        array('istumaannousu' => array(2,12)),
        array('selkäliike' => array(3,15))
    );
    // End configurationable

    private $exercisesAssigned = array();
    private $views = array();

    private function sendToViews($exerciseName, $repetitions) {
        foreach ($this->views as $view) {
            $view->show($exerciseName, $repetitions);
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

    public function startInteractiveMode() {
        while (true) {
          // @TODO Encapsulate the input somewhere.
          $gotKey = fgets(STDIN);  
          if ($gotKey === "q\n") {
              break;
          }
          if (is_integer((int) substr($gotKey, 0, mb_strlen($gotKey) - 1))) {
              $this->showExercises($gotKey - 1);
          }
          $this->showExercises(1);
        }
    }

    public function showExercises($number) {
        for ($i=0; $i<$number; $i++) {
              $exerciseData = $this->getRandomExercise();
              $reps     = $this->getRandomRepetitions($exerciseData);
              $exercise = key($exerciseData);

              // @TODO This happens only because of a lack of better way to 
              // communicate to rupter program an exercise.
              file_put_contents('/tmp/randy.tmp', $exercise . ' ' . $reps . "\n"); 

              $this->exercisesAssigned[] = $exercise;
              $this->sendToViews($exercise, $reps);
        }
    }

    public function addView(View $view) {
        $this->views[] = $view;
    }
}

interface View {
    public function show($exerciseName, $repetitions);
}

class CommandLineView implements View {
    public function show($exerciseName, $repetitions) {
        echo $exerciseName . ' ' . $repetitions . "\n";
    }
}

class FileLoggingView implements View {
    private $startTime = 0;
    public function __construct() {
        $this->startTime = time();
    }
    public function show($exerciseName, $repetitions) {
        file_put_contents('exerciseLogs/' . $this->startTime, 
            $exerciseName . ' ' . $repetitions . "\n", FILE_APPEND);
    }
}
$exer = new Exerciser();
$exer->addView(new CommandLineView());
$exer->addView(new FileLoggingView());

$passedArgs = $argv;
array_shift($passedArgs);
$numberOfExercises = (int) array_shift($passedArgs);
if (($numberOfExercises > 0)) {
    $exer->showExercises($numberOfExercises);
} else {
    $exer->startInteractiveMode();
}
