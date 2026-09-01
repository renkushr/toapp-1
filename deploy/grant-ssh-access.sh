#!/usr/bin/env bash
# Run this ONCE inside DigitalOcean Droplet Console (Access → Launch Droplet Console)
# to allow deploy from your Mac via SSH key.

set -euo pipefail

PUBKEY='ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIGFDS9hOj0ch0vcbxcttYy/ojcjDKVsrVL54kMMHcvLe ren@Rens-MacBook-Pro-2.local'

mkdir -p /root/.ssh
chmod 700 /root/.ssh
touch /root/.ssh/authorized_keys
chmod 600 /root/.ssh/authorized_keys

if ! grep -qF "${PUBKEY}" /root/.ssh/authorized_keys; then
  echo "${PUBKEY}" >> /root/.ssh/authorized_keys
  echo "SSH key added."
else
  echo "SSH key already present."
fi

echo "Done. You can now SSH as root@159.89.201.139 from your Mac."
