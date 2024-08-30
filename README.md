<a id="readme-top"></a>
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="space-themed-reddit-clone">About The Project</a>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#Technology-Stack">Technology Stack</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgments">Acknowledgments</a></li>
  </ol>
</details>

# Space-Themed Reddit Clone
[![Laravel][Laravel.com]][Laravel-url]

This project is a space-themed Reddit clone built using Laravel. The application serves as an API that allows users to create, manage, and interact with posts and comments related to celestial bodies and galaxies. For admins or editors there is an admin panel Filament which allows you to manage celestial bodies, galaxies, posts, admin can grant editor role to user. Redis is used for cache, Rabbit MQ is used for queues. For documentation swagger, and for tracking the behavior of the application telescope laravel. More details below.

## Technology Stack

* Laravel Framework 11.21.0
* PHP 8.3.8
* Redis 7.2.0
* RabbitMQ 3.13.7
* Laravel Breeze API 2.1
* Filament 3.2
* Telescope 5.2
* Swagger 8.6

## Features

- **User Authentication**: Secure user authentication using Laravel Sanctum.
- **Posts and Comments**: Users can create, view, update, and delete posts and comments.
- **Celestial Bodies and Galaxies**: API resources to manage information about celestial bodies and galaxies.
- **Middleware**: Custom middleware to check if a user is an admin or the author of a post/comment.
- **Policy**: Ensures that only the author can manage their own posts and comments.
- **Pagination**: Support for paginating large datasets.
- **Caching**: Uses Redis for caching to improve performance.
- **Database Transactions**: Ensures data integrity during operations.
- **Queues**: RabbitMQ is used for background comment checking.
- **Cron Jobs**: Scheduled tasks, such as sending congratulatory emails on user birthdays.
- **Email Notifications**: Sends emails for specific events like new comments on a post or user birthdays.
- **Logging and Debugging**: Laravel Telescope is integrated for monitoring, debugging, and logging.
- **API Documentation**: Swagger is integrated for API documentation.
- **Seeder**: Provides database seeding for initial setup and testing.
- **Services**: Implements a clean architecture approach using service layers.
- **Unit Tests**: Provides testing for the application.

## API Endpoints

### Authentication Routes

- `POST /register`: Register a new user. Requires the user to be a guest (not authenticated).

- `GET /user`: Get authenticated user details.
  
- `POST /login`: Log in an existing user. Requires the user to be a guest.

- `POST /forgot-password`: Send a password reset link to the user's email. Requires the user to be a guest.

- `POST /reset-password`: Reset the user's password. Requires the user to be a guest.

- `GET /verify-email/{id}/{hash}`: Verify the user's email address. Requires the user to be authenticated, the URL to be signed, and throttles requests to 6 per minute.

- `POST /email/verification-notification`: Resend the email verification notification. Requires the user to be authenticated and throttles requests to 6 per minute.

- `POST /logout`: Log out the authenticated user. Requires the user to be authenticated.

### Post Routes

- `GET /posts/user/{userId}`: Get all posts by a specific user.
- `GET /posts`: Get all posts.
- `POST /posts`: Create a new post. Only authorized.
- `GET /posts/{id}`: Get a specific post by ID.
- `PUT /posts/{id}`: Update a specific post by ID. Author/Admin/Editor only.
- `DELETE /posts/{id}`: Delete a specific post by ID. Author/Admin/Editor only.

### Comment Routes

- `GET /comments/post/{postId}`: Get all comments for a specific post.
- `GET /comments`: Get all comments.
- `POST /comments`: Create a new comment. Only authorized.
- `GET /comments/{id}`: Get a specific comment by ID.
- `PUT /comments/{id}`: Update a specific comment by ID. Author/Admin/Editor only.
- `DELETE /comments/{id}`: Delete a specific comment by ID. Author/Admin/Editor only.

### Celestial Bodies Routes

- `GET /bodies`: Get all celestial bodies.
- `POST /bodies`: Create a new celestial body. Admin/Editor only.
- `GET /bodies/{id}`: Get a specific celestial body by ID.
- `PUT /bodies/{id}`: Update a specific celestial body by ID. Admin/Editor only.
- `DELETE /bodies/{id}`: Delete a specific celestial body by ID. Admin/Editor only.

### Galaxy Routes

- `GET /galaxies`: Get all galaxies.
- `POST /galaxies`: Create a new galaxy. Admin/Editor only.
- `GET /galaxies/{id}`: Get a specific galaxy by ID.
- `PUT /galaxies/{id}`: Update a specific galaxy by ID. Admin/Editor only.
- `DELETE /galaxies/{id}`: Delete a specific galaxy by ID. Admin/Editor only.

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/Jonika3000/Space-Api.git
    cd Space-Api
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Copy the `.env.example` file to `.env` and update the environment variables as needed:

    ```bash
    cp .env.example .env
    ```

4. Generate the application key:

    ```bash
    php artisan key:generate
    ```

5. Run database migrations and seed the database:

    ```bash
    php artisan migrate --seed
    ```

6. Start the development server:

    ```bash
    php artisan serve
    ```

## Running Tests

Run unit tests using PHPUnit:

```bash
php artisan test
```

## Usage

This project is primarily an API with several integrated tools for documentation, administration, and monitoring:

- **Swagger**: Visit `/api/documentation` to access the Swagger UI. This interface provides interactive API documentation, allowing you to test and explore the available API endpoints.

- **Filament**: Use the admin panel at `/admin` to manage users, posts, and other administrative tasks. Filament provides a user-friendly interface for backend management.

- **Telescope**: To monitor and debug application activities, visit `/telescope`. Laravel Telescope offers detailed insights into requests, exceptions, database queries, and more.

![image](https://github.com/user-attachments/assets/c7446546-1b3b-43c6-baf8-b4ff1261bf08)

![image](https://github.com/user-attachments/assets/a09d9ecb-530b-447e-be86-51beb0d488ed)

![image](https://github.com/user-attachments/assets/3967f0c5-3c2a-4cb1-a993-b23895c5b5c2)


<!-- MARKDOWN LINKS & IMAGES -->
[Laravel.com]: https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white
[Laravel-url]: https://laravel.com
