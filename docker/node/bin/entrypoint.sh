#!/bin/bash
set -e

SCRIPT_TITLE_PATTERN="\033[32m[%s]\033[0m %s\n"

uid=$(stat -c %u /srv)
gid=$(stat -c %g /srv)

sed -i -r "s/${RUN_USER}:x:\d+:\d+:/${RUN_USER}:x:$uid:$gid:/g" /etc/passwd
sed -i -r "s/${RUN_USER}:x:\d+:/${RUN_USER}:x:$gid:/g" /etc/group

chown -R ${RUN_USER}:${RUN_USER} /srv/package.json
chown -R ${RUN_USER}:${RUN_USER} /srv/public/build
[[ -d /srv/node_modules ]] && chown -R ${RUN_USER}:${RUN_USER} /srv/node_modules
[[ -f /srv/package-lock.json ]] && chown -R ${RUN_USER}:${RUN_USER} /srv/package-lock.json

if [ $# -eq 0 ]; then
    printf "${SCRIPT_TITLE_PATTERN}""Node" "Please run a command"
else
    exec gosu ${RUN_USER} "$@"
fi
