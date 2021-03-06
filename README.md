
Laravel Rest API CRUD Generator

### Requirements
    Laravel >=5.1 and Laravel <= 5.6.*
    PHP >= 5.5.9

## Installation

1. Run
    ```
    composer require sluggergen/slugger dev-master
    ```

2. Add service provider to **/config/app.php** file.
    ```php
    'providers' => [
        ...

         Slugger\Crud\CrudServiceProvider::class,
    ],
    ```
  

3. Publish config file & generator template files.
    ```
    php artisan vendor:publish --provider="Slugger\Crud\CrudServiceProvider"
    ```
4. All Done.
    ```
    ```

    ```

#### Crud command:

```
php artisan crud:generate Posts --fields="title:string, body:text"
```

You can also easily include route, set primary key, set views directory etc through options **--route**, **--pk** :

```

Options:

- --fields : Fields name for the form & model.
- --route : Include Crud route to routes.php? yes or no.
- --pk : The name of the primary key.
- --namespace : Namespace of the controller.
- --route-group : Prefix of the route group.

-----------
-----------


#### Other commands (optional):

For controller generator:

```
php artisan crud:controller PostsController --crud-name=posts --model-name=Post  --route-group=admin
```

For model generator:

```
php artisan crud:model Post --fillable="['title', 'body']"
```

For migration generator:

```
php artisan crud:migration posts --schema="title:string, body:text"
```

```
Generate Pivot tables

```
php artisan make:migration:pivot tags posts
```

By default, the generator will attempt to append the crud route to your *routes/web.php* file. If you don't want the route added, you can use the option ```--route=no```, or edit the route path on config file.

After creating all resources, run migrate command. *If necessary, include the route for your crud as well.*

```
php artisan migrate
```

If you chose not to add the crud route in automatically (see above), you will need to include the route manually.
check your routes/api.php file
```php
Route::apiResource('posts', 'PostsController');
```

### Supported Field Types

These fields are supported for migration and view's form:

* string
* char
* varchar
* password
* email
* date
* datetime
* time
* timestamp
* text
* mediumtext
* longtext
* json
* jsonb
* binary
* number
* integer
* bigint
* mediumint
* tinyint
* smallint
* boolean
* decimal
* double
* float
