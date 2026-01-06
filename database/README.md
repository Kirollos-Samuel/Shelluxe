# Shelluxe Database Documentation

## Database Overview

The Shelluxe database is designed for a modern e-commerce platform specializing in bracelet sales. The schema follows normalization principles and implements best practices for data integrity, performance, and scalability.

## Database Information

- **Database Name**: `shelluxe_db`
- **Character Set**: `utf8mb4`
- **Collation**: `utf8mb4_unicode_ci`
- **Storage Engine**: `InnoDB`

---

## Table Reference

### Core Tables

#### 1. `users`
Stores customer account information and authentication data.

**Columns:**
- `user_id` (PK, INT, AUTO_INCREMENT)
- `username` (VARCHAR(50), UNIQUE, NOT NULL)
- `email` (VARCHAR(100), UNIQUE, NOT NULL)
- `password_hash` (VARCHAR(255), NOT NULL)
- `first_name`, `last_name` (VARCHAR(50), NOT NULL)
- `phone` (VARCHAR(20))
- `date_of_birth` (DATE)
- `gender` (ENUM)
- `profile_image` (VARCHAR(255))
- `email_verified` (BOOLEAN, DEFAULT FALSE)
- `account_status` (ENUM: Active, Inactive, Suspended, Banned)
- `created_at`, `updated_at`, `last_login` (TIMESTAMP)

**Indexes:**
- Primary: `user_id`
- Unique: `username`, `email`
- Index: `email`, `username`, `account_status`

---

#### 2. `categories`
Hierarchical product categorization system.

**Columns:**
- `category_id` (PK, INT, AUTO_INCREMENT)
- `category_name` (VARCHAR(100), NOT NULL)
- `category_slug` (VARCHAR(100), UNIQUE, NOT NULL)
- `parent_category_id` (FK → categories.category_id, NULL)
- `description` (TEXT)
- `category_image` (VARCHAR(255))
- `display_order` (INT, DEFAULT 0)
- `is_active` (BOOLEAN, DEFAULT TRUE)
- `created_at`, `updated_at` (TIMESTAMP)

**Relationships:**
- Self-referencing: `parent_category_id` → `categories.category_id`

**Indexes:**
- Primary: `category_id`
- Unique: `category_slug`
- Index: `parent_category_id`, `category_slug`, `is_active`

---

#### 3. `products`
Main product catalog with comprehensive product information.

**Columns:**
- `product_id` (PK, INT, AUTO_INCREMENT)
- `category_id` (FK → categories.category_id, NOT NULL)
- `product_name` (VARCHAR(200), NOT NULL)
- `product_slug` (VARCHAR(200), UNIQUE, NOT NULL)
- `sku` (VARCHAR(50), UNIQUE, NOT NULL)
- `short_description`, `full_description` (TEXT)
- `price` (DECIMAL(10,2), NOT NULL, CHECK >= 0)
- `compare_at_price` (DECIMAL(10,2), CHECK >= 0)
- `cost_price` (DECIMAL(10,2), CHECK >= 0)
- `stock_quantity` (INT, DEFAULT 0, CHECK >= 0)
- `low_stock_threshold` (INT, DEFAULT 10)
- `weight` (DECIMAL(8,2))
- `dimensions`, `material`, `color`, `size` (VARCHAR)
- `is_active`, `is_featured` (BOOLEAN)
- `meta_title`, `meta_description` (SEO fields)
- `view_count` (INT, DEFAULT 0)
- `created_at`, `updated_at` (TIMESTAMP)

**Relationships:**
- `category_id` → `categories.category_id` (RESTRICT on delete)

**Indexes:**
- Primary: `product_id`
- Unique: `product_slug`, `sku`
- Index: `category_id`, `is_active`, `is_featured`, `price`
- Full-text: `product_name`, `short_description`, `full_description`
- Composite: `(category_id, is_active)`

---

#### 4. `product_images`
Multiple images per product with ordering support.

