NTCI Parent-Teacher Interview Booking Application
Team Oscar, U of T Software Engineering, Fall 2011
oscar.uoft@gmail.com

Files and Installation
======================

Unzip ntci_pt_interview_app.zip to a directory of your choice. The archive 
contains two directories: pt_interview and pt_database.

pt_interview contains the application files and applicable libraries. Place 
this directory in the root of the web server (often a folder called www). The 
application can then be accessed by pointing a web browser to your server's 
URL with /pt_interview appended at the end.

pt_database contains a MySQL script called ptweb_schema.sql that can be run at 
the MySQL command line to create the database schema the application relies 
on. This can be done from the MySQL command line with the command 
"\. [path_to_file]/ptweb_schema.sql", without the double quotes. The 
path_to_file is optional, but needs to be specified if you are not accessing 
the MySQL command line from the directory where the ptweb_schema.sql file 
resides.

The ptweb_load_schema.sql script can be safely ignored.

An administrive view of the system can be accessed using PHPMyAdmin. This will 
need to be installed on your server.

PHPMyAdmin can be found here: http://www.phpmyadmin.net

