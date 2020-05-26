[![pipeline status](https://gitlab.com/socomec/webfactory/socomec.com/badges/develop/pipeline.svg)](https://gitlab.com/socomec/webfactory/socomec.com/commits/develop)

# Socomec

## Installation :

### 1. Clone the project into your workspace directory

```
git@gitlab.com:socomec/webfactory/socomec.com.git
```

### 2. Navigate to the project directory

```
cd socomec
```

### 3. Create your docker-compose file

```
cp docker-compose-example.yml docker-compose.yml
```

### 4. Customize your docker-compose file

You should at least check and maybe adjust the volumes paths for the web container.

### 5. Initialize the containers

```
docker-compose up -d
```

### 6. Log into your web container

#### 6.1. Get the container name:

```
docker ps | grep web
```

The name should look like `socomec_web_1`

#### 6.2. Log into the container:

```
docker exec -it socomec_web_1 bash
```

Replace `socomec_web_1` with your container name.

### 7. Navigate to the project root

```
cd /var/www/html
```

### 8. Install project dependencies

```
composer install
```

### 9. Install Drupal

```
./vendor/bin/phing install
```

## Gulp setup

From the `web` folder, run :

```
sudo npm install --global gulp-cli
sudo npm install --no-bin-link
gulp
```

## Theme

The Socomec theme is a subtheme of Barrio.

## Pardot Form

Pardot form style & js are manage throw a specfics github project.
For install
* go to web/themes/custom/socomec/assets
* git clone git@github.com:Guicom/guicom.github.io.git pardot-assets

To compile CSS you must launch `gulp pardot`

## Content hub

Content hub is part of the web factory as a subsite.

### Setup the content hub

#### 1. Create a database for the content hub

In our example, we will name it `contenthub`.

#### 2. Create the local settings file

From project root:

```
cp config/drupal/contenthub/example.settings.local.php config/drupal/contenthub/settings.local.php
```

#### 3. Configure the local settings file

Configure the database connection info:

```php
$databases["default"]["default"] = array (
  "database" => "contenthub",
  'username' => 'mysqlusr',
  'password' => 'mysqlpwd',
  "host" => "mysql",
  "port" => "3306",
  "namespace" => "Drupal\\Core\\Database\\Driver\\mysql",
  "driver" => "mysql",
);
```

#### 4. Enable the site

If `web/sites/sites.php` does not exist:

```
cp web/sites/example.sites.php web/sites/sites.php
```

Then, edit `sites.php` and configure it like:

```php
$sites['socomec.com.loc'] = 'default';
$sites['contenthub.loc'] = 'contenthub';
```

Where `socomec.com.loc` is the URL of the default website and `contenthub.loc` is the URL of the content hub.

#### 4. Build the site

```
drush cr
./vendor/bin/phing build -Dmultisite.uri=contenthub.loc
```

Where `contenthub.loc` is the URL declared in `sites.php`.

### Update the content hub

Two steps are needed to update the content hub.

#### 1. Export specific configuration prior to updating

This step is very important. If any changes are made to the configuration of the content hub, they may be lost if this step is not

```
drush --uri=contenthub.loc csex contenthub
```

#### 2. Import configuration

On this step, the project can be updated almost as usual, but by specifying the URI of the content hub.

```
./vendor/bin/phing update -Dmultisite.uri=contenthub.loc
```
