FROM php:8.5-fpm

RUN pecl install apcu
RUN pecl install xdebug
#RUN pecl install redis

RUN apt-get update && \
apt-get install -y \
zlib1g-dev libzip-dev libicu-dev libpq-dev unzip curl zip fontconfig libfreetype6 libjpeg62-turbo-dev libpng16-16 libxrender1 xfonts-75dpi xfonts-base libpng-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pgsql pdo_pgsql

RUN docker-php-ext-install zip
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-enable apcu
#RUN docker-php-ext-enable redis
RUN docker-php-ext-configure intl && docker-php-ext-install intl
RUN docker-php-ext-configure gd && docker-php-ext-install gd

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --filename=composer && \
    php -r "unlink('composer-setup.php');" && \
    mv composer /usr/local/bin/composer

RUN echo 'short_open_tag = 0' >> /usr/local/etc/php/conf.d/docker-php-short-tag.ini;
RUN echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'max_execution_time = 360' >> /usr/local/etc/php/conf.d/docker-php-exectime.ini;
RUN echo 'xdebug.mode=debug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo 'pm.max_children = 100' >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo 'pm.start_servers = 25' >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo 'pm.min_spare_servers = 25' >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo 'pm.max_spare_servers = 75' >> /usr/local/etc/php-fpm.d/zz-docker.conf

RUN echo 'max_input_vars = 9999' >> /usr/local/etc/php/conf.d/docker-php-max-input-vars.ini

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash && \
    apt-get -y install symfony-cli
