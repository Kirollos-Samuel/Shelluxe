# Shelluxe - E-commerce Database Design Project

## 🎯 Project Overview

**Shelluxe** is a comprehensive database design and implementation project for a modern bracelet e-commerce platform. This project demonstrates advanced SQL skills, database normalization, and best practices in relational database design.

### 🏆 Skills Demonstrated

- **SQL Intermediate Level** - Certified on [HackerRank](https://www.hackerrank.com)
- Database Schema Design & Normalization
- Complex Relationships & Foreign Keys
- Stored Procedures & Triggers
- Views & Indexes Optimization
- Data Integrity & Constraints
- Transaction Management

---

## 📊 Database Architecture

### Entity Relationship Diagram

The database follows a normalized design with the following core entities:

- **Users** - Customer account management
- **Products** - Product catalog with categories
- **Orders** - Order processing and tracking
- **Cart & Wishlist** - Shopping functionality
- **Reviews & Ratings** - Customer feedback system
- **Addresses** - Shipping and billing management
- **Payment & Shipping** - Transaction and delivery methods

### Database Schema Highlights

- **11 Core Tables** with proper relationships
- **Foreign Key Constraints** ensuring referential integrity
- **Indexes** optimized for query performance
- **Views** for common reporting queries
- **Stored Procedures** for complex operations
- **Triggers** for automated business logic
- **Full-text Search** capabilities on product descriptions

---

## 🗂️ Project Structure

```
shelluxe/
├── database/
│   ├── schema.sql          # Complete database schema
│   ├── seed.sql            # Sample data for testing
│   └── README.md           # Database documentation
├── docs/
│   ├── DATABASE_DESIGN.md  # Detailed design documentation
│   └── ER_DIAGRAM.md       # Entity Relationship documentation
├── config/
│   └── database.php        # Database configuration
└── README.md               # This file
```

---

## 🚀 Quick Start

### Prerequisites

- MySQL 5.7+ or MariaDB 10.3+
- PHP 8.0+ (for configuration files)
- MySQL command-line client or phpMyAdmin

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Kirollos-Samuel/Shelluxe.git
   cd Shelluxe
   ```

2. **Create the database**
   ```sql
   CREATE DATABASE shelluxe_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. **Import the schema**
   ```bash
   mysql -u root -p shelluxe_db < database/schema.sql
   ```

4. **Load sample data** (optional)
   ```bash
   mysql -u root -p shelluxe_db < database/seed.sql
   ```

5. **Configure database connection**
   - Edit `config/database.php` with your credentials
   - Update host, username, password, and database name

---

## 📋 Database Schema Details

### Core Tables

#### Users Table
- User authentication and profile management
- Email verification and account status tracking
- Indexes on email, username, and account status

#### Products Table
- Complete product catalog with pricing
- Stock management with low stock alerts
- Full-text search on name and descriptions
- SEO meta fields

#### Categories Table
- Hierarchical category structure (parent-child relationships)
- SEO-friendly slugs
- Display ordering support

#### Orders & Order Items
- Complete order lifecycle management
- Order status tracking (Pending → Processing → Shipped → Delivered)
- Payment status management
- Automatic order number generation via trigger

#### Product Reviews
- Customer ratings (1-5 stars)
- Review moderation (approval system)
- Verified purchase tracking
- Helpful votes system

### Advanced Features

#### Views
- `v_product_summary` - Product listings with aggregated data
- `v_order_summary` - Order overview with customer information

#### Stored Procedures
- `sp_get_product_details` - Retrieve complete product information
- `sp_calculate_order_total` - Calculate order totals

#### Triggers
- `trg_update_stock_after_order` - Automatic inventory management
- `trg_generate_order_number` - Unique order number generation

---

## 🔍 Key Database Features

### Data Integrity
- ✅ Primary keys on all tables
- ✅ Foreign key constraints with appropriate actions
- ✅ Check constraints for data validation
- ✅ Unique constraints where needed
- ✅ NOT NULL constraints for required fields

### Performance Optimization
- ✅ Strategic indexes on frequently queried columns
- ✅ Composite indexes for common query patterns
- ✅ Full-text indexes for search functionality
- ✅ Proper data types to minimize storage

### Business Logic
- ✅ Automatic stock updates on order placement
- ✅ Order number generation
- ✅ Calculated fields via views
- ✅ Status tracking for orders and payments

---

## 📈 SQL Skills Demonstrated

### Intermediate SQL Concepts

1. **Complex Joins**
   - INNER, LEFT, RIGHT joins
   - Self-joins for hierarchical categories
   - Multiple table joins in views

2. **Aggregate Functions**
   - COUNT, AVG, SUM with GROUP BY
   - HAVING clauses
   - Subqueries and correlated subqueries

3. **Advanced Queries**
   - Window functions (implicit in design)
   - CASE statements
   - UNION operations
   - EXISTS and IN clauses

4. **Database Objects**
   - Stored procedures with parameters
   - Triggers (BEFORE/AFTER)
   - Views with joins and aggregations
   - Indexes (single, composite, full-text)

5. **Data Manipulation**
   - INSERT with subqueries
   - UPDATE with JOINs
   - DELETE with CASCADE
   - Transaction management concepts

---

## 🧪 Testing the Database

### Sample Queries

```sql
-- Get all products with average ratings
SELECT * FROM v_product_summary;

-- Get product details with images and reviews
CALL sp_get_product_details(1);

-- Find products with low stock
SELECT product_name, stock_quantity 
FROM products 
WHERE stock_quantity <= low_stock_threshold;

-- Get customer order history
SELECT * FROM v_order_summary WHERE username = 'john_doe';

-- Search products by keyword
SELECT * FROM products 
WHERE MATCH(product_name, short_description) 
AGAINST('leather' IN NATURAL LANGUAGE MODE);
```

---

## 📚 Documentation

- **[Database Design Documentation](docs/DATABASE_DESIGN.md)** - Detailed design decisions and rationale
- **[ER Diagram Documentation](docs/ER_DIAGRAM.md)** - Entity relationships and cardinalities
- **[Database Schema Reference](database/README.md)** - Complete table reference

---

## 🛠️ Technologies Used

- **Database**: MySQL 5.7+ / MariaDB 10.3+
- **SQL Standard**: ANSI SQL with MySQL extensions
- **Character Set**: UTF8MB4 (full Unicode support)
- **Storage Engine**: InnoDB (ACID compliance, foreign keys)

---

## 📝 Database Statistics

- **Total Tables**: 11
- **Total Views**: 2
- **Total Stored Procedures**: 2
- **Total Triggers**: 2
- **Total Indexes**: 25+
- **Relationships**: 15+ foreign key constraints

---

## 🎓 Learning Outcomes

This project demonstrates proficiency in:

- ✅ Database normalization (3NF)
- ✅ Entity-Relationship modeling
- ✅ SQL DDL (Data Definition Language)
- ✅ SQL DML (Data Manipulation Language)
- ✅ Advanced SQL features (procedures, triggers, views)
- ✅ Database optimization techniques
- ✅ Data integrity and constraints
- ✅ Real-world e-commerce database design

---

## 📄 License

This project is part of a database design portfolio demonstrating SQL intermediate-level skills.

---

## 👤 Author

**Database Design Project**
- SQL Intermediate Level Certified - [HackerRank](https://www.hackerrank.com)
- Skills: Database Design, SQL, MySQL, Data Modeling

---

## 🙏 Acknowledgments

This database design follows industry best practices for e-commerce platforms and demonstrates real-world application of SQL intermediate-level concepts.

---

**Last Updated**: January 2024

