# VendonTask

## Backend

1. Run `composer install` to install composer dependencies
2. Configure `.env` file with database configuration
3. Run server
    - Configure apache or nginx server to run from `public` folder
    - Or:
        1. Navigate into public folder with `cd public`
        2. Run `php -S <host>:<port>` to start the server

## Frontend

#### Note: You need node.js for frontend to work

1. Navigate into frontend folder `cd frontend`
2. Run `npm install` to install dependencies
3. Configure backend API inside `.env` file
4. Configure frontend port inside `vite.config.js` file
5. Run frontend server by executing command `npm run dev`