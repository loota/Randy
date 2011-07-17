<?php
require_once 'randy.php';

class WebView implements View {
    public function show($exercises) {
        echo "<html>\n<dl>\n";
        foreach ($exercises as $exercise) {
            $name = key($exercise);
            $repetitions = array_pop($exercise);
            echo "<dt>$name</dt><dd>$repetitions</dd>\n";
        }
        echo "</dl>\n</html>";
    }
}

$exer = new Exerciser();
$exer->addView(new WebView());
$number = $_GET['number'];
if ($number > 0) {
    $exer->showExercises($number);
} else {
    $exer->showExercises(1);
}

