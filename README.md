# Laravel Booking Products Excel Application

This Laravel application allows users to manage products, add them to bookings, and export booking details to an Excel sheet.

## Features

1. **User Authentication:**
   - Users can register and log in to the application.

2. **Product Management:**
   - Authenticated users can add, edit, and delete products.
   - Products include details such as name, price, and discount.

3. **Booking Process:**
   - Users can select multiple products and add them to their cart.
   - The booking page displays:
     - Paid Amount
     - Discount Amount
     - Total Amount
   - Users can create a booking with the selected products.

4. **Export to Excel:**
   - The application allows users to export booking details to an Excel sheet.
   - A button in the table triggers the download of the Excel sheet.

## Installation

Follow these steps to set up the application:

1. Clone the repository:

   ```bash
   git clone https://github.com/dkoderweb/product-export-excel.git
   ```

2. Navigate to the project directory:

   ```bash
   cd product-export-excel
   ```

3. Install dependencies:

   ```bash
   composer update
   ```

4. Create a copy of the environment file:

   ```bash
   cp .env.example .env
   ```

5. Run the migrations:

   ```bash
   php artisan migrate
   ```

   (Alternatively, you can use the provided `product.sql` file.)

6. Generate an application key:

   ```bash
   php artisan key:generate
   ```

7. Run the development server:

   ```bash
   php artisan serve
   ```

8. Access the application in your web browser at [http://localhost:8000](http://localhost:8000).

## Usage

1. Register or log in to the application.

2. Manage products by adding, editing, or deleting them.

3. Navigate to the product menu to add products to your cart.

4. View the booking page to see the calculated Paid Amount, Discount Amount, and Total Amount.

5. Create a booking with the selected products.

6. Export booking details to an Excel sheet using the provided button.

Feel free to explore the application and enhance it further as needed!