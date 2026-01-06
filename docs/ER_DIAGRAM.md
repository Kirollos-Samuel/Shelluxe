# Shelluxe Entity Relationship Diagram Documentation

## Overview

This document describes the Entity Relationship (ER) model for the Shelluxe e-commerce database. The ER diagram illustrates the relationships between entities and their cardinalities.

---

## Entity Descriptions

### User Management Entities

#### Users
- **Primary Key**: `user_id`
- **Description**: Customer accounts and authentication
- **Key Attributes**: username, email, password_hash, account_status

#### User Addresses
- **Primary Key**: `address_id`
- **Foreign Key**: `user_id` → Users
- **Description**: Shipping and billing addresses
- **Cardinality**: One User → Many Addresses (1:N)
- **Key Attributes**: address_type, is_default

---

### Product Catalog Entities

#### Categories
- **Primary Key**: `category_id`
- **Self-Reference**: `parent_category_id` → Categories
- **Description**: Hierarchical product categories
- **Cardinality**: One Category → Many Subcategories (1:N, self-referencing)
- **Key Attributes**: category_name, category_slug, parent_category_id

#### Products
- **Primary Key**: `product_id`
- **Foreign Key**: `category_id` → Categories
- **Description**: Product catalog items
- **Cardinality**: One Category → Many Products (1:N)
- **Key Attributes**: product_name, sku, price, stock_quantity

#### Product Images
- **Primary Key**: `image_id`
- **Foreign Key**: `product_id` → Products
- **Description**: Product images
- **Cardinality**: One Product → Many Images (1:N)
- **Key Attributes**: image_url, is_primary, display_order

#### Product Reviews
- **Primary Key**: `review_id`
- **Foreign Keys**: 
  - `product_id` → Products
  - `user_id` → Users
- **Description**: Customer reviews and ratings
- **Cardinality**: 
  - One Product → Many Reviews (1:N)
  - One User → Many Reviews (1:N)
  - One User → One Review per Product (unique constraint)
- **Key Attributes**: rating, review_text, is_approved

---

### Shopping Functionality Entities

#### Cart Items
- **Primary Key**: `cart_item_id`
- **Foreign Keys**:
  - `user_id` → Users
  - `product_id` → Products
- **Description**: Shopping cart items
- **Cardinality**: 
  - One User → Many Cart Items (1:N)
  - One Product → Many Cart Items (1:N)
  - Many Users ↔ Many Products (M:N via junction table)
- **Key Attributes**: quantity

#### Wishlist Items
- **Primary Key**: `wishlist_item_id`
- **Foreign Keys**:
  - `user_id` → Users
  - `product_id` → Products
- **Description**: Customer wishlist
- **Cardinality**: 
  - One User → Many Wishlist Items (1:N)
  - One Product → Many Wishlist Items (1:N)
  - Many Users ↔ Many Products (M:N via junction table)
- **Key Attributes**: added_at

---

### Order Management Entities

#### Orders
- **Primary Key**: `order_id`
- **Foreign Keys**:
  - `user_id` → Users
  - `shipping_address_id` → User Addresses
  - `billing_address_id` → User Addresses
  - `shipping_method_id` → Shipping Methods
  - `payment_method_id` → Payment Methods
- **Description**: Customer orders
- **Cardinality**: 
  - One User → Many Orders (1:N)
  - One Address → Many Orders (1:N, for shipping)
  - One Address → Many Orders (1:N, for billing)
  - One Shipping Method → Many Orders (1:N)
  - One Payment Method → Many Orders (1:N)
- **Key Attributes**: order_number, order_status, total_amount, payment_status

#### Order Items
- **Primary Key**: `order_item_id`
- **Foreign Keys**:
  - `order_id` → Orders
  - `product_id` → Products
- **Description**: Individual items in orders
- **Cardinality**: 
  - One Order → Many Order Items (1:N)
  - One Product → Many Order Items (1:N)
