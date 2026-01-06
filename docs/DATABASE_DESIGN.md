# Shelluxe Database Design Documentation

## Design Philosophy

The Shelluxe database is designed following **Third Normal Form (3NF)** principles to ensure data integrity, eliminate redundancy, and optimize for both performance and maintainability.

---

## Design Principles

### 1. Normalization

The database follows **3NF** normalization:
- **1NF**: All attributes are atomic (no repeating groups)
- **2NF**: All non-key attributes fully depend on the primary key
- **3NF**: No transitive dependencies (non-key attributes depend only on the primary key)

### 2. Referential Integrity

All relationships are enforced through foreign key constraints with appropriate actions:
- **CASCADE**: For dependent data (e.g., product images when product is deleted)
- **RESTRICT**: For critical relationships (e.g., prevent category deletion if products exist)
- **SET NULL**: For optional relationships (e.g., shipping method on orders)

### 3. Data Integrity

Multiple layers of data validation:
- **Primary Keys**: Unique identification for all entities
- **Foreign Keys**: Enforced relationships
- **Check Constraints**: Business rule validation (prices >= 0, ratings 1-5)
- **Unique Constraints**: Prevent duplicates (emails, SKUs, usernames)
- **NOT NULL**: Required fields enforcement

### 4. Performance Optimization

Strategic indexing for common query patterns:
- Foreign key columns
- Frequently searched fields (email, username, SKU)
- Status and filter fields (is_active, order_status)
- Composite indexes for multi-column queries
- Full-text indexes for search functionality

---

## Entity Relationship Analysis

### Core Entities

#### 1. User Management
- **users**: Central user account table
- **user_addresses**: Multiple addresses per user (1:N relationship)

#### 2. Product Catalog
- **categories**: Hierarchical category structure (self-referencing, 1:N)
- **products**: Product catalog (N:1 with categories)
- **product_images**: Multiple images per product (1:N)
- **product_reviews**: Reviews per product (N:1 with products, N:1 with users)

#### 3. Shopping Functionality
- **cart_items**: Shopping cart (N:1 with users, N:1 with products)
- **wishlist_items**: Wishlist (N:1 with users, N:1 with products)

#### 4. Order Management
- **orders**: Order header (N:1 with users, addresses, shipping/payment methods)
- **order_items**: Order line items (N:1 with orders, N:1 with products)
- **shipping_methods**: Shipping options (1:N with orders)
- **payment_methods**: Payment options (1:N with orders)

---

## Relationship Design Decisions

### One-to-Many Relationships

1. **User → Addresses**: Users can have multiple addresses
   - Supports both shipping and billing addresses
   - Default address flag for convenience

2. **Category → Products**: Categories contain multiple products
   - RESTRICT on delete to prevent orphaned products
   - Hierarchical categories via self-reference

3. **Product → Images**: Products can have multiple images
   - CASCADE on delete (images are product-specific)
   - Primary image flag for main display

4. **Product → Reviews**: Products can have multiple reviews
   - Unique constraint: one review per user per product
   - Approval system for moderation

5. **User → Orders**: Users can place multiple orders
   - RESTRICT on delete to preserve order history
   - Order snapshot preserves historical data

### Many-to-Many Relationships

1. **Users ↔ Products (Cart)**: Users can add multiple products to cart
   - Resolved via `cart_items` junction table
   - Unique constraint prevents duplicate entries

2. **Users ↔ Products (Wishlist)**: Users can wishlist multiple products
   - Resolved via `wishlist_items` junction table
   - Unique constraint prevents duplicates

---

## Key Design Decisions

### 1. Order Item Snapshots

**Decision**: Store product name, SKU, and price in `order_items` table

**Rationale**:
- Preserves order history even if products are modified or deleted
- Ensures accurate order records for legal/compliance
- Allows product price changes without affecting past orders

**Trade-off**: Slight data redundancy, but necessary for data integrity

### 2. Hierarchical Categories

**Decision**: Self-referencing foreign key for parent categories

**Rationale**:
- Supports unlimited category depth
- Flexible category organization
- Single table simplifies queries

**Alternative Considered**: Adjacency list with separate parent/child table (rejected for complexity)

### 3. Separate Shipping and Billing Addresses

**Decision**: Store both addresses in orders table

**Rationale**:
- Common e-commerce requirement
- Supports different billing and shipping locations
- Historical record preservation

### 4. Review Moderation

**Decision**: `is_approved` flag in reviews table

**Rationale**:
- Allows review moderation workflow
- Prevents spam and inappropriate content
- Views filter by approval status

### 5. Stock Management

**Decision**: Stock quantity in products table with low stock threshold

**Rationale**:
- Simple inventory tracking
- Automatic stock updates via trigger
- Low stock alerts for reordering

**Future Enhancement**: Separate inventory table for multi-warehouse support

---

## Data Types Selection

### String Types
- **VARCHAR**: Variable-length strings (usernames, emails, names)
- **TEXT**: Long descriptions and reviews
- **ENUM**: Fixed set of values (statuses, types)

### Numeric Types
- **INT**: Whole numbers (IDs, quantities, counts)
- **DECIMAL(10,2)**: Monetary values (prices, costs)
- **DECIMAL(8,2)**: Measurements (weight)