**Columns:**
- `image_id` (PK, INT, AUTO_INCREMENT)
- `product_id` (FK → products.product_id, NOT NULL)
- `image_url` (VARCHAR(255), NOT NULL)
- `image_alt` (VARCHAR(255))
- `display_order` (INT, DEFAULT 0)
- `is_primary` (BOOLEAN, DEFAULT FALSE)
- `created_at` (TIMESTAMP)

**Relationships:**
- `product_id` → `products.product_id` (CASCADE on delete)

**Indexes:**
- Primary: `image_id`
- Index: `product_id`, `is_primary`

---

#### 5. `product_reviews`
Customer reviews and ratings system.

**Columns:**
- `review_id` (PK, INT, AUTO_INCREMENT)
- `product_id` (FK → products.product_id, NOT NULL)
- `user_id` (FK → users.user_id, NOT NULL)
- `rating` (INT, NOT NULL, CHECK 1-5)
- `review_title` (VARCHAR(200))
- `review_text` (TEXT)
- `is_verified_purchase` (BOOLEAN, DEFAULT FALSE)
- `is_approved` (BOOLEAN, DEFAULT FALSE)
- `helpful_count` (INT, DEFAULT 0)
- `created_at`, `updated_at` (TIMESTAMP)

**Relationships:**
- `product_id` → `products.product_id` (CASCADE)
- `user_id` → `users.user_id` (CASCADE)

**Constraints:**
- Unique: `(user_id, product_id)` - One review per user per product

**Indexes:**
- Primary: `review_id`
- Unique: `(user_id, product_id)`
- Index: `product_id`, `user_id`, `rating`, `is_approved`
- Composite: `(product_id, is_approved)`

---

#### 6. `user_addresses`
Customer shipping and billing addresses.

**Columns:**
- `address_id` (PK, INT, AUTO_INCREMENT)
- `user_id` (FK → users.user_id, NOT NULL)
- `address_type` (ENUM: Billing, Shipping, Both)
- `first_name`, `last_name` (VARCHAR(50), NOT NULL)
- `company` (VARCHAR(100))
- `address_line1` (VARCHAR(255), NOT NULL)
- `address_line2` (VARCHAR(255))
- `city` (VARCHAR(100), NOT NULL)
- `state_province` (VARCHAR(100))
- `postal_code` (VARCHAR(20), NOT NULL)
- `country` (VARCHAR(100), NOT NULL)
- `phone` (VARCHAR(20))
- `is_default` (BOOLEAN, DEFAULT FALSE)
- `created_at`, `updated_at` (TIMESTAMP)

**Relationships:**
- `user_id` → `users.user_id` (CASCADE)

**Indexes:**
- Primary: `address_id`
- Index: `user_id`, `is_default`

---

#### 7. `cart_items`
Shopping cart functionality.

**Columns:**
- `cart_item_id` (PK, INT, AUTO_INCREMENT)
- `user_id` (FK → users.user_id, NOT NULL)
- `product_id` (FK → products.product_id, NOT NULL)
- `quantity` (INT, NOT NULL, CHECK > 0)
- `added_at`, `updated_at` (TIMESTAMP)

**Relationships:**
- `user_id` → `users.user_id` (CASCADE)
- `product_id` → `products.product_id` (CASCADE)

**Constraints:**
- Unique: `(user_id, product_id)` - One cart entry per user per product

**Indexes:**
- Primary: `cart_item_id`
- Unique: `(user_id, product_id)`
- Index: `user_id`, `product_id`

---

#### 8. `wishlist_items`
Customer wishlist functionality.

**Columns:**
- `wishlist_item_id` (PK, INT, AUTO_INCREMENT)
- `user_id` (FK → users.user_id, NOT NULL)
- `product_id` (FK → products.product_id, NOT NULL)
- `added_at` (TIMESTAMP)

**Relationships:**
- `user_id` → `users.user_id` (CASCADE)
- `product_id` → `products.product_id` (CASCADE)

**Constraints:**
- Unique: `(user_id, product_id)`

**Indexes:**
- Primary: `wishlist_item_id`
- Unique: `(user_id, product_id)`
- Index: `user_id`, `product_id`

