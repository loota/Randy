Training application to output exercises selected at random, with repetitions selected at random.

* Example of interactive command line usage. Enter outputs a new exercise and q
  and enter will exit the program.
`php cliRandy.php`
    `selkäliike 6

hartiasilta 2

sukelluspunnerrus 5`
`q`

* Example usage for obtaining a list of three exercises:
`php cliRandy.php 3`
`sit-up 7
back extension 14
tiger pushup 3`

* Configuration format:
tiger pushup:    1   10
squat:           1   10
sit-up:          2   12
back extension:  3   15
shoulder bridge: 1   5
