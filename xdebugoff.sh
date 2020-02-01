#!/usr/bin/env bash

function displayWarning {
  echo -e "\e[7;49;33m$1\e[0m"
}

function displayOperation {
  echo -e "\e[7;49;32m$1\e[0m"
}

function displayError {
  echo -e "\e[7;49;31m$1\e[0m"
}

cd /usr/local/etc/php
sed -ie "s/zend_extension/;zend_extension/g" php.ini
/etc/init.d/apache2 reload
