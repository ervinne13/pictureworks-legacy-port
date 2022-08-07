FROM php:8.1-fpm

# Based on how you would normally install on a DO droplet:
# https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-laravel-with-nginx-on-ubuntu-20-04

# NOTICE!
# I can't install mbstring, pdo_mysql, pcntl normally in the php:8.1-fpm
# docker container so I'm going with docker-php-extension-installer
# here for now. Just note that these 3 are needed on prod too
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions mbstring pcntl pgsql pdo pdo_pgsql

# ==================================================================
# Anything we put inside here are the server production dependencies
# This also serves as a documentation on what to install on the 
# server upon production deployment.

# TODO: double check if we still need an opcache if we're using 8.1
# now, as I'm really new to 7+ php versions

RUN apt-get update && apt-get install -y \ 
    # mbstring pdo_mysql pcntl does not work normally, see comments above
    # JS
    nodejs\
    npm \
    # Others
    curl \
    zip unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# ==================================================================

# Let's add some commonly used aliases when we're inside
# while we still have root access
RUN echo "alias ll='ls -alF'" >> /etc/bash.bashrc

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY --chown=www:www ./pictureworks-server /var/www/pictureworks-server
# 755 instead of 644 so composer can read the files, but let's kee
# editing to the owner
RUN chmod 755 /var/www/pictureworks-server

WORKDIR /var/www/pictureworks-server
USER www

# Setting up Laravel
RUN composer install
RUN composer dump-autoload

RUN php artisan route:clear
RUN php artisan route:cache
RUN php artisan config:clear
RUN php artisan config:cache

EXPOSE 9000
CMD ["php-fpm"]