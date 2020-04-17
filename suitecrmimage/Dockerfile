FROM  suitecrmdev-basebuild:latest
LABEL Maintainer="Kiran Kumar Mallru" \
      Description="Suite crm  docker image using a base image. Base image should be available in the openshift container repository"


# Configure nginx
COPY nginx.conf /etc/nginx/nginx.conf
# Remove default server definition
# RUN rm /etc/nginx/conf.d/default.conf

# Configure PHP-FPM
COPY fpm-pool.conf /etc/php7/php-fpm.d/www.conf
# COPY config/fpm-pool.conf /usr/local/etc/php-fpm.d/www.conf
COPY php.ini /etc/php7/conf.d/custom.ini
# use default value variables_order = "GPCS" 
# RUN sed -i 's/variables_order/;&/' /etc/php7/php.ini
# Configure supervisord
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup document root
RUN mkdir -p /var/www/html
    
RUN curl -LJ ${SUITECRM_RELEASE_URL}  -o  SuitecrmRelease.tar.gz && \
    tar -zxf SuitecrmRelease.tar.gz --strip-components 1

# Cleanup 
RUN rm SuitecrmRelease.tar.gz
RUN rm config/configuration.php

RUN composer install --no-cache

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /var/www/html 
# Make the document root a volume
# VOLUME /var/www/html

COPY configuration.php config/configuration.php
# Switch to use a non-root user from here on
USER nobody

# Add application
WORKDIR /var/www/html

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping
