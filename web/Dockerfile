FROM nginx:alpine

COPY config/ /etc/nginx/
COPY start.sh /var/opt/

RUN addgroup prestashop && \
  adduser -s /sbin/nologin -HD -G prestashop web && \
  chmod +x /var/opt/start.sh

VOLUME /app
WORKDIR /app

CMD ["sh", "/var/opt/start.sh"]
