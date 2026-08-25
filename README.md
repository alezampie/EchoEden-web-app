# EchoEden - Peer-to-Peer E-Commerce Platform

Created by Alessio Zampierolo and Alice Tindiglia[cite: 2].

EchoEden is a peer-to-peer web application designed to give artists the opportunity to sell their products and fans the opportunity to buy them[cite: 2]. The site follows a rigid division of roles, similar to platforms like eBay and Vinted[cite: 2].

## Video Walkthrough

[![EchoEden Video Presentation](https://img.youtube.com/vi/KkXfZjA5Goc/maxresdefault.jpg)](https://youtu.be/KkXfZjA5Goc)  
*(Click the image to watch the step-by-step video explanation on YouTube[cite: 2])*

## User Roles and Permissions

Users are divided into three types using a total exclusive generalization hierarchy, meaning a user can only belong to one category[cite: 2]:

*   **Fan (Buyer):** After admin approval, fans get access to a shopping cart to place orders[cite: 2]. They can view products, leave reviews (max 5 total, 1 per product), and modify or delete their own comments[cite: 2]. Fans can also cancel their orders as long as the artist hasn't confirmed or rejected them yet[cite: 2].
*   **Artist (Seller):** Artists can add, edit, or delete their own merch (categories include CDs, vinyl, socks, hats, t-shirts, and hoodies) and apply discounts[cite: 2]. Order management is entirely at their discretion; they can decide whether to confirm or reject an order based on their availability, effectively acting as a manual stock check[cite: 2].
*   **Admin:** Admins are responsible for approving, rejecting, blocking, unblocking, or deleting registered users[cite: 2]. They must monitor the site and have the power to delete any inappropriate products or comments from the database[cite: 2].
*   **Guest (Unregistered):** Can browse the site, view products and comments, and use the parametric search feature[cite: 2].

## Architecture and Tech Stack

The site is designed with HTML5 and Bootstrap classes to ensure responsive design[cite: 2]. It uses a hybrid logic between a Multi-Page Application (MPA) and a Single-Page Application (SPA)[cite: 2]. PHP sessions are used to grant access only to authorized users and to dynamically load content based on the navigation state[cite: 2].

The codebase is logically divided to separate functionality[cite: 2]:
*   **`/common/`**: Centralizes application logic and database access[cite: 2]. It includes `setup.php` for database connection, `funzioni.php` for SQL queries and error handling, and polymorphic layout files like `navbar.php` and `footer.php`[cite: 2].
*   **`/frontend/` & `index.php`**: Contains the visible user interfaces, forms, and the main routing page[cite: 2].
*   **`/backend/`**: Contains execution scripts handling user management, cart operations, order processing, product management, and search logic[cite: 2].
*   **`/js/` & `/css/`**: Manages the global style and dynamic behaviors[cite: 2]. JavaScript is heavily used to handle AJAX calls, allowing real-time updates for the cart, orders, and modal comment interfaces without reloading the entire page[cite: 2].

## Key Features

*   **Dynamic Cart & Orders:** The shopping cart (max 99 items) updates in real-time via AJAX[cite: 2]. During checkout, if a fan buys items from different artists, the system automatically splits them into multiple distinct orders so each artist only manages their own products[cite: 2].
*   **Comment System:** Comments are displayed in a modal interface[cite: 2]. When a user adds or deletes a comment, a JavaScript script intercepts the event and makes an AJAX call to the backend (`aggiungi_commento-exe.php`, `elimina_commento.php`, `get_commenti.php`) to update the modal dynamically[cite: 2].

## Testing Credentials

To test the application, log in using the email address (not the username)[cite: 2]. *Note: The database constraint for an 8-character password was manually bypassed in the DB to allow these simple test passwords[cite: 2].*

*   **Fan:** `marco@gmail.com` | Password: `123` | *(Has already placed an order[cite: 2])*
*   **Artist:** `sara@gmail.com` | Password: `123` | *(Has already published products[cite: 2])*
*   **Admin:** `admin@gmail.com` | Password: `123`[cite: 2]
