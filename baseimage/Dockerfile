FROM  phpbase:7.3-fpm-alpine
LABEL Maintainer="Kiran Kumar Mallru" \
      Description="Lightweight container with Nginx 1.16 & PHP-FPM 7.3 based on Alpine Linux. based on https://github.com/TrafeX/docker-php-nginx. Credits Tim de Pater"


# Install packages
RUN apk update && apk upgrade 
RUN apk --no-cache add php7 php7-fpm php7-mysqli php7-json php7-openssl php7-curl \
    php7-zlib php7-xml php7-phar php7-intl php7-dom php7-xmlreader php7-ctype php7-session \
    php7-mbstring php7-gd nginx supervisor curl build-base gcc mysql-client \
    freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev \
    icu-libs jpegoptim optipng pngquant gifsicle git libzip-dev imap-dev openssl-dev \
    krb5-dev libxml2-dev icu-dev zip php7-zip zlib-dev musl-dev  bzip2-dev 



# Installing extensions
RUN docker-php-ext-install mysqli pdo_mysql mbstring exif pcntl pdo bcmath && docker-php-ext-enable pdo_mysql
RUN docker-php-ext-configure gd --with-gd --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd 

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl 
RUN docker-php-ext-install imap
RUN docker-php-ext-configure intl
RUN docker-php-ext-configure intl --enable-intl 
RUN docker-php-ext-install -j$(nproc) intl
RUN docker-php-ext-install soap

RUN docker-php-ext-configure mysqli --with-mysqli
RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-configure zip --with-libzip=/usr/include  && docker-php-ext-install zip
# Workaround for loading PDO
# RUN cp /usr/local/lib/php/extensions/no-debug-non-zts-20180731/* /usr/lib/php7/modules/
# Configure nginx
COPY nginx.conf /etc/nginx/nginx.conf
# Remove default server definition
RUN rm /etc/nginx/conf.d/default.conf

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
RUN mkdir -p /var/www/html/public

COPY index.php/ /var/www/html/public/

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /var/www/html && \
  chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib/nginx && \
  chown -R nobody.nobody /var/log/nginx

# Make the document root a volume
# VOLUME /var/www/html

# Switch to use a non-root user from here on
USER nobody

# Add application
WORKDIR /var/www/html
# COPY src/ /var/www/html/

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping
