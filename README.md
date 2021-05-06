# cs348-project-duetifier
Duetifier is a reminder web application designed for collage assignments.

## Related link
Duetifier application:\
https://duetifier.uc.r.appspot.com

Project stage 2 link:\
https://drive.google.com/drive/folders/1NBSfq-gZMkWNHsVFNLt1FF9aNv_wguYq?usp=sharing

Project stage 3 link:\
https://drive.google.com/drive/folders/1vdjh8TKrOC1EkAQq0yMOrN5sJJqvJeD9

Project demo YouTube link:\
https://youtu.be/ifsL-iUY0RQ

## Usage
* Use Duetifier online application with the link provided above.
    * **Note**: In case the Dutifier application url does not work, please let us know. This may due to the instability of the Google cloud account billing.
* Use Duetifier application on your local devices.
    1. Import the `database.sql` to your local SQL server.
    2. Change the `config.php` file in `assets/functions/` folder. Instruction has been provided in `config.php` file.
    3. Run on your PHP local server.

## Code description
### Index
Index settings are located in `database.sql`.
### Transaction and Isolation level
There are 4 transactions with isolation level setting in this project.\
They are located in files `course_edit.php`, `events_edit.php`, `events_del.php`, and `events_update.php`, respectively.
### Stored procedures
6 stored procedures are predefined in `database.sql` and are utilized in files `index.php`, `course_edit.php`, `events_edit.php`, `events_del.php`, and `events_update.php`.
### Prepared statements
There are 21 places where prepared statement is used. They are located in file `index.php`, `functions.php`, `course_edit.php`, and `events_edit.php`. Among them, 4 prepared statements are predefined in `config.php` file.
