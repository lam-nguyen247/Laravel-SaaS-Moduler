# Sử dụng ảnh Nginx cùng phiên bản bạn muốn
FROM nginx:alpine

# Thư mục làm việc trong container
WORKDIR /var/www/html

COPY ./backend .

# Expose cổng mà Nginx sẽ chạy (mặc định là 80)
EXPOSE 80

# Chạy Nginx
CMD ["nginx", "-g", "daemon off;"]