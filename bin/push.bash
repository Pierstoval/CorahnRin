#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cd ${DIR}/../

# These vars must be set in the dev environment for the project to be deployable.
ssh_remote=${CORAHN_RIN_DEPLOY_REMOTE}
prod_dir=${CORAHN_RIN_DEPLOY_DIR}

if [ -z $ssh_remote ]; then
    echo "Please set up the CORAHN_RIN_DEPLOY_REMOTE environment variable"
    exit 1
fi

if [ -z $prod_dir ]; then
    echo "Please set up the CORAHN_RIN_DEPLOY_DIR environment variable"
    exit 1
fi

git push origin main && ssh ${ssh_remote} ${prod_dir}/bin/deploy.bash

git fetch --all --prune
