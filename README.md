# This API is made for getting sipping tracking information

**To run the API, you need to**
1. Install the required packages by running `composer install`
2. Run the API by executing `php artisan serve`
3. Access the API at host which is `localhost:8000` by default.
4. You can use the API by sending a POST request to `/track` with the following JSON body:
```json
{
  "tracking_code": "YOUR_TRACKING_CODE" // Tracking code must contain only uppercase letters and numbers.
}
```
