# Sử dụng image PHP 8.2 kèm Apache
FROM php:8.2-apache

# Cài đặt các tiện ích cần thiết
RUN apt-get update && apt-get install -y unzip git curl

# Cài Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy toàn bộ source code vào thư mục web
COPY . /var/www/html/

# Đặt thư mục làm việc chính
WORKDIR /var/www/html/

# Cài đặt thư viện PHP qua composer
RUN composer install --no-dev --optimize-autoloader

# Bật module rewrite (nếu bạn dùng .htaccess)
RUN a2enmod rewrite

# Cấu hình Apache để cho phép .htaccess hoạt động
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Mở port 80 (Render sẽ map tự động)
EXPOSE 80

# Chạy Apache khi container khởi động
CMD ["apache2-foreground"]
