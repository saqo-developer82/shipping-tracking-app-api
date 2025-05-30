# This API is made for getting sipping tracking information

**To run the API, you need to**
1. Install the required packages by running `composer install`
2. Set up your environment by copying the `.env.example` file to `.env` and configuring it as needed.
3. Generate the application key by running `php artisan key:generate`
4. Run the migrations to set up the database by executing `php artisan migrate`
5. (Optional) Seed the database with sample data by running `php artisan db:seed`
6. (Optional) If you want to use the API with a specific shipping provider, you can configure it in the `.env` file.
7. Run the API by executing `php artisan serve`
8. You can now access the API at `http://localhost:8000/api/track`. with a JSON body containing the tracking number and provider, like this:
```json
{
  "tracking_code": "123456789"
}
```
9. To test the API, you can use tools like Postman or cURL to send requests.
10. You can also run the tests by executing `php artisan test` to ensure everything is working correctly.

Set these environment variables in your `.env` file:
```aiignore
TRACKING_STORAGE_DRIVER=sqlite or csv
CSV_TRACKING_FILE= your csv file path
API_RATE_LIMIT=60
API_CACHE_TTL=300
CORS_ALLOWED_ORIGINS=http://localhost:3000
```



