# SIMPLE SYMFONY REST API
Building Symfony REST API for e-shop

## Build
Edit .env file
```
DATABASE_URL="mysql://root:@127.0.0.1:3306/shop?serverVersion=10.8.4-MariaDB&charset=utf8mb4"
```
specify your database user instead of `root` and database password after `root:`. Also you can change database name instead of `shop`.

Create new APP_SECRET - Run command
```
php bin/console regenerate-app-secret
```
response:
```
[OK] New APP_SECRET was generated: NEW APP SECRET HERE
```
specify your `NEW APP SECRET HERE` to .env file in APP_SECRET=

Run command.
```
docker compose up
```

Run the application
```
symfony server:start
```

## APIs
# Get Country list

```php
URL: /country
Method: GET
```
Response sample
```json
[
    {
        "id": 1,
        "name": "England",
        "short_name": "en"
    },
    {
        "id": 2,
        "name": "France",
        "short_name": "fr"
    }
]
```
where `id` - id of country in database, `name` - country name, `short_name` - short name of country; used for locale (max 2 symbols)

# Add new Country

```php
URL: /country
Method: POST
```
Data sample
```json
{
    "name": "Ukraine",
    "short_name": "ua"
}
```
where `name` - country name (max 100 symbols, required), `short_name` - short name of country; used for locale (max 2 symbols, required)

Response sample (seccessfull)
```json
{
    "status": 200,
    "success": "Country added successfully",
    "country_id": 4
}
```
Response sample (error)
```json
{
    "status" => 422,
    "errors" => "Data no valid",
    "text" => "Parameter Name is not found"
}
```

# Get Country by Id

```php
URL: /country/{id}
Method: GET
```
Response sample (seccessfull)
```json
{
    "id": 4,
    "name": "Ukraine",
    "short_name": "ua"
}
```

# Update Country by Id

```php
URL: /country/{id}
Method: PUT
```
Data sample
```json
{
    "name": "Ukraine",
    "short_name": "uk"
}
```
where `name` - country name (max 100 symbols, not required), `short_name` - short name of country; used for locale (max 2 symbols, not required)

Response sample (seccessfull)
```json
{
    "status": 200,
    "success": "Country updated successfully"
}
```

# Delete Country by Id

```php
URL: /country/{id}
Method: DELETE
```
Response sample (seccessfull)
```json
{
    "status": 200,
    "errors": "Country deleted successfully"
}
```

# Get Product list
```php
URL: /products
Method: GET
```
Data sample
```json
{
    "locale": "en"
}
```
where `locale` - country short name (required). Can take value all or `short_name` from table countries

Response sample for all
```json
[
    {
        "id": 1,
        "name": "Bread",
        "price": 10,
        "currency": "USD",
        "locales": {
            "en": {
                "vat": 7,
                "price": 10.7
            },
            "fr": {
                "vat": 5,
                "price": 10.5
            }
        }
    },
    {
        "id": 2,
        "name": "Wine",
        "price": 50,
        "currency": "USD",
        "locales": {
            "en": {
                "vat": 15,
                "price": 57.5
            },
            "fr": {
                "vat": 12,
                "price": 56
            }
        }
    },
    {
        "id": 10,
        "name": "banana",
        "price": 3,
        "currency": "USD",
        "locales": {
            "en": {
                "vat": 1,
                "price": 3.03
            },
            "fr": {
                "vat": 1,
                "price": 3.03
            }
        }
    }
]
```
Response sample for short_name
```json
[
    {
        "id": 1,
        "name": "Bread",
        "price": 10.7,
        "currency": "USD",
        "locale": "en",
        "country": "England"
    },
    {
        "id": 2,
        "name": "Wine",
        "price": 57.5,
        "currency": "USD",
        "locale": "en",
        "country": "England"
    },
    {
        "id": 10,
        "name": "banana",
        "price": 3.03,
        "currency": "USD",
        "locale": "en",
        "country": "England"
    }
]
```
Response sample error
```json
{
    "status": 422,
    "errors": "Data no valid",
    "text": "No data or parameter 'locale' not found"
}
```

# Add new Product
```php
URL: /products
Method: POST
```
Data sample
```json
{
    "name": "Lemon",
    "price": 7,
    "currency": "USD",
    "vat": {
        "en": 2,
        "fr": 2
    }
}
```
where `name` - product name (max 100 symbols, required), `price` - standart price of product, without VAT (required), `currency` - currency of the product (3 symbols, for example USD, not required. Default USD), `vat` - VAT for each country (required. key - short name od country, value - amount of VAT in percentage, min 1 and max 20)

Response sample
```json
{
    "status": 200,
    "success": "Product added successfully",
    "product_id": 11
}
```

# Get Product by Id
```php
URL: /products/{id}
Method: GET
```
where `{id}` - id of product from database

Data sample
```json
{
    "local": "en"
}
```
where `locale` - country short name (required). Can take value `short_name` from table countries

Response sample
```json
{
    "id": 11,
    "name": "Lemon",
    "price": 7.14,
    "currency": "USD",
    "locale": "en",
    "country": "England"
}
```

# Update Product by Id
```php
URL: /products/{id}
Method: PUT
```
where `{id}` - id of product from database

Data sample
```json
{
    "locale": "en",
    "name": "Orange",
    "price": 8,
    "currency": "USD",
    "vat": 3
}
```
where `locale` - country short name (required). Can take value `short_name` from table countries; `name` - product name (not required), `price` - product price (not required), `currency` - currency of product (not required), `vat` - `vat` - VAT for country's locale (not required. key - short name od country, value - amount of VAT in percentage, min 1 and max 20)

Response sample
```json
{
    "status": 200,
    "success": "Product updated successfully"
}
```

# Delete Product by Id
```php
URL: /products/{id}
Method: DELETE
```
where `{id}` - id of product from database

Response sample
```json
{
    "status": 200,
    "errors": "Product deleted successfully"
}
```
