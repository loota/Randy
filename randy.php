<?php
class Exerciser
{
    private $routines = array(
        'sukelluspunnerrus',
        'kyykky',
        'istumaannousu',
        'selkäliike'
    );
    private $repetitionsMin = 1;
    private $repetitionsMax = 14;
    private $exercisesAssigned = array();
    private $views = array();

    private function sendToViews($exerciseName, $repetitions) {
        foreach ($this->views as $view) {
            $view->show($exerciseName, $repetitions);
        }
    }

    private function getRandomExerciseName() {
        //mt_srand(substr(microtime(), 2, 8));
      $routineName = $this->routines[mt_rand(0, count($this->routines) - 1)];
      return $routineName;
    }
    private function getRandomRepetitions() {
      $repetitions =  mt_rand($this->repetitionsMin, $this->repetitionsMax);
      return $repetitions;
    }

    public function startExercise() {
        while (true) {
          // @TODO 40 char limit to what should be written as a command.
          $gotKey = fgets(STDIN, 40);  
          if ($gotKey === "q\n") {
              break;
          }
          if (is_integer((int) substr($gotKey, 0, mb_strlen($gotKey) - 1))) {
              for ($i=0; $i<$gotKey-1; $i++) {
                  $exercise = $this->getRandomExerciseName();
                  $reps     = $this->getRandomRepetitions();

                  // @TODO This happens only because of a lack of better way to 
                  // communicate to rupter program an exercise.
                  file_put_contents('/tmp/randy.tmp', $exercise); 

                  $this->exercisesAssigned[] = $exercise;
                  $this->sendToViews($exercise, $reps);
              }
          }
          $exercise = $this->getRandomExerciseName();
          $reps = $this->getRandomRepetitions();
          // @TODO This happens only because of a lack of better way to 
          // communicate to rupter program an exercise.
          file_put_contents('/tmp/randy.tmp', $exercise); 
          $this->exercisesAssigned[] = $exercise;
          $this->sendToViews($exercise, $reps);
        }
    }

    public function addView($view) {
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
$exer->startExercise();
