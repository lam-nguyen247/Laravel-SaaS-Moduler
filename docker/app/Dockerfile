# Sử dụng ảnh PHP cùng phiên bản bạn muốn (ví dụ: PHP 8.2)
FROM library/php:8.2-fpm-bullseye

COPY docker/app/php.ini /usr/local/etc/php/php.ini

ARG UID=1000
RUN useradd -m -u ${UID} docker \
  && echo "alias art='php artisan'" >> /home/docker/.bashrc \
  && echo "alias yd='yarn dev'" >> /home/docker/.bashrc \
  && echo "alias yw='yarn watch'" >> /home/docker/.bashrc \
  && echo "alias cf='/var/www/html/laravel-app/vendor/bin/php-cs-fixer'" >> /home/docker/.bashrc \
  && echo "alias cff='/var/www/html/laravel-app/vendor/bin/php-cs-fixer fix'" >> /home/docker/.bashrc \
  && echo "alias yp='yarn prettier'" >> /home/docker/.bashrc \
  && echo "alias ypw='yarn prettier --write'" >> /home/docker/.bashrc \
  && echo "alias ypwd='yarn prettier --write .'" >> /home/docker/.bashrc \
  && echo "alias jsdoc='yarn doc'" >> /home/docker/.bashrc \
  && chown docker:docker /home/docker/.bashrc

# thêm thư viện
RUN apt-get update \
  && apt-get install -y \
  git \
  zip \
  unzip \
  gdal-bin \
  make \
  g++ \
  sqlite3 \
  libsqlite3-dev \
  zlib1g-dev \
  vim

RUN apt-get update \
  && apt-get install -y libzip-dev libpq-dev libonig-dev libicu-dev libpng-dev libwebp-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install -j$(nproc) zip gd exif bcmath pdo_mysql pdo_pgsql intl \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.client_host = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Copy php-fpm
COPY docker/app/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Cài đặt các extension PHP mà bạn cần
RUN docker-php-ext-install pdo pdo_mysql

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Đặt thư mục làm việc trong container
WORKDIR /var/www/html

# Sao chép thư mục backend vào container
COPY ./backend /var/www/html

# Expose cổng mà PHP-FPM sẽ chạy (mặc định là 9000)
EXPOSE 9000

# Chạy PHP-FPM
CMD ["php-fpm"]