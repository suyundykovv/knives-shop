
# Knife-Shop

Welcome to the **Knife-Shop** project! This is a Laravel-based web application designed to showcase and manage knives, along with the ability to handle user profiles and payments via Stripe.

## Features

- **User Authentication**: Secure login and registration with email verification.
- **Admin Panel**: Manage knives, users, and other administrative tasks.
- **Knife Management**: Add, update, and delete knives.
- **Profile Management**: Edit and delete user profiles.
- **Stripe Integration**: Checkout functionality using Stripe to handle payments.
- **Inertia.js**: Seamless, single-page application (SPA) experience with Vue.js and Laravel.

## Installation

### Prerequisites

- PHP 8.0 or higher
- Composer
- Node.js and npm
- Laravel 9.x
- Stripe API Key (for payment functionality)

### Step-by-step Installation

1. **Clone the Repository**

   ```bash
   git clone https://github.com/your-username/knife-shop.git
   cd knife-shop
   ```

2. **Install Dependencies**

   Install PHP dependencies via Composer:

   ```bash
   composer install
   ```

   Install JavaScript dependencies via npm:

   ```bash
   npm install
   ```

3. **Set Up Environment Variables**

   Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

   Configure the `.env` file with your database and Stripe API credentials:

    - **Database settings**:
      ```env
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=knife_shop
      DB_USERNAME=root
      DB_PASSWORD=
      ```

    - **Stripe settings**:
      ```env
      STRIPE_KEY=your-stripe-public-key
      STRIPE_SECRET=your-stripe-secret-key
      ```

4. **Generate Application Key**

   ```bash
   php artisan key:generate
   ```

5. **Run Migrations**

   Set up the database by running the migrations:

   ```bash
   php artisan migrate
   ```

6. **Run the Development Server**

   ```bash
   php artisan serve
   ```

   Visit the application at [http://localhost:8000](http://localhost:8000) in your browser.

## Usage

### Pages

- **Knives**: Browse and manage knives in the system.
- **Admin Panel**: Admin can view user and knife information, add or delete knives.
- **Profile**: Users can view and edit their profiles.
- **Checkout**: Add items to your cart and complete a Stripe checkout process.

### Routes

- `/login` — Login page
- `/register` — Registration page
- `/knives` — List of knives
- `/profile/edit` — Profile editing page
- `/checkout` — Payment page

### Admin Features

- **Knife Panel**: Add or delete knives.
- **User Panel**: Admin dashboard for managing users.

## Contributing

We welcome contributions to this project! Here's how you can help:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature-name`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature-name`)
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgements

- [Stripe](https://stripe.com) for the payment API
- [Laravel](https://laravel.com) for building the foundation of the app
- [Inertia.js](https://inertiajs.com/) for seamless page navigation in the app
```
