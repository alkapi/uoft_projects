INSERT INTO Accounts (email, passwd, stuNum, fname, lname)
VALUES
('lisa@test.ca', 'flower', '000', 'Lisa', 'Chang'),
('andy@test.ca', 'foo', '111', 'Andy', 'Smith'),
('jenny@test.com', 'bar', '222', 'Jenny', 'Wong');

INSERT INTO Students (stuNum, fname, lname, course1, course2, course3,
		course4, course5, course6, course7, course8, course9, email,
		postalCode, birthYear, birthMonth, birthDate)
VALUES
('000', 'Linda', 'Chang', 'csc108', 'csc207', 'csc389', 'mat224', 'mat244', 
 'csc987', 'mat167', 'mat567', 'mat321', 'linda@test.ca', 'M2M3Z7', '1990', '03', '23'),
('111', 'Aaron', 'Smith', 'mat224', 'csc108', 'csc207', 'csc389', 'mat224', 'mat244',
 'ant100', 'eng185', 'hps285', 'aaron@test.ca', 'M2M3Z7', '1989', '01', '22'),
('222', 'Cathy', 'Wong', 'ant100', 'csc987', 'mat167', 'mat567', 'mat321', 'csc207', 
 'csc389', 'mat224', 'mat244', 'cathy@test.ca', 'M5G1R1', '1995', '02', '12'),
('333', 'Max', 'Chang', 'csc108', 'csc207', 'csc389', 'mat224', 'mat244', 'csc389', 
 'mat224', 'mat244', 'mat567', 'max@test.ca', 'M2M3Z7', '1991', '03', '21');

INSERT INTO Courses (courseCode, teacherId, courseName, roomNum)
VALUES
('csc108', '1', 'Intro to Programming', '300'),
('csc207', '2', 'Java Fundamentals', '312A'),
('csc389', '3', 'Intro to C++', '121'),
('mat224', '4', 'Linear Algebra', '200'),
('mat244', '5', 'Partial Diff Equations', '212'),
('csc987', '6', 'Communication in CS', '167'),
('mat167', '7', 'Calculus I', '100A'),
('ant100', '8', 'Anthropology', '300'),
('mat567', '9', 'Math Puzzles', '201'),
('mat321', '10', 'Calculus II', '122A'),
('eng185', '12', 'English I', '210'),
('hps285', '11', 'History of Science', '301');

INSERT INTO Teachers (fname, lname, passwd, email)
VALUES
('Gary', 'Castle', 'castlebing2011', 'gary.castle@ntci.ca'),
('Nellie', 'Sander', 'leggomyeggo', 'sander.nell@ntci.ca'),
('Andrew', 'King', 'kingslions', 'andy.king@ntci.ca'),
('Sandy', 'Bullock', '45alligators', 'bull.sand@ntci.ca'),
('Nitesh', 'Chakravorty', 'chakaboy', 'chaka.nit@ntci.ca'),
('Jane', 'Li', 'lolla', 'jane.li@ntci.ca'),
('Marge', 'Simpson', 'madgemuffin', 'madge.simps@ntci.ca'),
('Ayra', 'Stark', 'drothrakia', 'stark.ayra@ntci.ca'),
('Nellie', 'Furtado', 'jellynelly', 'furtado.nellie@ntci.ca'),
('John', 'Doe', 'deerman', 'john.doe@ntci.ca'),
('Jane', 'Doe', '123appleTree', 'doe.jane@bellnet.ca'),
('Ruskav', 'Bolshoi', 'leaningPisa', 'rus.bolsh@rogers.ca');

INSERT INTO Parents (fname, lname)
VALUES
('Lisa', 'Chang'),
('Andy', 'Smith'),
('Jenny', 'Wong');

#INSERT INTO ParentOf (parentId, stuNum)
#VALUES
#('1', '111'),
#('2', '222'),
#('3', '222');

INSERT INTO Schedule (timeStart, timeEnd)
VALUES
('1:55', '4:15'),
('5:55', '8:15');

INSERT INTO SecondaryAcc(email, stuNum)
VALUES
('lisa@test.ca', '333');
