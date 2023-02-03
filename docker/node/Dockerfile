FROM node:14

ENV GOSU_VERSION=1.14 \
    RUN_USER="node"

COPY bin/entrypoint.sh /bin/entrypoint

ENTRYPOINT ["/bin/entrypoint"]

RUN npm -g i npm \
    && chmod a+x /bin/entrypoint \
    && mkdir -p /srv/node_modules \
    \
    && `# User management for entrypoint` \
    && curl -L -s -o /bin/gosu https://github.com/tianon/gosu/releases/download/${GOSU_VERSION}/gosu-$(dpkg --print-architecture | awk -F- '{ print $NF }') \
    && chmod +x /bin/gosu
