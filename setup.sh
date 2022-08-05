#!/bin/bash
set -o allexport
source .env
set +o allexport

add_host_or_ignore() {
    if grep "$1" /etc/hosts; then
        echo "$1 already exists in /etc/hosts!"
    else
        echo "$2      $1" >> /etc/hosts  
        echo "Added the entry $2      $1 to /etc/hosts"
    fi
}

create_nginx_conf_443_entry() {
    cp $1/conf.d.tmpl/$2-443.conf $1/conf.d/$2-443.conf
    sed -i "s/%NGINX_ALIAS%/$NGINX_ALIAS/g" $1/conf.d/$2-443.conf
}

gen_certs() {
    echo "Generating self signed certs"
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -subj "/C=PH/ST=NCR/L=Manila/O=PayrollDemo/OU=Development Team/CN=$NGINX_ALIAS" \
    -keyout $1/certs/server.key -out $1/certs/server.crt
    echo "Generated $1/certs/server.key $1/certs/server.crt"
}

init_app_env() {
    echo "Generating $1/.env"
    cp $1/.env.tmpl $1/.env
    sed -i "s/%NGINX_ALIAS%/$NGINX_ALIAS/g" $1/.env
    sed -i "s/%DB_IP%/$DB_IP/g" $1/.env
    sed -i "s/%DB_NAME%/$DB_NAME/g" $1/.env
    sed -i "s/%DB_USERNAME%/$DB_USERNAME/g" $1/.env
    sed -i "s/%DB_PASSWORD%/$DB_PASSWORD/g" $1/.env
}

add_host_or_ignore $NGINX_ALIAS $NGINX_IP
add_host_or_ignore $NGINX_LEGACY_ALIAS $NGINX_LEGACY_ALIAS
create_nginx_conf_443_entry pictureworks-nginx pictureworks-legacy
gen_certs pictureworks-nginx
init_app_env pictureworks-server
