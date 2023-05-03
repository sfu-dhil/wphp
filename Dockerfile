FROM node:19.5 AS wphp-webpack
WORKDIR /app

RUN apt-get update \
    && apt-get install -y git \
    && npm upgrade -g npm \
    && npm upgrade -g yarn \
    && rm -rf /var/lib/apt/lists/*

# build js deps
COPY public/package.json public/yarn.lock public/webpack.config.js /app/
RUN yarn

# run webpack
COPY public /app
RUN yarn webpack


FROM wphp-webpack AS wphp-prod-assets

RUN yarn --production \
    && yarn cache clean


FROM dhilsfu/symfony-base:php-8.2-apache AS wphp

# basic deps installer (no script/plugings)
COPY composer.json composer.lock /var/www/html/
RUN composer install --no-scripts

# copy project files and install all symfony deps
COPY . /var/www/html
RUN composer install

# copy webpacked js and libs
COPY --from=wphp-prod-assets /app/js/dist /var/www/html/public/js/dist
COPY --from=wphp-prod-assets /app/css /var/www/html/public/css
COPY --from=wphp-prod-assets /app/node_modules /var/www/html/public/node_modules

CMD ["/docker-entrypoint.sh"]