# Sử dụng image PHP 8.2 kèm Apache
FROM php:8.2-apache

# Cài đặt các tiện ích cần thiết
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mbstring zip mysqli pdo pdo_mysql

# Cài Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy toàn bộ source code vào thư mục web
COPY . /var/www/html/

# Đặt thư mục làm việc chính
WORKDIR /var/www/html/

# Cài đặt thư viện PHP qua composer (bỏ qua yêu cầu ext chưa có)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Bật module rewrite (nếu bạn dùng .htaccess)
RUN a2enmod rewrite

# Cấu hình Apache cho phép .htaccess hoạt động
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Mở port 80
EXPOSE 80

# Chạy Apache khi container khởi động
CMD ["apache2-foreground"]
