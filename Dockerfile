FROM php:7.4-cli
COPY . /usr/src/formatter
WORKDIR /usr/src/formatter

RUN ./usr/src/formatter/composer-install.sh
RUN composer update

CMD [ "php", "./app.php" ]
