<?php
// @TODO Add Usage to cli
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

    public function usage()
    {
        $usage = "Usage: php cliRandy.php [--no-log] [NUMBER OF EXERCISES]
        * show <number> of exercises
         php cliRandy.php <number>
        
        * start interactive mode
         php cliRandy.php
        
        * In the interactive mode the following commands apply: 
            q quits
            enter key shows one exercise. 
            Typing a number and then enter shows that number of exercises";
        return $usage;
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
// @TODO Add proper handling of options
if ($argv[1] !== '--no-log' && $argv[2] !== '--no-log') {
    $exer->addView(new FileLoggingView());
}

$passedArgs = $argv;
array_shift($passedArgs);
$numberOfExercises = (int) array_shift($passedArgs);
if ($numberOfExercises > 0) {
    $exer->showExercises($numberOfExercises);
} else {
    $exer->startInteractiveMode();
}
