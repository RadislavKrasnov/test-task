# test-task
test-task

Installation documentation

If you want to download this application you need to clone this repository into folder where installed you local machine.

Please, don't set up folder of you server on /folder/test-task, it need look like this /folder, 
and use such routs  like http://localhost/test-task/index.php in your browser.

You can to download this repository in two ways:

- git clone https://github.com/RadislavKrasnov/test-task.git
- download archive file

Since, you have installed this app already, you can create database tables, for this just import sql dump file of 
database (mysql -u username -p database_name < test_task.sql). This file you will find in project folder.

Your can to connect application with database in core/init.php file.
