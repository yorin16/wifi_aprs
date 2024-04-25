# Start with the official Jenkins image
FROM jenkins/jenkins:lts

# Switch to root to install additional software
USER root

# Install necessary packages for PHP, Composer, npm, and Yarn
RUN apt-get update && apt-get install -y \
    apt-transport-https \
    lsb-release \
    ca-certificates \
    curl \
    gnupg \
    && curl -sSL https://packages.sury.org/php/apt.gpg | apt-key add - \
    && echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && apt-get update \
    && apt-get install -y \
        php8.3 \
        php8.3-cli \
        php8.3-common \
        php8.3-curl \
        php8.3-mbstring \
        php8.3-xml \
        php8.3-zip \
        nodejs \
        npm \
        yarn \
        sshpass \
        rsync \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony Encore
RUN npm install --global @symfony/webpack-encore

# Clean up the apt cache to reduce image size
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Change back to the jenkins user
USER jenkins

# Expose the default port for Jenkins and optional ports if necessary
EXPOSE 8080
EXPOSE 50000

# Jenkins base image already includes an ENTRYPOINT