- **Key Attributes**: quantity, unit_price, total_price
- **Note**: Stores product snapshot (name, SKU, price) for historical accuracy

#### Shipping Methods
- **Primary Key**: `shipping_method_id`
- **Description**: Available shipping options
- **Cardinality**: One Shipping Method → Many Orders (1:N)
- **Key Attributes**: method_name, base_cost, estimated_days

#### Payment Methods
- **Primary Key**: `payment_method_id`
- **Description**: Available payment options
- **Cardinality**: One Payment Method → Many Orders (1:N)
- **Key Attributes**: method_name, method_type, processing_fee_percentage

---

## Relationship Summary

### One-to-Many (1:N) Relationships

1. **Users → User Addresses**
   - One user can have multiple addresses
   - Foreign key: `user_addresses.user_id`

2. **Categories → Categories** (Self-referencing)
   - One category can have multiple subcategories
   - Foreign key: `categories.parent_category_id`

3. **Categories → Products**
   - One category contains multiple products
   - Foreign key: `products.category_id`

4. **Products → Product Images**
   - One product can have multiple images
   - Foreign key: `product_images.product_id`

5. **Products → Product Reviews**
   - One product can have multiple reviews
   - Foreign key: `product_reviews.product_id`

6. **Users → Product Reviews**
   - One user can write multiple reviews
   - Foreign key: `product_reviews.user_id`

7. **Users → Cart Items**
   - One user can have multiple cart items
   - Foreign key: `cart_items.user_id`

8. **Products → Cart Items**
   - One product can be in multiple carts
   - Foreign key: `cart_items.product_id`

9. **Users → Wishlist Items**
   - One user can have multiple wishlist items
   - Foreign key: `wishlist_items.user_id`

10. **Products → Wishlist Items**
    - One product can be in multiple wishlists
    - Foreign key: `wishlist_items.product_id`

11. **Users → Orders**
    - One user can place multiple orders
    - Foreign key: `orders.user_id`

12. **User Addresses → Orders** (Shipping)
    - One address can be used for multiple orders
    - Foreign key: `orders.shipping_address_id`

13. **User Addresses → Orders** (Billing)
    - One address can be used for multiple orders
    - Foreign key: `orders.billing_address_id`

14. **Shipping Methods → Orders**
    - One shipping method can be used for multiple orders
    - Foreign key: `orders.shipping_method_id`

15. **Payment Methods → Orders**
    - One payment method can be used for multiple orders
    - Foreign key: `orders.payment_method_id`

16. **Orders → Order Items**
    - One order contains multiple items
    - Foreign key: `order_items.order_id`

17. **Products → Order Items**
    - One product can appear in multiple orders
    - Foreign key: `order_items.product_id`

### Many-to-Many (M:N) Relationships

1. **Users ↔ Products** (via Cart Items)
   - Many users can add many products to cart
   - Junction table: `cart_items`
   - Unique constraint: one entry per user-product combination

2. **Users ↔ Products** (via Wishlist Items)
   - Many users can wishlist many products
   - Junction table: `wishlist_items`
   - Unique constraint: one entry per user-product combination

3. **Users ↔ Products** (via Product Reviews)
   - Many users can review many products
   - Junction table: `product_reviews`
   - Unique constraint: one review per user per product

---

## Cardinality Notation

### Relationship Types

- **1:1** (One-to-One): Not used in this schema
- **1:N** (One-to-Many): Most common relationship type
- **M:N** (Many-to-Many): Resolved via junction tables

### Foreign Key Actions

- **CASCADE**: Child records deleted when parent deleted
- **RESTRICT**: Prevents parent deletion if children exist
- **SET NULL**: Sets foreign key to NULL when parent deleted

---

## Entity Relationship Diagram (Text Representation)

```
┌─────────────┐
│   Users     │
│─────────────│
│ user_id (PK)│
│ username    │
│ email       │
└──────┬──────┘
       │
       │ 1:N
       │
       ├─────────────────┐
       │                 │
       │                 │
┌──────▼──────┐   ┌──────▼──────────┐
│User Addresses│   │ Product Reviews│
│─────────────│   │────────────────│
│address_id(PK)│   │review_id (PK)  │
│user_id (FK) │   │user_id (FK)    │
│             │   │product_id (FK) │
└──────┬──────┘   └──────┬──────────┘
       │                 │
       │ 1:N             │ 1:N
       │                 │
┌──────▼──────┐   ┌──────▼──────────┐
│   Orders    │   │   Products      │
│─────────────│   │────────────────│
│order_id (PK)│   │product_id (PK) │
│user_id (FK) │   │category_id(FK) │
│shipping_addr│   └──────┬──────────┘
│billing_addr │          │
└──────┬──────┘          │ 1:N
       │                 │
       │ 1:N             │
       │                 │
┌──────▼──────┐   ┌──────▼──────────┐
│ Order Items │   │Product Images   │
│─────────────│   │────────────────│
│order_item_id│   │image_id (PK)   │
│order_id (FK)│   │product_id (FK) │
│product_id(FK)│  └─────────────────┘
└─────────────┘

┌─────────────┐
│ Categories  │
│─────────────│
│category_id  │
│parent_cat_id│◄───┐
└──────┬──────┘    │
       │          │ (Self-reference)
       │ 1:N      │
       │          │
┌──────▼──────────┘
│   Products       │
│─────────────────│
│product_id (PK)  │
│category_id (FK) │
└─────────────────┘
```

---

## Relationship Details

### Strong Relationships (Required)

- **Products → Categories**: Product must belong to a category (NOT NULL)
- **Order Items → Orders**: Order item must belong to an order (NOT NULL)
- **Order Items → Products**: Order item must reference a product (NOT NULL)
- **Orders → Users**: Order must belong to a user (NOT NULL)
- **Orders → Addresses**: Order must have shipping and billing addresses (NOT NULL)

### Weak Relationships (Optional)

- **Orders → Shipping Methods**: Shipping method can be NULL (historical data)
- **Orders → Payment Methods**: Payment method can be NULL (historical data)
- **Products → Reviews**: Product can exist without reviews
- **Users → Reviews**: User can exist without reviews

---

## Data Flow

### Order Creation Flow

1. **User** selects products → **Cart Items**
2. **User** proceeds to checkout → **Order** created
3. **Order** references **User Addresses** (shipping & billing)
4. **Order** references **Shipping Method** and **Payment Method**
5. **Order Items** created from cart
6. **Product** stock updated via trigger
7. **Order** status tracked through lifecycle

### Product Display Flow

1. **Products** filtered by **Category**
2. **Product Images** displayed for each product
3. **Product Reviews** aggregated for ratings
4. **Stock Quantity** checked for availability

---

## Constraints Summary

### Unique Constraints

- `users.username` - Unique usernames
- `users.email` - Unique email addresses
- `products.sku` - Unique product SKUs
- `products.product_slug` - Unique product slugs
- `categories.category_slug` - Unique category slugs
- `orders.order_number` - Unique order numbers
- `(user_id, product_id)` in `product_reviews` - One review per user per product
- `(user_id, product_id)` in `cart_items` - One cart entry per user per product
- `(user_id, product_id)` in `wishlist_items` - One wishlist entry per user per product

### Check Constraints

- Prices must be >= 0
- Stock quantities must be >= 0
- Order item quantities must be > 0
- Ratings must be between 1 and 5

---

## Summary

The Shelluxe database ER model demonstrates:

- ✅ **11 core entities** with clear responsibilities
- ✅ **17 relationships** properly defined with foreign keys
- ✅ **Proper normalization** following 3NF principles
- ✅ **Data integrity** through constraints and relationships
- ✅ **Scalability** through efficient indexing and design
- ✅ **Real-world requirements** for e-commerce functionality

This ER model provides a solid foundation for implementing a robust e-commerce database system.

---

**Last Updated**: January 2024

