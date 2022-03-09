#!/bin/bash

set -e

#     _
#    / \    Disclaimer!
#   / ! \   Please read this before continuing.
#  /_____\  Thanks ☺ ♥
#
# This is the deploy script used in production.
# It does plenty tasks:
#  * Run scripts that are mandatory after a deploy.
#  * Update RELEASE_VERSION and RELEASE_DATE environment vars,
#  * Save the values in env files for CLI and webserver.
#  * Send by email the analyzed changelog (which might not be 100% correct, but it's at least a changelog).

# bin/ directory
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Project directory
cd ${DIR}/../

DIR="$(pwd)"

echo "Working directory: ${DIR}"

ENV_FILE="${DIR}/.env.local.php"
CHANGELOG_FILE=${DIR}/../_tmp_changelog.txt

LAST_VERSION=$(php -r "echo (require '${ENV_FILE}')['RELEASE_VERSION'];" | sed -r 's/[^0-9]+//g')
NEW_VERSION=$(expr ${LAST_VERSION} + 1)
LAST_DATE=$(php -r "echo (require '${ENV_FILE}')['RELEASE_DATE'];" | sed -r 's/^.*="?([^"]+)"?$/\1/g')
NEW_DATE=$(date --rfc-3339=seconds)

echo "[DEPLOY] > Current version: ${LAST_VERSION}"
echo "[DEPLOY] > Last build date: ${LAST_DATE}"

echo "[DEPLOY] > Update repository branch"

git fetch --all --prune

CHANGELOG=$(git changelog v${LAST_VERSION}...origin/main | sed 1d)
CHANGELOG_SIZE=$(echo "${CHANGELOG}" | wc -l)
CHANGELOG_SIZE_CHARS=$(echo "${CHANGELOG}" | wc -m)
if [ "${CHANGELOG_SIZE_CHARS}" -lt "1" ]; then
    echo "[DEPLOY] > ${CHANGELOG}"
    echo "[DEPLOY] > No new commit! Terminating..."
    exit 1
else
    echo "[DEPLOY] > Retrieved $((CHANGELOG_SIZE)) commits(s) in changelog:"
    echo "[DEPLOY] > ${CHANGELOG}"
fi

# Just a safety because cross-platform isn't something in NodeJS...
git checkout yarn.lock

echo "[DEPLOY] > Applying these commits..."
git merge origin/main

echo "[DEPLOY] > Done!"

if [[ -f "${DIR}/../pre_deploy.bash" ]]
then
    echo "[DEPLOY] > Executing pre-deploy scripts"
    bash ../pre_deploy.bash
fi

echo "[DEPLOY] > Executing scripts..."
echo "[DEPLOY] > "

#
# These scripts are "wrapped" because they might have been updated between deploys.
# Only this "deploy.bash" script can't be updated, because it's executed on deploy.
# But having the scripts executed like this is a nice opportunity to update the scripts between deploys.
#
bash ./bin/deploy_scripts.bash

echo "[DEPLOY] > Done!"
echo "[DEPLOY] > Now updating environment vars..."
echo "[DEPLOY] > New version: ${NEW_VERSION}"
echo "[DEPLOY] > New build date: ${NEW_DATE}"

sed -i -e "s/RELEASE_VERSION=.*/RELEASE_VERSION=\"v${NEW_VERSION}\"/g" ${ENV_FILE}
sed -i -e "s/RELEASE_DATE=.*/RELEASE_DATE=\"${NEW_DATE}\"/g" ${ENV_FILE}

php -r "require '${DIR}/vendor/autoload.php';\$env=(require '${ENV_FILE}');\$env['RELEASE_VERSION'] = 'v${NEW_VERSION}'; \$env['RELEASE_DATE'] = '${NEW_DATE}'; file_put_contents('${ENV_FILE}', \"<?php\n\nreturn \".\Symfony\Component\VarExporter\VarExporter::export(\$env).\";\n\");"

echo "[DEPLOY] > Now generating changelogs..."

echo "" > ${CHANGELOG_FILE}

echo "New version: v${NEW_VERSION}"    >> ${CHANGELOG_FILE}
echo "Released on: ${NEW_DATE}"        >> ${CHANGELOG_FILE}
echo ""                                >> ${CHANGELOG_FILE}
echo "List of all changes/commits:"    >> ${CHANGELOG_FILE}
echo "${CHANGELOG}"                    >> ${CHANGELOG_FILE}
echo ""                                >> ${CHANGELOG_FILE}

echo "[DEPLOY] > FULL CHANGELOG"
cat ${CHANGELOG_FILE}

if [[ -f "${DIR}/../post_deploy.bash" ]]
then
    echo "[DEPLOY] > Executing post-deploy scripts"
    bash ../post_deploy.bash ${NEW_VERSION} ${CHANGELOG_FILE}
fi

echo "[DEPLOY] > Tagging release..."
echo "[DEPLOY] > Pushing it to Git..."

git tag -s -F ${CHANGELOG_FILE} "v${NEW_VERSION}"
git push origin "v${NEW_VERSION}"

mv ${CHANGELOG_FILE} "${DIR}/../changelogs/${NEW_VERSION}_${NEW_DATE}.log"

echo "[DEPLOY] > Done!"
echo "[DEPLOY] > Deploy finished!"
