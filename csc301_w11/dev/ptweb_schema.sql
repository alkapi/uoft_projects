DROP TABLE Accounts; DROP TABLE Courses;
DROP TABLE TeacherOf;
DROP TABLE Comments;
DROP TABLE Teachers;
DROP TABLE Interviews;
DROP TABLE Students;
DROP TABLE Parents;
DROP TABLE EnrolledIn;
DROP TABLE Schedule;
DROP TABLE SecondaryAcc;

create TABLE Courses(
	courseId INTEGER not NULL AUTO_INCREMENT,
	courseCode VARCHAR(40) UNIQUE not NULL,
	teacherId INTEGER not NULL,
 	courseName VARCHAR(40),
	roomNum VARCHAR(10),
	PRIMARY KEY (courseId),
	FOREIGN KEY (teacherId) REFERENCES Teachers(teacherId)
);

create TABLE Teachers(
	teacherId INTEGER not NULL AUTO_INCREMENT,
	fname VARCHAR(40),
	lname VARCHAR(40),
	passwd VARCHAR(40) not NULL,
	email VARCHAR(40) UNIQUE not NULL,
	PRIMARY KEY(teacherId)
);

create TABLE TeacherOf(
	fname VARCHAR(40) not NULL,
	lname VARCHAR(40) not NULL,
	courseCode VARCHAR(40) not NULL,
	PRIMARY KEY (courseCode),
	FOREIGN KEY (fname) REFERENCES Teachers(fname),
	FOREIGN KEY (lname) REFERENCES Teachers(lname),
	FOREIGN KEY (courseCode) REFERENCES Courses(courseCode)
);
	
create TABLE Comments(
	cmtID INTEGER UNIQUE not NULL,
	email VARCHAR(255) not NULL,
	_date DATE,
	name VARCHAR(255) not NULL,
	_comment VARCHAR(255),
	PRIMARY KEY (cmtID),
	FOREIGN KEY (email) REFERENCES Students(email)
);

create TABLE Interviews(
	teacherId INTEGER not NULL,
	time TIME not NULL,
	blocked BOOLEAN not NULL,
	year YEAR(4) not NULL,	
	accountId INTEGER,
	stuNum VARCHAR(40),
    courseCode VARCHAR(40),
	PRIMARY KEY (teacherId, time, year),
	FOREIGN KEY (teacherId) REFERENCES Teachers(teacherId),
	FOREIGN KEY (accountId) REFERENCES Accounts(accountId),
	FOREIGN KEY (courseCode) REFERENCES Courses(courseCode),
    FOREIGN KEY (stuNum) REFERENCES Students(stuNum)
);

create TABLE Students(
	stuNum VARCHAR(40) not NULL,
	fname VARCHAR(40) not NULL,
	lname VARCHAR(40) not NULL,
	course1 VARCHAR(40),
	course2 VARCHAR(40),
	course3 VARCHAR(40),
	course4 VARCHAR(40),
	course5 VARCHAR(40),
	course6 VARCHAR(40),
	course7 VARCHAR(40),
	course8 VARCHAR(40),
	course9 VARCHAR(40),
	email VARCHAR(40) not NULL,
	postalCode VARCHAR(10),
	birthYear YEAR(4),
	birthMonth INTEGER,
	birthDate INTEGER,
	PRIMARY KEY (stuNum),
	FOREIGN KEY (course1) REFERENCES Courses(courseCode),
	FOREIGN KEY (course2) REFERENCES Courses(courseCode),
	FOREIGN KEY (course3) REFERENCES Courses(courseCode),
	FOREIGN KEY (course4) REFERENCES Courses(courseCode),
	FOREIGN KEY (course5) REFERENCES Courses(courseCode),
	FOREIGN KEY (course6) REFERENCES Courses(courseCode),
	FOREIGN KEY (course7) REFERENCES Courses(courseCode),
	FOREIGN KEY (course8) REFERENCES Courses(courseCode),
	FOREIGN KEY (course9) REFERENCES Courses(courseCode)
);

create TABLE Parents(
	parentId INTEGER not NULL AUTO_INCREMENT,
	fname VARCHAR(40) not NULL,
	lname VARCHAR(40),
	PRIMARY KEY (parentId)
);

create TABLE EnrolledIn(
	stuNum VARCHAR(40) not NULL,
	courseCode VARCHAR(40) not NULL,
	PRIMARY KEY (stuNum, courseCode),
	FOREIGN KEY (stuNum) REFERENCES Students(stuNum),
	FOREIGN KEY (courseCode) REFERENCES Courses(courseCode)
);

/*
create TABLE ParentOf(
	parentId INTEGER not NULL,
	stuNum VARCHAR(40) not NULL,
	PRIMARY KEY (parentId, stuNum)
	FOREIGN KEY (parentId) REFERENCES Parents(parentId),
	FOREIGN KEY (stuNum) REFERENCES Students(stuNum)
);
*/


create TABLE Accounts(
	accountId INTEGER not NULL AUTO_INCREMENT,
	email VARCHAR(40) UNIQUE not NULL,
	passwd VARCHAR(40) not NULL,
	stuNum VARCHAR(40) not NULL,
	fname VARCHAR(40) not NULL,
	lname VARCHAR(40) not NULL,
	PRIMARY KEY (accountId),
	FOREIGN KEY (stuNum) REFERENCES Students(stuNum)
);

create TABLE Schedule(
	timeStart TIME not NULL,
	timeEnd TIME not NULL,
	PRIMARY KEY (timeStart)
);

create TABLE SecondaryAcc (
	email VARCHAR(40),
	stuNum VARCHAR(40),
	PRIMARY KEY (email, stuNum),
	FOREIGN KEY (email) REFERENCES Accounts(email),
	FOREIGN KEY (stuNum) REFERENCES Students(stuNum)
);
