> This is the first version of the package. It is the most stable version that includes all Trendyol services and will be shared in the shortest time possible.

# Trendyol Yemek Service
![Packagist Downloads](https://img.shields.io/packagist/dt/furkanmeclis/trendyol-yemek-api) ![Packagist Stars](https://img.shields.io/packagist/stars/furkanmeclis/trendyol-yemek-api) ![Packagist Version](https://img.shields.io/packagist/v/furkanmeclis/trendyol-yemek-api) ![Packagist License](https://img.shields.io/packagist/l/furkanmeclis/trendyol-yemek-api)




This repository provides a PHP implementation for integrating with the Trendyol Food API. It includes various functionalities such as managing menus, restaurants, and orders, allowing seamless interactions with the Trendyol food delivery system.

## Installation

You can install this package via Composer:

```bash
composer require furkanmeclis/trendyol-yemek-service
```
## Usage

To use the `TrendyolYemekApi` class, initialize it with your `supplierId`, `restaurantId`, `apiUsername`, and `apiPassword`. Here is an example:

```php
require 'vendor/autoload.php';

use furkanmeclis\Tools\TrendyolYemekApi;

try {
    $supplierId = 'YOUR_SUPPLIER_ID';
    $restaurantId = 'YOUR_RESTAURANT_ID';
    $apiUsername = 'YOUR_API_USERNAME';
    $apiPassword = 'YOUR_API_PASSWORD';
    $api = new TrendyolYemekApi($supplierId, $restaurantId, $apiUsername, $apiPassword);
    
    // Get menu
    $menu = $api->getMenu();
    print_r($menu);
    
    // Get Orders 
    $orders = $api->getPackages();
    print_r($orders);
    
    // Update category status
    $api->updateCategoryStatus('ACTIVE');
    
    // Other methods...
} catch (Exception $e) {
    echo $e->getMessage();
}

```
## Methods

*   `getMenu()`: Retrieves the restaurant's menu.
*   `updateCategoryStatus($status)`: Updates the status of a specific category.
*   `updateProductStatus($productId, $status)`: Updates the status of a specific product.
*   `getRestaurantInfo()`: Retrieves information about the restaurant.
*   `updateDeliveryAreas($areas)`: Updates the delivery areas for the restaurant.
*   `updateWorkingHours($workingHours)`: Updates the working hours for the restaurant.
*   `updateRestaurantStatus($status)`: Updates the status of the restaurant.
*   `getPackages()`: Retrieves the available packages for the restaurant.
*   `acceptOrder($packageId, $preparationTime)`: Accepts a new order.
*   `completeOrder($packageId)`: Marks an order as completed.
*   `shipOrder($packageId)`: Ships an order.
*   `deliverOrder($packageId)`: Delivers an order.
*   `cancelOrder($packageId, $itemIdList, $reasonId)`: Cancels an order.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
