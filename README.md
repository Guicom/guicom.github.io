# Socomec

## Installation :

### 1. Clone the project into your workspace directory

```
git@gitlab.com:actency/prod/socomec/socomec.com.git
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

## Theme :

The Socomec theme is a subtheme of Barrio.