---

#### 9. `shipping_methods`
Available shipping options.

**Columns:**
- `shipping_method_id` (PK, INT, AUTO_INCREMENT)
- `method_name` (VARCHAR(100), NOT NULL)
- `description` (TEXT)
- `base_cost` (DECIMAL(10,2), DEFAULT 0.00)
- `cost_per_kg` (DECIMAL(10,2), DEFAULT 0.00)
- `estimated_days_min`, `estimated_days_max` (INT)
- `is_active` (BOOLEAN, DEFAULT TRUE)
- `created_at`, `updated_at` (TIMESTAMP)

**Indexes:**
- Primary: `shipping_method_id`
- Index: `is_active`

---

#### 10. `payment_methods`
Available payment options.

**Columns:**
- `payment_method_id` (PK, INT, AUTO_INCREMENT)
- `method_name` (VARCHAR(50), NOT NULL)
- `method_type` (ENUM: Credit Card, Debit Card, PayPal, Bank Transfer, Cash on Delivery, Other)
- `is_active` (BOOLEAN, DEFAULT TRUE)
- `processing_fee_percentage` (DECIMAL(5,2), DEFAULT 0.00)
- `created_at`, `updated_at` (TIMESTAMP)

**Indexes:**
- Primary: `payment_method_id`
- Index: `is_active`

---

#### 11. `orders`
Customer order management.

**Columns:**
- `order_id` (PK, INT, AUTO_INCREMENT)
- `user_id` (FK → users.user_id, NOT NULL)
- `order_number` (VARCHAR(50), UNIQUE, NOT NULL)
- `order_status` (ENUM: Pending, Processing, Shipped, Delivered, Cancelled, Refunded)
- `shipping_address_id` (FK → user_addresses.address_id, NOT NULL)
- `billing_address_id` (FK → user_addresses.address_id, NOT NULL)
- `shipping_method_id` (FK → shipping_methods.shipping_method_id)
- `payment_method_id` (FK → payment_methods.payment_method_id)
- `subtotal`, `shipping_cost`, `tax_amount`, `discount_amount`, `total_amount` (DECIMAL(10,2))
- `payment_status` (ENUM: Pending, Paid, Failed, Refunded)
- `payment_transaction_id` (VARCHAR(255))
- `notes` (TEXT)
- `shipped_at`, `delivered_at` (TIMESTAMP)
- `created_at`, `updated_at` (TIMESTAMP)

**Relationships:**
- `user_id` → `users.user_id` (RESTRICT)
- `shipping_address_id` → `user_addresses.address_id` (RESTRICT)
- `billing_address_id` → `user_addresses.address_id` (RESTRICT)
- `shipping_method_id` → `shipping_methods.shipping_method_id` (SET NULL)
- `payment_method_id` → `payment_methods.payment_method_id` (SET NULL)

**Indexes:**
- Primary: `order_id`
- Unique: `order_number`
- Index: `user_id`, `order_status`, `payment_status`, `created_at`
- Composite: `(user_id, order_status)`

---

#### 12. `order_items`
Individual items within each order.

**Columns:**
- `order_item_id` (PK, INT, AUTO_INCREMENT)
- `order_id` (FK → orders.order_id, NOT NULL)
- `product_id` (FK → products.product_id, NOT NULL)
- `product_name` (VARCHAR(200), NOT NULL) - Snapshot at time of order
- `product_sku` (VARCHAR(50), NOT NULL) - Snapshot at time of order
- `quantity` (INT, NOT NULL, CHECK > 0)
- `unit_price` (DECIMAL(10,2), NOT NULL, CHECK >= 0) - Snapshot at time of order
- `total_price` (DECIMAL(10,2), NOT NULL, CHECK >= 0)
- `created_at` (TIMESTAMP)

**Relationships:**
- `order_id` → `orders.order_id` (CASCADE)
- `product_id` → `products.product_id` (RESTRICT)

**Indexes:**
- Primary: `order_item_id`
- Index: `order_id`, `product_id`

