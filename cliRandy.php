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

function usage()
{
    $usage = "Usage: php cliRandy.php [--no-log] [NUMBER OF EXERCISES]
    Show <number> of exercises
     php cliRandy.php <number>
    
    * start interactive mode
     php cliRandy.php
    
    * In the interactive mode the following commands apply: 
        q quits
        enter key shows one exercise. 
        Typing a number and then enter shows that number of exercises";
    return $usage;
}

$exer = new CommandLineExerciser();
$exer->addView(new CommandLineView());

// @TODO Add proper handling of options

$optionFound = false;
$logging = true;
$numberOfExercises = 0;

array_shift($argv);
foreach ($argv as $option => $optionValue) {
    switch ($optionValue) {
        case is_numeric($optionValue) :
            if ($numberOfExercises) {
                echo 'cliRandy: number of exercises must be given only once.' . "\n";
                echo "Try `php cliRandy.php --help' for more information\n";
                exit();
            }
            $numberOfExercises = $optionValue;
            break;
        case '-n':
            $logging = false;
            break;
        case '--no-log':
            $logging = false;
            break;
        case '--help':
            exit(usage());
            break;
        default:
            echo "cliRandy: invalid option -- '" . $optionValue . "'\n\n";
            echo "Try `php cliRandy.php --help' for more information\n";
            exit();

    }
}

if ($logging) {
    $exer->addView(new FileLoggingView());
}

if ($numberOfExercises > 0) {
    $exer->showExercises($numberOfExercises);
} else {
    $exer->startInteractiveMode();
}
