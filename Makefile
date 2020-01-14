install:
  composer install
  ./vendor/bin/phing install
  cd web
  sudo npm install --global gulp-cli
  sudo npm install --no-bin-link
  gulp
