FROM debian:jessie-slim
MAINTAINER Stephan Hochdoerfer <S.Hochdoerfer@bitExpert.de>

RUN apt-get update -q && \
    DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends ssh git curl apt-transport-https ca-certificates && \
    apt-get clean

RUN curl -L https://github.com/Yelp/dumb-init/releases/download/v1.2.0/dumb-init_1.2.0_amd64.deb > dumb-init.deb && \
    dpkg -i dumb-init.deb && \
    rm -rf dumb-init.deb

RUN echo "deb https://packages.sury.org/php/ jessie main" > /etc/apt/sources.list.d/php.list && \
    curl https://packages.sury.org/php/apt.gpg | apt-key add - && \
    apt-get update -q && \
    DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends \
        php7.1-cli \
        php7.1-common \
        php7.1-curl \
        php7.1-intl \
        php7.1-json \
        php7.1-mbstring \
        php7.1-bcmath \
        php7.1-mcrypt \
        apache2 \
        libapache2-mod-php7.1 && \
        apt-get clean autoclean

RUN curl https://getcomposer.org/download/1.3.1/composer.phar > /usr/local/bin/composer.phar && \
    chmod +x /usr/local/bin/composer.phar

COPY docker/apache2/run-apache2.sh /usr/local/sbin/run-apache2.sh

COPY docker/apache2/000-default.conf /etc/apache2/sites-available/000-default.conf

ENTRYPOINT ["/usr/bin/dumb-init", "--"]

CMD ["/usr/local/sbin/run-apache2.sh"]
