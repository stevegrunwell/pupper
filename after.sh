#!/bin/bash

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.
#
# If you have user-specific configurations you would like
# to apply, you may also create user-customizations.sh,
# which will be run after this script.

# When a user connects via SSH, start in the project directory.
grep -Fq "cd ~/code" ~/.bashrc || echo -e "\\n# Start in the code/ directory\\ncd ~/code" >> ~/.bashrc
cd ~/code || exit 1

# Install npm dependencies and build site scripts/styles.
npm install
npm run dev

# Run database migrations
php artisan migrate
