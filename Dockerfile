# ===============================
# ⚡ Dockerfile cho PHP + Apache + PostgreSQL
# ===============================
FROM php:8.2-apache

# Cài extension PostgreSQL cho PHP
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Bật rewrite module
RUN a2enmod rewrite

# Cho phép .htaccess hoạt động
RUN echo '<Directory /var/www/html/>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/override.conf \
    && a2enconf override

# Copy mã nguồn vào container
COPY . /var/www/html/
WORKDIR /var/www/html/

# Quyền truy cập
RUN chown -R www-data:www-data /var/www/html

# Cổng chạy (Render yêu cầu 10000)
EXPOSE 10000
ENV PORT=10000

# Đổi cấu hình Apache sang cổng PORT
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf && \
    sed -i 's/:80/:${PORT}/' /etc/apache2/sites-enabled/000-default.conf

CMD ["apache2-foreground"]
