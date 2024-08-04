# RF PHP Routing Library

## Overview

The RF PHP Routing Library provides a flexible and easy-to-use routing system for PHP applications. It supports registering routes from separate files, setting maintenance and 404 page handlers, and defining routes with various HTTP methods. Additionally, it supports route grouping for organizing routes under a common prefix.

## Installation

1. **Install via Composer**

   Make sure you have Composer installed. Run the following command to add the RF PHP Routing Library to your project:

   ```bash
   composer require rf/router
   ```

2. **Autoload Dependencies**

   Ensure that your project’s `vendor/autoload.php` is required in your application entry point.

   ```php
   require 'vendor/autoload.php';
   ```

## Usage

### Importing the Library

Before using the routing methods, you need to import the `Route` class from the `RF\Router` namespace. This is done with the `use` statement:

```php
use RF\Router\Route;
```

This line of code allows you to use the `Route` class methods without needing to write the full namespace each time.

### 1. Register Routes from Separate Files

You can register routes from different files using the `Route::register` method:

```php
Route::register([
    'example/route/other'
]);
```

### 2. Global Settings

#### Maintenance Page

Define a maintenance page handler:

```php
Route::setMaintenance(function() {
    echo "Maintenance Page";
});

// OR

require 'example/page/maintenance.php';
Route::setMaintenance([RF\Page\Maintenance::class, 'index']);
```

#### 404 Page

Define a 404 page handler:

```php
Route::set404(function() {
    echo "Page Not Found";
});

// OR

require 'example/page/page404.php';
Route::set404([RF\Page\Page404::class, 'index']);
```

### 3. Basic Route Usage

Define routes with different HTTP methods:

```php
Route::get('', function() {
    echo "Default Page";
});

// OR

require 'example/page/default.php';
Route::get('', [RF\Page\DefaultPage::class, 'index']);
```

#### HTTP Methods

- `Route::set` - General method for setting routes (default is GET)
- `Route::get` - GET request
- `Route::post` - POST request
- `Route::put` - PUT request
- `Route::delete` - DELETE request
- `Route::patch` - PATCH request
- `Route::options` - OPTIONS request

### 4. Route with Parameters

Define routes with optional or required parameters. Optional parameters are denoted with a `?` in the route pattern, indicating that the parameter is not required and can be omitted in the URL.

#### Example

Define a route with an optional parameter:

```php
Route::get('/param/{id?}', function($id) {
    echo 'Add param: ' . ($id ? $id : 'No parameter provided');
});
```

#### Usage

- **With Parameter:**

  URL: `/param/123`  
  Output: `Add param: 123`

- **Without Parameter:**

  URL: `/param`  
  Output: `Add param: No parameter provided`

### 5. Route Groups

Group routes under a common prefix:

```php
Route::group(['prefix' => '/parent/path'], function() {
    Route::get("", function() {
        echo "Default Page for /parent/path";
    });

    Route::get("/child", function() {
        echo "Path /child page of /parent/path";
    });
});
```

### 5. Run the Router

Finally, execute the router to handle incoming requests:

```php
Route::run();
```

## Contributing

If you would like to contribute to the development of this library, please submit a pull request or open an issue on GitHub.

## License

This library is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.