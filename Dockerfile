# to make service production ready
FROM nginx:1.22

RUN apt update -y
RUN apt-get install -y software-properties-common wget gnupg2 git
RUN curl https://packages.sury.org/php/apt.gpg | apt-key add -
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" |  tee /etc/apt/sources.list.d/php.list
RUN apt-get update -y
RUN apt-get install -y php8.1-fpm php8.1-dom php8.1-zip php8.1-curl php8.1-intl php8.1-redis php8.1-mysql -y


RUN rm -rf /etc/nginc/nginx.conf
ADD nginx/nginx.conf /etc/nginx/


WORKDIR /var/www/html/
COPY --chown=www-data:www-data  . .

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --working-dir /var/www/html/src/

CMD php /var/www/html/src/artisan cache:clear && php /var/www/html/src/artisan config:cache && service php8.1-fpm start && nginx -g 'daemon off;'
