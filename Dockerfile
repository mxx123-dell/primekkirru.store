# Sử dụng image PHP có sẵn Apache
FROM php:8.2-apache

# Cài đặt và bật mod_rewrite (cho phép .htaccess hoạt động)
RUN a2enmod rewrite

# Cấu hình Apache để cho phép Override từ .htaccess
RUN echo '<Directory /var/www/html/>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/override.conf \
    && a2enconf override

# Copy toàn bộ code vào thư mục web mặc định
COPY . /var/www/html/

# Phân quyền (tránh lỗi 500 hoặc forbidden)
RUN chown -R www-data:www-data /var/www/html

# Expose port cho Render
EXPOSE 10000
ENV PORT=10000

# Sửa Apache để lắng nghe đúng cổng PORT mà Render cấp
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

# Chạy Apache
CMD ["apache2-foreground"]
