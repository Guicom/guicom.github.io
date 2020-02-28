FROM actency/docker-apache-php:7.3

ADD . /var/www/html/

# Set and run a custom entrypoint
COPY iaac/docker/drupal/core/docker-entrypoint.sh /
RUN chmod 777 /docker-entrypoint.sh && chmod +x /docker-entrypoint.sh
ENTRYPOINT ["/docker-entrypoint.sh"]

