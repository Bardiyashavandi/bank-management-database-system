-- ============================================================
-- Bank Management Database System
-- Phase 3: Full SQL Script (CREATE + INSERT + Queries)
-- Group 10
-- Borhan Javadian (35012), Bardiya Shavandi (33588), Faraz Sahabi (34557)
-- ============================================================
CREATE SCHEMA IF NOT EXISTS bankmanagement;
USE bankmanagement;
-- ============================================================
-- SECTION 1: TABLE CREATION
-- ============================================================

CREATE TABLE Country (
    CountryID INT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL
);

CREATE TABLE Bank (
    BankID INT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL
);

CREATE TABLE PlacedIn (
    BankID INT NOT NULL,
    CountryID INT NOT NULL,
    PRIMARY KEY (BankID, CountryID),
    FOREIGN KEY (BankID) REFERENCES Bank(BankID) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (CountryID) REFERENCES Country(CountryID) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE Manager (
    ManagerID INT PRIMARY KEY,
    FullName VARCHAR(255) NOT NULL
);

CREATE TABLE Employee (
    EmployeeID INT PRIMARY KEY,
    FullName VARCHAR(255) NOT NULL,
    Position VARCHAR(100) NOT NULL,
    Salary DECIMAL(15, 2) NOT NULL CHECK (Salary > 0)
);

CREATE TABLE Manages (
    ManagerID INT NOT NULL,
    EmployeeID INT NOT NULL,
    StartFrom DATE NOT NULL,
    Until DATE,
    PRIMARY KEY (ManagerID, EmployeeID, StartFrom),
    FOREIGN KEY (ManagerID) REFERENCES Manager(ManagerID) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Branch (
    BranchID INT PRIMARY KEY,
    Address VARCHAR(255) NOT NULL,
    BankID INT NOT NULL,
    FOREIGN KEY (BankID) REFERENCES Bank(BankID) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE WorksIn (
    EmployeeID INT NOT NULL,
    BranchID INT NOT NULL,
    StartFrom DATE NOT NULL,
    Until DATE,
    PRIMARY KEY (EmployeeID, BranchID, StartFrom),
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (BranchID) REFERENCES Branch(BranchID) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE Customer (
    CustomerID INT PRIMARY KEY,
    FullName VARCHAR(255) NOT NULL,
    Phone VARCHAR(20),
    Email VARCHAR(255)
);

CREATE TABLE Account (
    AccountID INT PRIMARY KEY,
    Number VARCHAR(50) NOT NULL UNIQUE,
    Balance DECIMAL(15, 2) NOT NULL DEFAULT 0 CHECK (Balance >= 0),
    OpenDate DATE NOT NULL,
    CustomerID INT NOT NULL,
    BranchID INT NOT NULL,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (BranchID) REFERENCES Branch(BranchID) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE `Transaction` (
    TransactionID INT PRIMARY KEY,
    Amount DECIMAL(15, 2) NOT NULL CHECK (Amount > 0),
    Currency VARCHAR(10) NOT NULL,
    TransDate DATE NOT NULL,
    AccountID INT NOT NULL,
    FOREIGN KEY (AccountID) REFERENCES Account(AccountID) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE Transfer (
    TransactionID INT PRIMARY KEY,
    Recipient VARCHAR(255) NOT NULL,
    FOREIGN KEY (TransactionID) REFERENCES `Transaction`(TransactionID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Loan (
    TransactionID INT PRIMARY KEY,
    Maturity DATE NOT NULL,
    FOREIGN KEY (TransactionID) REFERENCES `Transaction`(TransactionID) ON DELETE CASCADE ON UPDATE CASCADE
);

-- ============================================================
-- SECTION 2: SAMPLE DATA
-- ============================================================

INSERT INTO Country VALUES (1, 'United States'), (2, 'United Kingdom'), (3, 'Germany'), (4, 'France'), (5, 'Turkey');
INSERT INTO Bank VALUES (1, 'Global Trust Bank'), (2, 'Northern Finance'), (3, 'Euro Capital Bank'), (4, 'Atlantic Savings'), (5, 'Vakif Bank');
INSERT INTO PlacedIn VALUES (1, 1), (1, 2), (2, 2), (2, 3), (3, 3), (3, 4), (4, 1), (4, 5), (5, 5);
INSERT INTO Manager VALUES (1, 'Alice Johnson'), (2, 'Bob Smith'), (3, 'Clara Davis'), (4, 'David Lee'), (5, 'Eva Martinez');
INSERT INTO Employee VALUES (1, 'Frank White', 'Teller', 35000.00), (2, 'Grace Hall', 'Analyst', 52000.00), (3, 'Henry Brown', 'Teller', 34000.00), (4, 'Isla Green', 'Advisor', 61000.00), (5, 'Jack Black', 'Teller', 33000.00), (6, 'Karen Young', 'Analyst', 55000.00), (7, 'Leo King', 'Advisor', 60000.00);
INSERT INTO Branch VALUES (1, '123 Main St, New York', 1), (2, '456 High St, London', 2), (3, '789 Berliner Str, Berlin', 3), (4, '12 Rue de Paris, Paris', 3), (5, '99 Ataturk Blvd, Ankara', 5);
INSERT INTO Manages VALUES (1, 1, '2020-01-01', NULL), (1, 3, '2021-03-15', NULL), (2, 2, '2019-06-01', NULL), (3, 4, '2022-01-01', NULL), (4, 5, '2020-09-01', '2023-09-01'), (5, 6, '2021-01-01', NULL), (5, 7, '2023-01-01', NULL);
INSERT INTO WorksIn VALUES (1, 1, '2020-01-01', NULL), (2, 1, '2019-06-01', NULL), (3, 2, '2021-03-15', NULL), (4, 3, '2022-01-01', NULL), (5, 4, '2020-09-01', NULL), (6, 5, '2021-01-01', NULL), (7, 5, '2023-01-01', NULL);
INSERT INTO Customer VALUES (1, 'Oliver Stone', '05301112233', 'oliver@email.com'), (2, 'Paula Reed', '05302223344', 'paula@email.com'), (3, 'Quinn Adams', '05303334455', NULL), (4, 'Rachel Ford', NULL, 'rachel@email.com'), (5, 'Sam Turner', '05305556677', 'sam@email.com'), (6, 'Tina Brooks', '05306667788', 'tina@email.com');
INSERT INTO Account VALUES (1, 'ACC-001', 15000.00, '2020-03-01', 1, 1), (2, 'ACC-002', 8500.00, '2021-07-15', 2, 1), (3, 'ACC-003', 23000.00, '2019-11-20', 3, 2), (4, 'ACC-004', 5000.00, '2022-01-10', 4, 3), (5, 'ACC-005', 42000.00, '2018-05-30', 5, 4), (6, 'ACC-006', 3200.00, '2023-08-01', 6, 5), (7, 'ACC-007', 11000.00, '2021-02-14', 1, 2);
INSERT INTO `Transaction` VALUES (1, 500.00, 'USD', '2023-01-10', 1), (2, 1200.00, 'USD', '2023-02-15', 1), (3, 300.00, 'GBP', '2023-03-20', 3), (4, 5000.00, 'EUR', '2023-04-05', 4), (5, 750.00, 'USD', '2023-05-18', 2), (6, 2000.00, 'TRY', '2023-06-22', 6), (7, 8000.00, 'EUR', '2023-07-30', 5), (8, 450.00, 'GBP', '2023-08-11', 3), (9, 3000.00, 'USD', '2023-09-01', 7), (10, 900.00, 'TRY', '2023-10-05', 6);
INSERT INTO Transfer VALUES (1, 'Paula Reed'), (3, 'Oliver Stone'), (5, 'Sam Turner'), (8, 'Rachel Ford'), (9, 'Tina Brooks');
INSERT INTO Loan VALUES (2, '2025-02-15'), (4, '2026-04-05'), (6, '2027-06-22'), (7, '2028-07-30'), (10, '2026-10-05');

-- ============================================================
-- SECTION 3: SQL QUERIES (Aligned with Report)
-- ============================================================

-- Queries by Borhan Javadian (35012)

-- Q1: Selection + Projection
SELECT FullName, Salary FROM Employee WHERE Salary > 50000;

-- Q2: Join between 3 Tables
SELECT A.Number, C.FullName, B.Address
FROM Account A, Customer C, Branch B
WHERE A.CustomerID = C.CustomerID AND A.BranchID = B.BranchID;

-- Q3: Aggregation with Having
SELECT AccountID, SUM(Amount) AS TotalAmount
FROM `Transaction` GROUP BY AccountID HAVING SUM(Amount) > 1000;

-- Q4: Sorting Multiple Columns
SELECT FullName, Position, Salary FROM Employee
ORDER BY Position ASC, Salary DESC;

-- Q5: Nested Subquery
SELECT FullName, Salary FROM Employee
WHERE Salary > (SELECT AVG(Salary) FROM Employee);


-- Queries by Bardiya Shavandi (33588)

-- Q6: Join + Aggregate + Sort
SELECT C.FullName, SUM(A.Balance) AS TotalBalance
FROM Customer C, Account A WHERE C.CustomerID = A.CustomerID
GROUP BY C.CustomerID, C.FullName ORDER BY TotalBalance DESC;

-- Q7: EXISTS Logic
SELECT DISTINCT C.FullName FROM Customer C
WHERE EXISTS (SELECT * FROM Account A, `Transaction` T 
              WHERE A.AccountID = T.AccountID AND A.CustomerID = C.CustomerID);

-- Q8: Count with Grouping
SELECT B.Address, COUNT(A.AccountID) AS AccountCount
FROM Branch B, Account A WHERE B.BranchID = A.BranchID
GROUP BY B.BranchID, B.Address;

-- Q9: Set Difference (NOT IN)
SELECT C.FullName FROM Customer C WHERE C.CustomerID NOT IN 
(SELECT A.CustomerID FROM Account A, `Transaction` T, Loan L 
 WHERE A.AccountID = T.AccountID AND T.TransactionID = L.TransactionID);

-- Q10: Basic Retrieval
SELECT * FROM Customer;


-- Queries by Faraz Sahabi (34557)

-- Q11: Single Column Sort
SELECT Number, Balance FROM Account ORDER BY Balance ASC;

-- Q12: Complex Join
SELECT E.FullName, B.Address FROM Employee E, WorksIn W, Branch B
WHERE E.EmployeeID = W.EmployeeID AND W.BranchID = B.BranchID AND E.Salary > 60000;

-- Q13: Average with Having
SELECT Currency, AVG(Amount) AS AvgAmount FROM `Transaction`
GROUP BY Currency HAVING AVG(Amount) > 500;

-- Q14: Filtered Sort
SELECT Number, Balance FROM Account WHERE Balance > 0 ORDER BY Balance DESC;

-- Q15: Join with Aggregation
SELECT BK.Name, COUNT(BR.BranchID) AS BranchCount FROM Bank BK, Branch BR
WHERE BK.BankID = BR.BankID GROUP BY BK.BankID, BK.Name HAVING COUNT(BR.BranchID) > 1;