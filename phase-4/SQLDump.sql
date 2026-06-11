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
INSERT INTO `Transaction` VALUES (1, 500.00, '2023-01-10', 1), (2, 1200.00, '2023-02-15', 1), (3, 300.00, '2023-03-20', 3), (4, 5000.00, '2023-04-05', 4), (5, 750.00, '2023-05-18', 2), (6, 2000.00, '2023-06-22', 6), (7, 8000.00, '2023-07-30', 5), (8, 450.00, '2023-08-11', 3), (9, 3000.00, '2023-09-01', 7), (10, 900.00, '2023-10-05', 6);
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
SELECT AVG(Amount) AS AvgAmount FROM `Transaction`
HAVING AVG(Amount) > 500;

-- Q14: Filtered Sort
SELECT Number, Balance FROM Account WHERE Balance > 0 ORDER BY Balance DESC;

-- Q15: Join with Aggregation
SELECT BK.Name, COUNT(BR.BranchID) AS BranchCount FROM Bank BK, Branch BR
WHERE BK.BankID = BR.BankID GROUP BY BK.BankID, BK.Name HAVING COUNT(BR.BranchID);


-- ============================================================
-- SECTION 4: TRIGGERS AND STORED PROCEDURES
-- ============================================================

-- ------------------------------------------------------------
-- Trigger 1 (by Borhan Javadian): Account Balance Cannot Go Negative
-- ------------------------------------------------------------
-- Fires BEFORE any UPDATE on Account. Blocks the update if the new
-- Balance value would be negative, with a clear custom error message.

DELIMITER $$

CREATE TRIGGER trg_prevent_negative_balance
BEFORE UPDATE ON Account
FOR EACH ROW
BEGIN
    IF NEW.Balance < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Account balance cannot go negative';
    END IF;
END$$

DELIMITER ;

-- ------------------------------------------------------------

-- Trigger 2 (by Bardiya Shavandi): Employee Work Date Validation
-- ------------------------------------------------------------
-- Fires BEFORE any INSERT on WorksIn. Blocks the insert if:
--   (a) Until is not NULL and Until <= StartFrom, or
--   (b) StartFrom is in the future.

DELIMITER $$

CREATE TRIGGER trg_validate_worksin_dates
BEFORE INSERT ON WorksIn
FOR EACH ROW
BEGIN
    IF NEW.Until IS NOT NULL AND NEW.Until <= NEW.StartFrom THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'End date cannot be before or equal to start date';
    END IF;

    IF NEW.StartFrom > CURRENT_DATE THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Start date cannot be in the future';
    END IF;
END$$

DELIMITER ;

-- ------------------------------------------------------------
-- Trigger 3 (by Faraz Sahabi): Transaction Amount Validation
-- ------------------------------------------------------------
-- Blocks the insert if the Amount is zero or negative.

DELIMITER $$

CREATE TRIGGER trg_validate_transaction_amount
BEFORE INSERT ON `Transaction`
FOR EACH ROW
BEGIN
    IF NEW.Amount <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Transaction amount must be strictly positive';
    END IF;
END$$

DELIMITER ;
-- ------------------------------------------------------------

-- extra trigger (not featured on the user homepage):
-- Internal helper trigger (not featured on the user homepage):
-- Keeps Account.Balance in sync with the Transaction ledger.
-- Whenever a Transaction row is successfully inserted, the linked
-- Account's Balance is decremented by the transaction amount.
-- This trigger composes with trg_prevent_negative_balance: an
-- attempted transaction so large it would drive the balance below
-- zero will cause that BEFORE UPDATE trigger to raise a SIGNAL,
-- which rolls the entire INSERT back so neither table changes.
-- I have added this trigger to ensure that the balance is always accurate and that the validation logic is properly tested in an integrated manner. It also adds realism to the system by automatically applying transactions to account balances.
DELIMITER $$

CREATE TRIGGER trg_apply_transaction_to_balance
AFTER INSERT ON `Transaction`
FOR EACH ROW
BEGIN
    UPDATE Account
    SET Balance = Balance - NEW.Amount
    WHERE AccountID = NEW.AccountID;
END$$

DELIMITER ;
-- ------------------------------------------------------------






-- ------------------------------------------------------------
-- Stored Procedure 1 (by Borhan Javadian): Customer Balances in Range
-- ------------------------------------------------------------
-- Returns every customer together with each of their accounts whose
-- Balance falls within [p_min, p_max], sorted by balance ascending.

DELIMITER $$

CREATE PROCEDURE GetCustomersInBalanceRange(
    IN p_min DECIMAL(15, 2),
    IN p_max DECIMAL(15, 2)
)
BEGIN
    SELECT C.CustomerID, C.FullName, C.Phone, C.Email,
           A.Number AS AccountNumber, A.Balance
    FROM Customer C
    JOIN Account A ON C.CustomerID = A.CustomerID
    WHERE A.Balance BETWEEN p_min AND p_max
    ORDER BY A.Balance ASC;
END$$

DELIMITER ;

-- Stored Procedure 2 (by Bardiya Shavandi): Employees by Date Range
-- ------------------------------------------------------------
-- Returns every employee whose WorksIn record STARTED inside
-- [p_start, p_end], together with the branch they were assigned to.
-- The previous overlap-based logic was replaced with a simpler
-- "started in range" rule so that open-ended assignments (Until IS NULL)
-- with a StartFrom outside the range are no longer reported.

DELIMITER $$

CREATE PROCEDURE GetEmployeesByDateRange(
    IN p_start DATE,
    IN p_end DATE
)
BEGIN
    SELECT DISTINCT E.EmployeeID, E.FullName, E.Position, E.Salary,
           W.StartFrom, W.Until, B.Address AS BranchAddress
    FROM Employee E
    JOIN WorksIn W ON E.EmployeeID = W.EmployeeID
    JOIN Branch B ON W.BranchID = B.BranchID
    WHERE W.StartFrom >= p_start
      AND W.StartFrom <= p_end;
END$$

DELIMITER ;


-- ------------------------------------------------------------
-- Stored Procedure 3 (by Faraz Sahabi): Transaction Count by Customer
-- ------------------------------------------------------------
-- Returns the total number of transactions associated with a given CustomerID.

DELIMITER $$

CREATE PROCEDURE GetTransactionCountByCustomer(
    IN p_customer_id INT
)
BEGIN
    SELECT COUNT(T.TransactionID) AS TransactionCount
    FROM Account A
    JOIN `Transaction` T ON A.AccountID = T.AccountID
    WHERE A.CustomerID = p_customer_id;
END$$

DELIMITER ;
