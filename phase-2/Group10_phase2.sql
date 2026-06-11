-- Phase 2: Bank Management Database System
-- SQL Script for Table Creation
-- Group 10
USE Project;

-- 1. Institutional & Global Structure
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

-- 2. Management & Workforce
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

-- 3. Core Banking
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
    Phone VARCHAR(20), -- VARCHAR to preserve leading zeros
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

-- 4. Transactions
CREATE TABLE Transaction (
    TransactionID INT PRIMARY KEY,
    Amount DECIMAL(15, 2) NOT NULL CHECK (Amount > 0),
    Currency VARCHAR(10) NOT NULL,
    TransDate DATE NOT NULL,
    AccountID INT NOT NULL,
    FOREIGN KEY (AccountID) REFERENCES Account(AccountID) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Specialized Transaction Types (ISA Subtypes)
CREATE TABLE Transfer (
    TransactionID INT PRIMARY KEY,
    Recipient VARCHAR(255) NOT NULL,
    FOREIGN KEY (TransactionID) REFERENCES Transaction(TransactionID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Loan (
    TransactionID INT PRIMARY KEY,
    Maturity DATE NOT NULL,
    FOREIGN KEY (TransactionID) REFERENCES Transaction(TransactionID) ON DELETE CASCADE ON UPDATE CASCADE
);