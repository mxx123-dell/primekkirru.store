# Sử dụng image PHP có sẵn Apache
FROM php:8.2-apache

# Copy toàn bộ code vào thư mục web mặc định
COPY . /var/www/html/

# Mở port cho Render
EXPOSE 10000

# Đặt biến môi trường PORT (Render sẽ tự thay)
ENV PORT=10000

# Chạy Apache ở foreground
CMD ["apache2-foreground"]
