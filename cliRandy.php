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

require_once 'randy.php';

class CommandLineExerciser extends Exerciser {
    public function startInteractiveMode() {
        while (true) {
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
}

class CommandLineView implements View {
    public function show(array $exercises) {
        foreach ($exercises as $exercise) {
            $name = key($exercise);
            $repetitions = array_pop($exercise);
            echo $name . ' ' . $repetitions . "\n";
        }
    }
}

$exer = new CommandLineExerciser();
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
