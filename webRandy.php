<?php
// Usage:
// Use a GET parameter named 'number' to signify how many exercises should be shown. If 
// there is no such parameter, shows one exercise.

require_once 'randy.php';

class WebView implements View {
    /**
     * @param array $exercises view data format @see View
     */
    public function show(array $exercises) {
        echo "<html>\n<dl>\n";
        foreach ($exercises as $exercise) {
            $name = key($exercise);
            $repetitions = array_pop($exercise);
            echo "<dt>$name</dt><dd>$repetitions</dd>\n";
        }
        echo "</dl>\n</html>";
    }
}

try {
    $exer = new Exerciser();
} catch (Exception $e) {
    echo '<html>';
    echo '<body>';
    echo '<span>';
    echo $e->getMessage();
    echo '</span>';
    echo '</body>';
    echo '</html>';
    exit(2);
}
$exer->addView(new WebView());
$number = 1;
if (is_array($_GET) && count($_GET) > 0) {
    if ($_GET['number']) {
        $number = $_GET['number'];
    }
} 
if ($number > 0) {
    $exer->showExercises($number);
} else {
    $exer->showExercises(1);
}
