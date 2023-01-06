FROM php:8.1.13-apache
ENV TZ=America/Sao_Paulo
#baixar o script pelo powershell: 
# Invoke-WebRequest -Uri https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -OutFile scripts/install-php-extensions
COPY scripts/install-php-extensions /usr/local/bin/
RUN install-php-extensions intl
RUN a2enmod rewrite
CMD ["apache2-foreground"]