### Date/Time Types
- **DATE**: Birth dates, dates without time
- **TIMESTAMP**: Created/updated timestamps with automatic management

### Boolean Types
- **BOOLEAN**: True/false flags (is_active, is_featured)

---

## Indexing Strategy

### Primary Indexes
- All tables have auto-increment primary keys
- Ensures unique identification and fast lookups

### Foreign Key Indexes
- All foreign keys are indexed
- Improves JOIN performance
- Required by MySQL for foreign key constraints

### Search Indexes
- **Email, Username**: Unique indexes for authentication
- **SKU, Slugs**: Unique indexes for product lookup
- **Full-text**: Product search on name and descriptions

### Filter Indexes
- **Status Fields**: is_active, order_status, payment_status
- **Boolean Flags**: is_featured, is_approved, is_default

### Composite Indexes
- **(category_id, is_active)**: Common product listing query
- **(user_id, order_status)**: User order filtering
- **(product_id, is_approved)**: Review display queries

---

## Views Design

### Purpose
Views encapsulate common query patterns and provide:
- Simplified data access
- Consistent business logic
- Performance optimization through pre-computed aggregations

### v_product_summary
**Purpose**: Product listings with aggregated data

**Benefits**:
- Single query for product display
- Includes ratings and review counts
- Filters inactive products automatically

### v_order_summary
**Purpose**: Order overview with customer information

**Benefits**:
- Complete order context in one query
- Includes item counts
- Ready for reporting and dashboards

---

## Stored Procedures Design

### Purpose
Stored procedures provide:
- Encapsulated business logic
- Reduced network traffic
- Consistent data access patterns

### sp_get_product_details
**Purpose**: Retrieve complete product information

**Benefits**:
- Single call for all product data
- Includes images and reviews
- Consistent format across application

### sp_calculate_order_total
**Purpose**: Calculate order totals

**Benefits**:
- Centralized calculation logic
- Reusable across application
- Ensures consistency

---

## Triggers Design

### Purpose
Triggers automate business logic at the database level:
- Ensures data consistency
- Reduces application code complexity
- Guarantees rule enforcement

### trg_update_stock_after_order
**Purpose**: Automatic inventory management

**Benefits**:
- Real-time stock updates
- Prevents overselling
- No manual intervention required

### trg_generate_order_number
**Purpose**: Unique order number generation

**Benefits**:
- Guaranteed unique order numbers
- Human-readable format
- Automatic generation

---

## Security Considerations

### Password Storage
- Passwords stored as hashes (bcrypt recommended)
- Never store plain text passwords
- `password_hash` column supports any hashing algorithm

### SQL Injection Prevention
- Use parameterized queries in application code
- Stored procedures with parameters
- Input validation at application level

### Data Access Control
- Database user permissions should be restricted
- Read-only access for reporting
- Write access only for application users

---

## Scalability Considerations

### Current Design
- Optimized for small to medium e-commerce sites
- Supports thousands of products and orders
- Efficient indexing for common queries

### Future Enhancements

1. **Partitioning**
   - Partition orders table by date
   - Improves query performance on large datasets

2. **Read Replicas**
   - Separate read/write operations
   - Improves performance for reporting

3. **Caching Layer**
   - Cache frequently accessed data
   - Reduce database load

4. **Archive Strategy**
   - Move old orders to archive table
   - Maintain active table performance

---

## Backup and Recovery

### Recommended Backup Strategy

1. **Daily Full Backups**
   - Complete database dump
   - Retain 30 days

2. **Transaction Log Backups**
   - Incremental backups
   - Point-in-time recovery

3. **Test Restores**
   - Monthly restore tests
   - Verify backup integrity

### Recovery Procedures

1. **Point-in-Time Recovery**
   - Use binary logs
   - Restore to specific timestamp

2. **Table-Level Recovery**
   - Restore individual tables
   - Minimal downtime

---

## Performance Tuning

### Query Optimization

1. **Use Indexes**
   - Ensure WHERE clauses use indexed columns
   - Avoid functions on indexed columns

2. **Limit Results**
   - Use LIMIT for pagination
   - Avoid SELECT *

3. **Join Optimization**
   - Use appropriate join types
   - Join on indexed columns

### Monitoring

1. **Slow Query Log**
   - Identify slow queries
   - Optimize based on findings

2. **Explain Plans**
   - Analyze query execution
   - Identify optimization opportunities

3. **Index Usage**
   - Monitor unused indexes
   - Remove unnecessary indexes

---

## Testing Strategy

### Unit Testing
- Test individual stored procedures
- Verify trigger behavior
- Validate constraints

### Integration Testing
- Test complete workflows
- Verify data integrity
- Check performance

### Data Validation
- Verify sample data integrity
- Test edge cases
- Validate business rules

---

## Documentation Standards

### Code Comments
- Schema file includes table descriptions
- Complex queries documented
- Business logic explained

### External Documentation
- README files for overview
- Design documentation for decisions
- ER diagrams for relationships

---

## Conclusion

The Shelluxe database design demonstrates:
- ✅ Strong understanding of relational database principles
- ✅ Practical application of normalization
- ✅ Performance optimization techniques
- ✅ Real-world e-commerce requirements
- ✅ SQL intermediate-level proficiency

This design provides a solid foundation for a scalable e-commerce platform while maintaining data integrity and performance.

---

**Last Updated**: January 2024

