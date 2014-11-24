#!/bin/bash

# Prepare puppet dir
PUPPET_DIR=/etc/puppet/
if [[ ! -d "$PUPPET_DIR" ]]; then
    mkdir -p "$PUPPET_DIR"
fi
cp -rf "/vagrant/.vagrant/puppet/Puppetfile" "$PUPPET_DIR"

# Run aptidude update only once
echo 'Running aptitude update...'
apt-get update > /dev/null

# Install git if not present
$(which git > /dev/null 2>&1)
FOUND_GIT=$?
if [ "$FOUND_GIT" -ne '0' ]; then
    echo 'Installing git...'
    apt-get -q -y install git-core > /dev/null
fi

# Install required packages for librarian
if [[ ! -f "${PUPPET_DIR}required-packages-installed" ]]; then
    echo 'Installing required packages for librarian...'
    apt-get install -y build-essential ruby-dev > /dev/null
    touch "${PUPPET_DIR}required-packages-installed"
fi

# Install librarian
if [[ ! -f "${PUPPET_DIR}librarian-puppet-installed" ]]; then
    echo 'Installing librarian-puppet...'
    gem install librarian-puppet > /dev/null
    touch "${PUPPET_DIR}librarian-puppet-installed"
fi

# Run librarian
echo 'Running librarian...'
cd "$PUPPET_DIR" && librarian-puppet install --clean
echo 'Finished running librarian.'
