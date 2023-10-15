FROM phpdockerio/php:8.1-fpm


RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        git \
        php8.1-amqp \
        php8.1-gd \
        php8.1-intl \
        php8.1-mcrypt \
        php8.1-sqlite \
        php8.1-mysql \
        php8.1-psr \
        php8.1-redis \
        php8.1-uuid \
        php8.1-xdebug \
        supervisor \
        php8.1-yaml; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN mkdir -p "/etc/supervisor/logs"
COPY supervisord.conf /etc/supervisor/
CMD ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisor/supervisord.conf"]
