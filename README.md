The installation steps:

1) git clone <repository>

2) go to the main folder -- threapy-box

3) run 'composer install' in the command line(assuming you have composer)

4) run 'npm install' in the command line(assuming you have node.js and npm)
   but this step is optional if you are not in a dev mode

5) copy threapy-box.sql from dp_dump folder and install the database in your local MySQL database

6) Change your username and password for database in the config.php which resides in config folder

7) run 'composer run --timeout=0 serve' in the command line

8) browser the site in the localhost:8080
