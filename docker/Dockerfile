FROM php:7.4-apache
 
RUN apt-get update && apt-get install -y \
    git \
    zlib1g-dev \
    nano \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    gnupg 

RUN apt-get install -y \ 
    build-essential \ 
    libssl-dev \ 
    libjpeg-dev \ 
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libzip-dev \
    zip 
RUN docker-php-ext-install \
    pdo_mysql \
    mysqli \
    gd \
    mbstring 

# MSSQL
ENV ACCEPT_EULA=Y
RUN apt-get update \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/9/prod.list \
        > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get install -y --no-install-recommends \
        locales \
        apt-transport-https \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen \
    && apt-get update \
    && apt-get -y --no-install-recommends install \
        unixodbc-dev \
        msodbcsql17


RUN docker-php-ext-install pdo \
    && pecl install sqlsrv pdo_sqlsrv xdebug \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv xdebug

RUN echo "pdo_sqlsrv.client_buffer_max_kb_size = 2048000" >>  /usr/local/etc/php/conf.d/docker-php-ext-pdo_sqlsrv.ini
RUN echo "client_buffer_max_kb_size = '2048000'" >>  /usr/local/etc/php/conf.d/docker-php-ext-pdo_sqlsrv.ini
RUN echo "sqlsrv.ClientBufferMaxKBSize = 2048000" >>  /usr/local/etc/php/conf.d/docker-php-ext-pdo_sqlsrv.ini

 
RUN docker-php-ext-configure gd --with-freetype --with-jpeg=/usr/include/ --enable-gd 
RUN docker-php-ext-install -j$(nproc) gd
  
RUN a2enmod rewrite
 
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/bin/composer


RUN usermod -u 1000 www-data

RUN a2enmod headers

RUN apt-get clean -y

WORKDIR /var/www/html
