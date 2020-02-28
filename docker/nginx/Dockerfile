FROM nginx:1.17-alpine as scratch

ARG ENVIRONMENT
ENV ENVIRONMENT=${ENVIRONMENT}

# nginx
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default_${ENVIRONMENT}.conf /etc/nginx/conf.d/default.conf

# ENTRYPOINT ["dumb-init", "--rewrite", "15:3"]
CMD ["nginx", "-g", "daemon off;"]

# ADD docker/nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/html

# DEV env (local)
FROM scratch as development

# PROD env (kubernetes)
FROM scratch as production