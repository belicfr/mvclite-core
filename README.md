<p align="center"><img src="src/Resources/Medias/logo.png" width="400"></p>


# About MVCLite 

*MVCLite* is a lightweight **MVC** (Model-View-Controller) framework for building web applications in **PHP**. It provides a robust foundation for structuring your application's codebase, including features such as **middlewares**, **routing system**, **ORM** (Object-Relational Mapping), **Twig** templating engine integration, and more.

## Features

- **Routing System:** Define clean and intuitive URL routes to handle various HTTP requests.
- **Middlewares:** Easily integrate pre-processing and post-processing logic into your application's request-response cycle.
- **ORM (Object-Relational Mapping):** Simplify database interactions by mapping database tables to PHP objects.
- **Twig Integration:** Use the Twig templating engine for separating logic from presentation in your views.
- **And More:** MVCLite includes many other features to streamline your development process.

## Installation

You can install *MVCLite* via **Composer**. Run the following command in your terminal:

```bash
composer create-project belicfr/mvclite
```

After installing *MVCLite*, you have to configure your application's settings in the `config` directory. The `config.php` file contains the main settings for your application.

```php
const ROUTE_PATH_PREFIX = '/';

const DATABASE_CREDENTIALS = [

    "dbms"      =>  "mysql",

    "host"      =>  "localhost",
    "port"      =>  "3306",
    "charset"   =>  "utf8mb4",
    "name"      =>  "",
    "user"      =>  "",
    "password"  =>  ""

];
```

You also have to configure the **htaccess** file, you can use the `.htaccess_example` file as a base.

```apache
RewriteEngine On
RewriteBase /website/

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_URI} !src/resources/(.*)$

RewriteRule ^(.*)$ index.php?route=$1 [QSA,L]
```

## Usage

### Router

Define your application's **routes** in the `src/Router/routes.php` file. Here's an example of a simple route:

```php
Router::get('/path', Controller::class, "method");
```

You can also define **routes** with a custom name 
```php
Router::get('/path', Controller::class, "method")->setName('routeName');
```

### ORM

The build-in **ORM** allows you to interact with your database. Here's an example of a simple **SELECT** query:

```php
Model::select('column1', 'column2')
       ->where('column', 'value')
       ->orderBy('column', 'ASC')
       ->execute();
```

The is still in development and more features will be added in the future, if you want to do **custom queries** you can use the `Database` class.

```php	
Database::query('INSERT INTO table (column1, column2) VALUES (?, ?)', ['value1', 'value2']);
```

### Middlewares

**Middlewares** are used to perform pre-processing logic on your application's request-response cycle. You can define a **middleware** in the constructor of your controller.

```php
public function __construct()
{
    $this->middleware(AuthMiddleware::class);
}
```

### Views

*MVCLite* uses the **Twig** templating engine for separating logic from presentation in your views. You can create your views in the `src/Views` directory and then render them in your **controllers**.

```php
View::render('view.twig', ['data' => $data]);
```

### Twig

You can use the **Twig** templating engine to create your views. Here's an example of a simple **Twig** template:

```twig
<!DOCTYPE html>
<html>
<head>
    <title>{{ title }}</title>
</head>
<body>
{% if bool %}
    <h1>{{ content1 }}</h1>
{% else %}
    <p>{{ content2 }}</p>
{% endif %}
{{ include('footer.twig', {'data': data}) }}
</body>
</html>
```

## Contributing

Feel free to contribute to *MVCLite* by submitting a **pull request**, opening an **issue**, or sharing your ideas for new **features**.

## License

*MVCLite* is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits

*MVCLite* is maintained by [belicfr](https://github.com/belicfr)

Thank you to all the contributors who have helped make *MVCLite* better : 
- [quentinformatique](https://github.com/quentinformatique) for the documentation and beta testing 
