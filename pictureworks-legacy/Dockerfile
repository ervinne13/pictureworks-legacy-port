# we don't know what server we are running the legacy pictureworks, lets just use something that
# would run a php 8.1 for now and change this later.
FROM php:7.3-fpm 

# Based on how you would normally install on a DO droplet:
# https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-laravel-with-nginx-on-ubuntu-20-04

# NOTICE!
# I can't install mbstring, pdo_mysql/php-pgsql, pcntl normally in the php:8.1-fpm
# docker container so I'm going with docker-php-extension-installer
# here for now. Just note that these 3 are needed on prod too
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions mbstring pdo_mysql pcntl

RUN apt update -y && apt upgrade -y
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# ==================================================================
# Anything we put inside here are the server production dependencies
# This also serves as a documentation on what to install on the 
# server upon production deployment.

# RUN apt-get update && apt-get install -y \    
#     curl 

# ==================================================================

# Let's add some commonly used aliases when we're inside
# while we still have root access
RUN echo "alias ll='ls -alF'" >> ~/.bashrc

# Setting up docker compose up script before we change to www
# COPY ./docker-start.sh /usr/local/bin
# RUN chmod +x /usr/local/bin/docker-start.sh

WORKDIR /var/www/

# 1000 as in the same user usually when you run the nginx
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY --chown=www:www ./pictureworks-legacy/pgphptest /var/www/legacy
# Owner can mod, others can read
RUN chmod 644 /var/www/legacy

USER www

EXPOSE 9000
CMD ["php-fpm"]