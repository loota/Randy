<?php
require_once 'randy.php';

/**
 * Interactive command line input. Commands:
 *  <Enter>          - show one exercise
 *  <Number> <Enter> - show <Number> exercises
 *  q                - Exit
 */
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
    /**
     * @param array $exercises view data format. @see View
     */
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
    private $_logging = true;
    private $_filename;

    /**
     * @param array $options command line options in the argv form
     */
    private function _checkOptions($options)
    {
        if (is_array($options)) {
            // remove the filename
            array_shift($options);

            foreach ($options as $optionString) {
                $optionSplit = split('=', $optionString);
                $option = $optionSplit[0];
                $optionValue = $optionSplit[1];
                switch ($option) {
                    case is_numeric($option) :
                        if ($this->numberOfExercises) {
                            echo 'cliRandy: number of exercises must be given only once.' . "\n";
                            echo "Try `php cliRandy.php --help' for more information\n";
                            exit();
                        }
                        $this->numberOfExercises = $option;
                        break;
                    case '-n':
                        $this->_logging = false;
                        break;
                    case '--no-log':
                        $this->_logging = false;
                        break;
                    case '-f':
                        $this->_filename = $optionValue;
                        break;
                    case '--help':
                        exit($this->_usage());
                        break;
                    default:
                        echo "cliRandy: invalid option -- '" . $option . "'\n\n";
                        echo "Try `php cliRandy.php --help' for more information\n";
                        exit();

                }
            }
        }

    }

    /**
     *  @return string
     */
    private function _usage()
    {
        $usage = "Usage: php cliRandy.php [-n] [--no-log] [-f filename] [number of exercises]
        Show <number> of exercises
        
        * start interactive mode
         php cliRandy.php
        
        * In the interactive mode the following commands apply: 
            q quits
            enter key shows one exercise. 
            Typing a number and then enter shows that number of exercises";
        return $usage;
    }

    /**
     * @param array $options command line options in the argv form
     */
    public function main($options)
    {
        $this->_checkOptions($options);

        $exer = new CommandLineExerciser($this->_filename);
        $exer->addView(new CommandLineView());

        if ($this->_logging) {
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
