### Configuration project

#### Clone project
```
git clone https://github.com/obabichev/deutsch-server.git
cd deutsch-server
```

#### Install dependencies
```
php --version

#if php is not installed:
brew install httpd php72
```

```
composer --version

# if composer is not installed:
curl -sS https://getcomposer.org/installer | php #(https://www.abeautifulsite.net/installing-composer-on-os-x)

composer install
```

#### Install postgres
this link should cover everything... we need empty db and user

https://www.codementor.io/engineerapart/getting-started-with-postgresql-on-mac-osx-are8jcopb

#### Setup environment
```
cp .env.example .env #and fill created file with db parameters
```

#### Migrate
```
php artisan migrate
```

#### Run server
```
php artisan serve
```
