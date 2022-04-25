## Setting up the project locally

To run this application locally, please follow the commands

- Install composer using
  `composer install`
- Copy .env file using `cp .env.example .env` or `copy .env.examaple .env` or manually copy the .env.example file as .env
- Generate application key using
  `php artisan key:generate`
- Migrate the database using `php artisan migrate`
- Seed the database using `php artisan db:seed`
- Install node packages using `npm install`
- Compile resources using `npm run dev`
- Run the application using `php artisan serve`

## Email server setup

    - Email server: Gmail server
    - Email address: your email address
    - Email password: your app pawword [see here how to generate app password: https://support.google.com/mail/answer/185833?hl=en]
    - Email port: 587 [for gmail server]
    - Email host: smtp.gmail.com
    

