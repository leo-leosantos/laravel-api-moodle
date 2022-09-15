FROM php:7.3-apache

RUN apt-get update

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" 

RUN apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
        libjpeg62-turbo-dev \
        zlib1g-dev \
    g++ \
        nano \
        mcrypt \
        && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd 

ENV APACHE_DOCUMENT_ROOT=/var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable mysqli
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-install mbstring
RUN a2enmod rewrite headers

RUN apt-get update && apt-get install -y \
    libzip-dev mariadb-client libmagickwand-dev \
    --no-install-recommends

RUN docker-php-ext-install \
    bz2 \
    intl \
    iconv \
    bcmath \
    opcache \
    calendar \
    mbstring \
    pdo_mysql \
    zip

RUN apt update
RUN apt upgrade -y
RUN apt install -y apt-utils

RUN a2enmod rewrite
RUN apt-get install libcurl4-gnutls-dev
RUN apt install -y libmcrypt-dev 
RUN apt install -y libicu-dev
RUN docker-php-ext-install -j$(nproc) intl
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev 
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ 
RUN docker-php-ext-install -j$(nproc) gd   
RUN apt install -y libxml2-dev 
RUN apt install -y libldb-dev
RUN apt install -y libldap2-dev 
RUN apt install -y libxml2-dev
RUN apt install -y libssl-dev
RUN apt install -y libxslt-dev
RUN apt install -y libpq-dev  
RUN apt install -y mariadb-client 
RUN apt install -y libsqlite3-dev
RUN apt install -y libsqlite3-0
RUN apt install -y libc-client-dev
RUN apt install -y libkrb5-dev
RUN apt install -y curl  
RUN apt install -y firebird-dev
RUN apt-get install -y libpspell-dev
RUN apt-get install -y aspell-en
RUN apt-get install -y aspell-de  
RUN apt install -y libtidy-dev
RUN apt install -y libsnmp-dev
RUN apt install -y librecode0
RUN apt install -y librecode-dev 
RUN docker-php-ext-install opcache
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini
RUN docker-php-ext-install soap
RUN docker-php-ext-install ftp
RUN docker-php-ext-install xsl
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install calendar
RUN docker-php-ext-install ctype
RUN docker-php-ext-install dba
RUN docker-php-ext-install dom
RUN docker-php-ext-install zip
RUN docker-php-ext-install session
RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu
RUN docker-php-ext-install ldap
RUN docker-php-ext-install json
RUN docker-php-ext-install hash
RUN docker-php-ext-install sockets
RUN docker-php-ext-install pdo
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install tokenizer
RUN docker-php-ext-install pgsql
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install pdo_mysql 
RUN docker-php-ext-install pdo_sqlite
RUN docker-php-ext-install intl 
RUN docker-php-ext-install mysqli
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl
RUN docker-php-ext-install imap
RUN docker-php-ext-install gd 
RUN docker-php-ext-install exif
RUN docker-php-ext-install fileinfo
RUN docker-php-ext-install gettext
RUN apt install -y libgmp-dev # idk
RUN docker-php-ext-install gmp # idk
RUN docker-php-ext-install iconv
RUN docker-php-ext-install interbase
RUN docker-php-ext-install pdo_firebird
RUN docker-php-ext-install opcache 
RUN docker-php-ext-install pcntl 
RUN docker-php-ext-install phar 
RUN docker-php-ext-install pspell 
RUN docker-php-ext-install recode
RUN docker-php-ext-install shmop
RUN docker-php-ext-install simplexml
RUN docker-php-ext-install snmp
RUN docker-php-ext-install sysvmsg
RUN docker-php-ext-install sysvsem
RUN docker-php-ext-install sysvshm
RUN docker-php-ext-install tidy
RUN docker-php-ext-install wddx
RUN docker-php-ext-install xml
RUN docker-php-ext-install xmlrpc
RUN docker-php-ext-install xmlwriter             
RUN docker-php-ext-install bz2
RUN docker-php-ext-install bz2
#RUN apt install npm
RUN chown -R www-data:www-data /var/www
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app

#docker build -t laravelserver ./docker/ 