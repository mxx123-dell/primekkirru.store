# ✅ 1. Sử dụng image PHP có Apache sẵn
FROM php:8.2-apache

# ✅ 2. Cài đặt extension cần thiết (mysqli, pdo, mbstring,...)
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo_mysql

# ✅ 3. Bật mod_rewrite để .htaccess hoạt động
RUN a2enmod rewrite

# ✅ 4. Cấu hình Apache cho phép .htaccess override
RUN echo '<Directory /var/www/html/>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/override.conf \
    && a2enconf override

# ✅ 5. Copy toàn bộ code vào thư mục web
COPY . /var/www/html/

# ✅ 6. Cài composer (nếu dùng Dotenv / vendor)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /var/www/html/
RUN composer install --no-dev --optimize-autoloader || true

# ✅ 7. Phân quyền cho Apache
RUN chown -R www-data:www-data /var/www/html

# ✅ 8. Expose port cho Render
EXPOSE 10000
ENV PORT=10000

# ✅ 9. Sửa Apache để lắng nghe đúng cổng PORT Render cấp
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf && \
    sed -i 's/:80/:${PORT}/' /etc/apache2/sites-enabled/000-default.conf

# ✅ 10. Chạy Apache
CMD ["apache2-foreground"]
