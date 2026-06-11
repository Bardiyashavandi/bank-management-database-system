# Bank Management Database System

> A full-stack bank management system built across 4 phases — from ER modeling to a live PHP web application backed by **MySQL** and **MongoDB**, hosted on **XAMPP/Apache**.  
> Developed for CS 306 — Database Systems (Spring 2025/2026).

---

## Table of Contents

- [Project Overview](#project-overview)
- [Tech Stack](#tech-stack)
- [Project Phases](#project-phases)
  - [Phase 1 — ER Design](#phase-1--er-design)
  - [Phase 2 — Relational Schema & SQL](#phase-2--relational-schema--sql)
  - [Phase 3 — Queries & Relational Algebra](#phase-3--queries--relational-algebra)
  - [Phase 4 — Web Application](#phase-4--web-application)
- [Features](#features)
- [Repository Structure](#repository-structure)
- [How to Use](#how-to-use)
  - [1. Prerequisites](#1-prerequisites)
  - [2. Clone the Repository](#2-clone-the-repository)
  - [3. Set Up the Database](#3-set-up-the-database)
  - [4. Configure MongoDB](#4-configure-mongodb)
  - [5. Deploy with XAMPP](#5-deploy-with-xampp)
  - [6. Access the Application](#6-access-the-application)
- [Database Schema](#database-schema)
- [Triggers & Stored Procedures](#triggers--stored-procedures)
- [Support Ticket System](#support-ticket-system)
- [Course Context](#course-context)
- [License](#license)

---

## Project Overview

This project develops a **bank management database application** from scratch, progressing through four structured phases: conceptual modeling, relational schema design, SQL querying, and full web integration.

The final product is a locally-hosted web application with two interfaces — a **user dashboard** for interacting with database triggers and stored procedures, and an **admin dashboard** for managing a MongoDB-backed support ticket system.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Web Server | Apache (via XAMPP) |
| Backend | PHP 8+ |
| Relational Database | MySQL (`bankmanagement` database) |
| NoSQL Database | MongoDB (support ticket system) |
| Frontend | HTML/CSS (PHP-rendered) |
| Dev Environment | XAMPP (Windows/macOS/Linux) |

---

## Project Phases

### Phase 1 — ER Design

Defined the scope of the bank management application and produced a complete **Entity-Relationship (ER) diagram** in Chen notation.

- Identified entities, attributes, and relationships
- Applied key constraints, participation constraints, and cardinality rules
- Deliverable: `phase-1/Group10_phase1.pdf`

### Phase 2 — Relational Schema & SQL

Converted the ER diagram into a full **relational schema** and implemented it in SQL.

- Applied ER-to-relational mapping rules (1:1, 1:N, M:N)
- Defined primary keys, foreign keys, and `NOT NULL` constraints
- Wrote `CREATE TABLE` statements for the entire database
- Deliverables: `phase-2/Group10_phase2.pdf`, `phase-2/Group10_phase2.sql`

### Phase 3 — Queries & Relational Algebra

Populated the database with sample data and wrote a comprehensive set of SQL queries, each paired with its **Relational Algebra** equivalent.

- Covered query categories: basic selection/projection, joins (2- and 3-table), aggregates (`COUNT`, `AVG`, `MIN`, `MAX`, `SUM`), `GROUP BY`, `ORDER BY`
- At least 15 queries covering all required categories
- Deliverables: `phase-3/Group10_phase3.pdf`, `phase-3/Project.sql`

### Phase 4 — Web Application

Extended the database into a fully functional **PHP web application** with both user and admin interfaces.

- Integrated MySQL triggers and stored procedures into interactive web pages
- Built a **MongoDB-backed support ticket system** for user–admin communication
- Hosted locally via XAMPP/Apache
- Deliverables: `phase-4/` (full source), `phase-4/report.pdf`

---

## Features

**User Interface (`/user`):**
- Interactive pages to test each implemented **trigger** (with live feedback on success/blocked operations)
- Interactive pages to call each **stored procedure** with user-supplied parameters
- Support ticket creation and tracking

**Admin Interface (`/admin`):**
- View all active support tickets across all users
- Comment on tickets as `admin`
- Mark tickets as resolved

**Database Logic:**
- 3 MySQL triggers enforcing business rules
- 3 MySQL stored procedures for querying and reporting
- Full relational schema with foreign key constraints

---

## Repository Structure

```
bank-management-database-system/
│
├── docs/                               # Project specification PDFs (course-issued)
│   ├── phase-1-spec.pdf
│   ├── phase-2-spec.pdf
│   ├── phase-3-spec.pdf
│   └── phase-4-spec.pdf
│
├── phase-1/
│   └── Group10_phase1.pdf              # ER diagram and project description
│
├── phase-2/
│   ├── Group10_phase2.pdf              # Relational schema report
│   └── Group10_phase2.sql              # CREATE TABLE statements
│
├── phase-3/
│   ├── Group10_phase3.pdf              # SQL queries + relational algebra report
│   └── Project.sql                     # Queries and populated data
│
├── phase-4/                            # Web application source
│   ├── Scripts/
│   │   ├── user/                       # User-facing PHP pages
│   │   │   ├── index.php               # User dashboard
│   │   │   ├── db.php                  # MySQL connection config
│   │   │   ├── trigger_1.php           # Trigger: Account balance cannot go negative
│   │   │   ├── trigger_2.php           # Trigger: Employee work date validation
│   │   │   ├── trigger_3.php           # Trigger: (third trigger)
│   │   │   ├── procedure_1.php         # Procedure: Get customers in balance range
│   │   │   ├── procedure_2.php         # Procedure: (second procedure)
│   │   │   ├── procedure_3.php         # Procedure: (third procedure)
│   │   │   ├── tickets.php             # View/create support tickets
│   │   │   ├── ticket_create.php       # New ticket form
│   │   │   ├── ticket_detail.php       # Single ticket view
│   │   │   ├── ticket_store.php        # MongoDB ticket operations
│   │   │   └── common_ui.php           # Shared UI components
│   │   └── admin/                      # Admin-facing PHP pages
│   │       ├── index.php               # Admin dashboard (active tickets)
│   │       ├── detail.php              # Ticket detail + comment/resolve
│   │       ├── ticket_store.php        # MongoDB ticket operations (admin)
│   │       └── common_ui.php           # Shared UI components
│   ├── SQLDump.sql                     # Full MySQL database dump (schema + data)
│   └── report.pdf                      # Phase 4 project report
│
├── LICENSE
└── README.md
```

---

## How to Use

### 1. Prerequisites

- [**XAMPP**](https://www.apachefriends.org/) (includes Apache, PHP, MySQL)
- [**MongoDB Community Server**](https://www.mongodb.com/try/download/community)
- [**MongoDB PHP Driver**](https://www.php.net/manual/en/mongodb.installation.php) (`mongodb` extension for PHP)
- PHP 8.0 or higher

### 2. Clone the Repository

```bash
git clone https://github.com/<your-username>/bank-management-database-system.git
```

### 3. Set Up the Database

1. Start **XAMPP** and ensure Apache and MySQL are running.
2. Open **phpMyAdmin** at `http://localhost/phpmyadmin`.
3. Create a new database named `bankmanagement`.
4. Import the SQL dump:
   - Select the `bankmanagement` database
   - Go to **Import** → choose `phase-4/SQLDump.sql`
   - Click **Go**

This will create all tables, insert sample data, and set up all triggers and stored procedures.

### 4. Configure MongoDB

1. Ensure MongoDB is running locally on the default port (`27017`).
2. No additional setup is needed — the PHP ticket store creates collections automatically on first use.
3. Verify the MongoDB PHP extension is enabled in your `php.ini`:
   ```
   extension=mongodb
   ```

### 5. Deploy with XAMPP

Copy the web application files into your XAMPP web root:

```bash
# On Windows
xcopy bank-management-system-main\Scripts C:\xampp\htdocs\ /E /I

# On macOS/Linux
cp -r bank-management-system-main/Scripts /Applications/XAMPP/htdocs/
# or
cp -r bank-management-system-main/Scripts /opt/lampp/htdocs/
```

Your `htdocs/` should now contain `user/` and `admin/` folders.

### 6. Access the Application

| Interface | URL |
|---|---|
| User Dashboard | `http://localhost/user/` |
| Admin Dashboard | `http://localhost/admin/` |

> **Database credentials:** The application connects to MySQL as `root` with no password (XAMPP default). To change this, edit the `$mysqli = new mysqli(...)` line in `Scripts/user/db.php`.

---

## Database Schema

The `bankmanagement` MySQL database was designed from the Phase 1 ER diagram and implemented in Phase 2. Key entities include:

- **Account** — bank accounts with balance, type, and account number
- **Customer** — customers linked to one or more accounts
- **Employee** — bank staff with hire date and branch assignment
- **Branch** — physical bank branches
- *(additional entities as defined in the ER diagram)*

The full schema with all `CREATE TABLE` statements is in `phase-2/Group10_phase2.sql`.  
A complete dump including sample data is in `phase-4/SQLDump.sql`.

---

## Triggers & Stored Procedures

All triggers and stored procedures are included in the SQL dump and are demonstrated interactively via the Phase 4 web interface.

### Triggers

| # | Name | Description |
|---|---|---|
| 1 | Account Balance Guard | Prevents an account balance from being set to a negative value |
| 2 | Employee Work Date Validation | Ensures employee work start date is not in the future |
| 3 | Transaction Amount Validation | prevents inserting transactions with zero or negative amounts |

### Stored Procedures

| # | Name | Description |
|---|---|---|
| 1 | `GetCustomersInBalanceRange` | Returns all customers with account balances within a given min–max range |
| 2 | 'Employees by Date Range' | returns employees whose WorksIn.StartFrom date is valid |
| 3 | 'Transaction Count by Customer' | receives a customer ID and returns the number of transactions |

---

## Support Ticket System

The support ticket system is backed by **MongoDB** and connects users with the admin team.

**User flow:**
1. Navigate to `http://localhost/user/tickets.php`
2. Create a new ticket describing the issue
3. Track ticket status and admin responses from the ticket detail page

**Admin flow:**
1. Navigate to `http://localhost/admin/`
2. View all active tickets across all users
3. Add comments or mark tickets as resolved

Tickets are stored as MongoDB documents, allowing flexible schema for comments and metadata without altering the relational database.

---

## Course Context

This project was completed across the full semester of **CS 306 — Database Systems** (Spring 2025/2026), covering:

- Conceptual database design (ER modeling, Chen notation)
- Relational model and SQL schema design
- Advanced SQL: joins, aggregates, relational algebra
- Database programming: triggers, stored procedures
- Web–database integration with PHP, MySQL, and MongoDB
- NoSQL data modeling for document-oriented use cases

---

## License

This project is licensed under the terms of the [LICENSE](./LICENSE) file included in this repository.
