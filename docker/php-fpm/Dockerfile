FROM php:8.1-fpm

RUN usermod -u 1000 www-data

RUN apt-get update && apt-get install -y \
    libpq-dev libpng-dev libbz2-dev libjpeg-dev zlib1g-dev && \
    php -i && \
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install --help && \
    docker-php-ext-install -j$(nproc) pdo pgsql pdo_pgsql pdo_mysql bcmath bz2 -j$(nproc) gd
RUN pecl channel-update pecl.php.net && pecl install redis mcrypt-1.0.1 && docker-php-ext-enable redis

ARG INSTALL_XDEBUG=false
ARG XDEBUG_PORT=9000
ENV INSTALL_XDEBUG ${INSTALL_XDEBUG}
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
RUN if [ ${INSTALL_XDEBUG} = true ]; then \
  yes | pecl install xdebug \
      && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" >> /usr/local/etc/php/conf.d/xdebug.ini \
      && sed -i "s/xdebug.remote_port=9000/xdebug.remote_port=${XDEBUG_PORT}/" /usr/local/etc/php/conf.d/xdebug.ini \
  && apt-get update && apt-get install -y \
    iproute2 \
  && (head --lines 3 /usr/local/bin/docker-php-entrypoint && echo "ip -4 route list match 0/0 | awk '{print \$3\" host.docker.internal\"}' >> /etc/hosts" && tail --lines=+3 /usr/local/bin/docker-php-entrypoint ) > /usr/local/bin/docker-php-entrypoint1 \
  && chown root:root /usr/local/bin/docker-php-entrypoint1 \
  && chmod +x,g+w /usr/local/bin/docker-php-entrypoint1 \
  && mv -f /usr/local/bin/docker-php-entrypoint1 /usr/local/bin/docker-php-entrypoint \
;fi

# Clean up
USER root
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Set default work directory
WORKDIR /app

COPY docker.conf /usr/local/etc/php-fpm.d/docker.conf
COPY php.ini /usr/local/etc/php

RUN mkfifo -m 666 /tmp/stderr

CMD ["sh", "-c", "exec 3<>/tmp/stderr; cat <&3 >&2 & exec php-fpm -F >/tmp/stderr 2>&1"]

EXPOSE 9000