**Note:** Product name, SKU, and price are stored as snapshots to preserve order history even if products are modified or deleted.

---

## Views

### `v_product_summary`
Aggregated product information including ratings and review counts.

**Columns:**
- `product_id`, `product_name`, `sku`, `price`, `stock_quantity`
- `category_name`
- `average_rating` (calculated)
- `review_count` (calculated)
- `image_count` (calculated)

**Usage:**
```sql
SELECT * FROM v_product_summary WHERE average_rating >= 4.0;
```

---

### `v_order_summary`
Order overview with customer information and item counts.

**Columns:**
- `order_id`, `order_number`, `order_status`, `total_amount`, `payment_status`
- `username`, `email`, `customer_name`
- `created_at`
- `item_count` (calculated)

**Usage:**
```sql
SELECT * FROM v_order_summary WHERE order_status = 'Pending';
```

---

## Stored Procedures

### `sp_get_product_details(product_id)`
Retrieves complete product information including images and aggregated review data.

**Parameters:**
- `p_product_id` (IN, INT) - Product ID to retrieve

**Returns:**
- Product details with comma-separated image URLs
- Average rating and total review count

**Usage:**
```sql
CALL sp_get_product_details(1);
```

---

### `sp_calculate_order_total(order_id, @total)`
Calculates the total amount for an order based on order items.

**Parameters:**
- `p_order_id` (IN, INT) - Order ID
- `p_total` (OUT, DECIMAL(10,2)) - Calculated total

**Usage:**
```sql
CALL sp_calculate_order_total(1, @total);
SELECT @total;
```

---

## Triggers

### `trg_update_stock_after_order`
Automatically decreases product stock quantity when an order item is inserted.

**Event:** AFTER INSERT on `order_items`
**Action:** Updates `products.stock_quantity`

---

### `trg_generate_order_number`
Automatically generates a unique order number when an order is created.

**Event:** BEFORE INSERT on `orders`
**Format:** `ORD-YYYYMMDD-XXXXXX`
**Action:** Sets `order_number` if not provided

---

## Data Integrity Rules

### Foreign Key Actions

- **CASCADE**: Deleting a parent deletes children (e.g., products → images, users → addresses)
- **RESTRICT**: Prevents deletion if children exist (e.g., categories → products, users → orders)
- **SET NULL**: Sets foreign key to NULL on parent deletion (e.g., orders → shipping_methods)

### Check Constraints

- Prices must be >= 0
- Stock quantities must be >= 0
- Order item quantities must be > 0
- Ratings must be between 1 and 5

### Unique Constraints

- Usernames and emails are unique
- Product SKUs and slugs are unique
- One review per user per product
- One cart item per user per product
- One wishlist item per user per product

---

## Performance Considerations

### Index Strategy

1. **Primary Keys**: All tables have auto-increment primary keys
2. **Foreign Keys**: Indexed for join performance
3. **Search Fields**: Email, username, SKU, slugs indexed
4. **Status Fields**: Active flags and status enums indexed
5. **Composite Indexes**: Common query patterns optimized
6. **Full-text Index**: Product search functionality

### Query Optimization Tips

- Use views for common reporting queries
- Leverage indexes on WHERE and JOIN conditions
- Use stored procedures for complex operations
- Consider partitioning for large tables (future enhancement)

---

## Maintenance

### Regular Tasks

1. **Backup**: Daily database backups recommended
2. **Index Maintenance**: Monitor and optimize slow queries
3. **Data Archiving**: Consider archiving old orders
4. **Statistics Update**: Run `ANALYZE TABLE` periodically

### Monitoring Queries

```sql
-- Check table sizes
SELECT 
    table_name,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
FROM information_schema.TABLES
WHERE table_schema = 'shelluxe_db'
ORDER BY size_mb DESC;

-- Check index usage
SHOW INDEX FROM products;

-- Check slow queries
SHOW PROCESSLIST;
```

---

## Version History

- **v1.0** (2024-01) - Initial schema design and implementation

---

## Contact

For database-related questions or issues, please refer to the main project documentation.

