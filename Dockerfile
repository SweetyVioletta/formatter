FROM php:7.4-cli
COPY . /usr/src/formatter
WORKDIR /usr/src/formatter
CMD [ "php", "./app.php" ]