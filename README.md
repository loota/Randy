Training application to output exercises selected at random, with repetitions selected at random.

Example of interactive command line session. Enter outputs a new exercise and q and enter will exit the program.
  <pre>
  php cliRandy.php
  </pre>
  <pre>
  back extension 6
  
  shoulder bridge 2
  
  tiger pushup 5
  q
  </pre>

Example usage for obtaining a list of three exercises:
  <pre>
  php cliRandy.php 3
  </pre>

  <pre>
  sit-up 7
  back extension 14
  tiger pushup 3
  </pre>

Configuration format (name, minimum possible repetitions, maximum possible repetitions):

<pre>
  tiger pushup:    1   10
  squat:           1   10
  sit-up:          2   12
  back extension:  3   15
  shoulder bridge: 1   5
</pre>
