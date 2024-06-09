1. **Install PHP Dependencies**

    ```bash
    composer install
    ```

    If you encounter any errors, don't worry. We'll resolve them in the next steps.

2. **Install JavaScript Dependencies**

    ```bash
    npm install
    ```

3. **Environment Configuration**

    Duplicate the `.env.example` file and rename it to `.env`. Then, fill in your configuration details in the `.env` file.

    ```bash
    cp .env.example .env
    ```

    Ensure to configure you database connection

4. **Generate Application Key**

    Run the following command to generate an application key for your Laravel application:

    ```bash
    php artisan key:generate
    ```

5. **Database Migration**

    Migrate the database using Artisan:

    ```bash
    php artisan migrate
    ```

6. **Serve the Application**

    Start the Laravel development server:

    ```bash
    php artisan serve
    ```

    If the above command doesn't work for any reason, you can manually serve the application by navigating to the public directory and running:

    ```bash
    php -S localhost:8080
    ```

    Additionally, compile your assets with:

    ```bash
    npm run dev
    ```

7. **Test the Application**

    ```bash
    php artisan test
    ```
