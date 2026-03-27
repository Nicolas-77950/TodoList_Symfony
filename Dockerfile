FROM dunglas/frankenphp:1.4-php8.4

# On installe les extensions nécessaires pour PostgreSQL
RUN install-php-extensions pdo_pgsql pgsql