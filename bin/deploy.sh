#!/usr/bin/env bash
#
# CD script for performing atomic deployments.
#
# Usage:
#   deploy.sh <environment>
#
# Expected environment variables. If any of these should be modified based on environment,
# please assign them to variables within the `case` block.
#
# - SSH_PRIVATE_KEY: The private SSH key, stored in the runner configuration.
# - TARGET_USER:     The SSH user for the deployment.
#
# The following environment variables should be set for each target environment:
#
# - {ENV}_SERVER_ADDR:        The IP address(es) for the target servers.
#                             Separate multiple addresses with commas.
# - {ENV}_TARGET_DIR:         The system path of the target directory on each target server.
#
# shellcheck disable=SC2029

# Print the usage instructions.
function print_usage {
    echo $"Usage: $0 {development|staging|production}"
    exit 1
}

# Parse one or more target server out of a comma-separated list.
function read_target_servers {
    IFS=',' read -ra TARGET_SERVERS <<< "$1"
}

if [ ! $# -eq 1 ]; then
    echo -e "\\033[0;31mAn environment name must be passed to the script.\\033[0;0m"
    print_usage
fi

# Set local variables
TIMESTAMP="$(date +"%s")"

# Customize the behavior based on the passed environment
case "$1" in
    development)
        read_target_servers "$DEVELOPMENT_SERVER_ADDR"
        TARGET_DIR="$DEVELOPMENT_TARGET_DIR"
        ;;
    staging)
        read_target_servers "$STAGING_SERVER_ADDR"
        TARGET_DIR="$STAGING_TARGET_DIR"
        ;;
    production)
        read_target_servers "$PRODUCTION_SERVER_ADDR"
        TARGET_DIR="$PRODUCTION_TARGET_DIR"
        ;;
    *)
        echo -e "\\033[0;31mThe \"$1\" environment is undefined.\\033[0;0m"
        print_usage
esac

# Load the SSH private key.
eval $(ssh-agent -s)
echo "$SSH_PRIVATE_KEY" | tr -d '\r' | ssh-add - > /dev/null

# Make sure the necessary SSH files are in place.
mkdir -p -m 700 ~/.ssh
touch ~/.ssh/known_hosts
chmod 644 ~/.ssh/known_hosts

# Remove unnecessary components and archive the app
composer install --no-dev --optimize-autoloader --no-suggest --prefer-dist --no-ansi --no-interaction --no-progress --no-scripts
npm run production --no-progress
rm -rf .env after.sh aliases log node_modules phpunit.* storage tests tmp
tar --exclude-vcs -czf "${TIMESTAMP}.tgz" -- *

# Iterate through the target servers, deploying to each one.
for TARGET_SERVER in "${TARGET_SERVERS[@]}"; do
    printf "\n\\033[0;36mDeploying to %s\\033[0;0m" "$TARGET_SERVER"

    # Ensure the runner can SSH into the target server.
    ssh-keyscan "$TARGET_SERVER" 2> /dev/null >> ~/.ssh/known_hosts

    # Deploy to the target directory.
    printf "\n\n\\033[0;36mDirectory: %s\\033[0;0m\n\n" "$TARGET_DIR"

    # Copy the tarball to the target server + directory.
    scp "${TIMESTAMP}.tgz" "${TARGET_USER}@${TARGET_SERVER}:${TARGET_DIR}"

    # Extract the archive and set up symlinks. The process, in order:
    #
    # 1.  Move into the DIR directory
    # 2.  Create a new directory within releases/ for the new release
    # 3.  Extract the tarball into the new directory
    # 4.  Remove the tarball
    # 5.  Symlink the .env file from shared/
    # 6.  Symlink the storage/ directory from shared/
    # 7.  Run any new database migrations
    # 8.  Flatten the Laravel configuration files
    # 9.  Update the current/ symlink to point to the new release
    # 10. Clear existing Laravel view cache
    # 11. Reload nginx to pick up on the new web root. At this point, the release is live.
    #     Note that this requires the $TARGET_USER to be able to restart nginx without logging
    #     in as root. See https://stackoverflow.com/a/45071759/329911.
    # 12. Clear PHP OpCache
    # 13. Roll off old releases. See https://engineering.growella.com/jenkins-digital-ocean/
    ssh "${TARGET_USER}@${TARGET_SERVER}" "cd \"$TARGET_DIR\" \
        && echo -en \"Extracting tarball to releases/${TIMESTAMP}...\" \
        && mkdir -p \"releases/${TIMESTAMP}\" \
        && tar -xzf \"${TIMESTAMP}.tgz\" --directory \"releases/${TIMESTAMP}\" \
        && rm \"${TIMESTAMP}.tgz\" \
        && echo -e \"\\033[0;32mOK\\033[0;0m\" \
        && echo -en \"Symlinking shared resources...\" \
        && mkdir -p shared/storage/framework/cache shared/storage/framework/sessions shared/storage/framework/views \
        && ln -s \"${TARGET_DIR}/shared/.env\" \"${TARGET_DIR}/releases/${TIMESTAMP}/.env\" \
        && ln -s \"${TARGET_DIR}/shared/storage\" \"${TARGET_DIR}/releases/${TIMESTAMP}/storage\" \
        && echo -e \"\\033[0;32mOK\\033[0;0m\" \
        && php \"releases/${TIMESTAMP}/artisan\" migrate --force \
        && php \"releases/${TIMESTAMP}/artisan\" config:cache \
        && php \"releases/${TIMESTAMP}/artisan\" view:clear \
        && echo -en \"Updating current symlink...\" \
        && ln -sfrn \"${TARGET_DIR}/releases/${TIMESTAMP}\" \"${TARGET_DIR}/current\" \
        && sudo /bin/systemctl restart nginx.service \
        && echo -e \"\\033[0;32mOK\\033[0;0m\" \
        && echo -e \"Clearing PHP OpCache...\" \
        && php \"${TARGET_DIR}/current/artisan\" opcache:clear \
        && echo -en \"Rolling off old releases...\" \
        && cd releases && ls -1 | sort -r | tail -n +6 | xargs rm -rf \
        && echo -e \"\\033[0;32mOK\\033[0;0m\" \
        && printf \"\n\\033[0;32mDeployment to %s completed successfully\\033[0;0m\" \"${TARGET_DIR}\" \
        " || exit 1
done

# Remove the local tarball.
rm "${TIMESTAMP}.tgz"
