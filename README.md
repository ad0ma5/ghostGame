#Ghost Game
-

##Install

` git clone https://github.com/ad0ma5/ghostGame.git `

` edit api/config.php - set path to dictionary `

` navigate to folder @ your localhost `

##Structure


```
README.md               --> this file
index.html              --> front-end web-app
api/                    --> all of the source files for the api
  config.php            --> configuration file
  api/GhostGame.php     --> ghost game class
  api/index.php         --> main api entry-point
js/                     --> js code
   app.js               --> jquery webapp code
```


##Notes

For opimum performance the result set from last play would be cached/saved as next word must be from selected sub-list. This would result in avoiding loading all the dictionary on every game step. As the only storage i'm using is session i decided to avoid saving list there.

##If the human starts the game with 'n', and the computer plays according to the strategy above, what unique word will complete the human's victory?

If the result set would not be shuffled the unique word should be 'naan'.

As we shuffle result set, the winning word for player would the the one having even number of letters and the one not having complete sub-word.

##Unit tests

` cd api `

` phpunit GhostGameTest.php `
