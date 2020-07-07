FROM alpine:edge

#COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
COPY ./ /var/www/html/

#RUN docker-php-ext-install bz2 && docker-php-ext-enable bz2

WORKDIR /var/www/html

RUN apk add --update --no-cache \
    tzdata \
    php7 \
    php7-curl \
    php7-pecl-apcu \
    php7-fileinfo \
    php7-json \
    php7-tokenizer \
    composer \
    && composer install -on --no-ansi --no-dev --no-progress \
    && ./vendor/bin/rr get-binary

EXPOSE 8080

ENTRYPOINT ./rr serve -v -d