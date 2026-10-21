# ==========================================
# Stage 1: Build the Vue/Frontend Assets
# ==========================================
FROM node:20-alpine AS frontend

WORKDIR /app

# Copy only package files first to leverage Docker cache
COPY package*.json ./
RUN npm install

# Copy the rest of the frontend source code
COPY resources/ ./resources/
COPY vite.config.js ./
COPY public/ ./public/
COPY tailwind.config.js* ./
COPY postcss.config.js* ./

# Build the production assets (creates public/build/manifest.json)
RUN npm run build


# ==========================================
# Stage 2: Final PHP + Nginx Environment
# ==========================================
FROM richarvey/nginx-php-fpm:3.1.6

# Copy the entire application code
COPY . .

# Copy the built Vue assets from Stage 1 into the PHP environment
COPY --from=frontend /app/public/build ./public/build

# Render-specific environment variables for this image
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]