#!/usr/bin/env sh
#
# Inspired from https://github.com/xAPI-vle/moodle-logstore_xapi/blob/master/build.sh

# Creates a folder to zip.
rm -f graylog.zip
php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar install --no-interaction --no-dev
cp -r . ../moodle_logstore_build

# Removes unused files and folders.
find ../moodle_logstore_build -type d -name 'tests' | xargs rm -rf
find ../moodle_logstore_build -type d -name 'docs' | xargs rm -rf
find ../moodle_logstore_build -type d -name '.git' | xargs rm -rf
find ../moodle_logstore_build -type f -name '.gitignore' | xargs rm -rf
find ../moodle_logstore_build -type f -name 'composer.*' | xargs rm -rf
find ../moodle_logstore_build -type f -name 'phpunit.*' | xargs rm -rf
find ../moodle_logstore_build -type f -name '*.md' | xargs rm -rf
find ../moodle_logstore_build -type f -name '*.sh' | xargs rm -rf

# Creates the zip file.
mv ../moodle_logstore_build graylog
zip -r graylog.zip graylog -x "graylog/.git/**/*"
rm -rf graylog

# Updates Github.
git add graylog.zip
git commit -m "Build zip file"
git push
