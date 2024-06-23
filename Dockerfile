FROM php:8.3-fpm

# Install Nginx
RUN apt-get update && apt-get install -y nginx libssl1.0-dev

# Remove the default Nginx configuration
RUN rm /etc/nginx/nginx.conf

# Copy your custom Nginx configuration file to the container
COPY nginx.conf /etc/nginx/nginx.conf

# Copy your PHP code into the Docker image
COPY . /var/www/html/

# Expose port 80
EXPOSE 80

# Start Nginx and PHP-FPM
CMD service php7.4-fpm start && nginx -g 'daemon off;'