<?php
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

class ExerDriver
{
    private $numberOfExercises = 0;
    private $logging = true;

    private function _checkOptions($options)
    {
        if (is_array($options)) {
            // remove the filename
            array_shift($options);

            foreach ($options as $option => $optionValue) {
                switch ($optionValue) {
                    case is_numeric($optionValue) :
                        if ($this->numberOfExercises) {
                            echo 'cliRandy: number of exercises must be given only once.' . "\n";
                            echo "Try `php cliRandy.php --help' for more information\n";
                            exit();
                        }
                        $this->numberOfExercises = $optionValue;
                        break;
                    case '-n':
                        $this->logging = false;
                        break;
                    case '--no-log':
                        $this->logging = false;
                        break;
                    case '--help':
                        exit($this->_usage());
                        break;
                    default:
                        echo "cliRandy: invalid option -- '" . $optionValue . "'\n\n";
                        echo "Try `php cliRandy.php --help' for more information\n";
                        exit();

                }
            }
        }

    }

    private function _usage()
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

    public function main($options)
    {
        $this->_checkOptions($options);

        $exer = new CommandLineExerciser();
        $exer->addView(new CommandLineView());

        if ($this->logging) {
            $exer->addView(new FileLoggingView());
        }

        if ($this->numberOfExercises > 0) {
            $exer->showExercises($this->numberOfExercises);
        } else {
            $exer->startInteractiveMode();
        }
    }
}

$driver = new ExerDriver();
$driver->main($argv);
