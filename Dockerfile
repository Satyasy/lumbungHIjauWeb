#Import Image from Docker repository
FROM php:8.3-fpm

#Set Working Directory
WORKDIR /var/www

#Install System Dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    redis-server \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# #Install PHP extentions
# RUN apt-get clean && rm -rf /var/lib/apt/lists/*

#Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

#Install Nodejs dan npm
RUN apt-get install -y  npm && apt-get install -y nodejs

#Copy all the existing application directory contents to the working directory
COPY . /var/www/

#Copy the application directory permission to the working directory
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

#Change current user to www
USER wwww-data

#Install Php composer dependencies
RUN composer install --no-dev --optimize-autoloader

#install NPM dependencies and build assets
RUN npm install && npm run build

# Create necessary directories
RUN mkdir -p /var/log/supervisor /var/log/nginx /var/log/redis

#Copy Nginx Configuration
COPY docker/nginx/admin.conf /etc/nginx/sites-available/admin.conf

# Copy supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

#Copy redis configuration
COPY docker/redis/redis.conf /etc/redis/redis.conf

#Expose port 9000 to start php-fpm server adn 80 to http
EXPOSE 9000 80 6379
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]