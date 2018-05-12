#!/bin/bash
if [ "$1" = "" ]; then
  echo "Error: Please provide a certificate filename as parameter."
  exit
fi

echo "Generating a private SSL key and pem..."
openssl req -new -x509 -sha256 -newkey rsa:2048 -days 365 -nodes -out "${1}.pem" -keyout "${1}.key" -subj "/C=XX/ST=XX/L=XX/O=XX/OU=XX/CN=${1}"
chmod 600 "${1}.key"

echo "Generating a Certificate Signing Request..."
openssl req -new -sha256 -key "${1}.key" -out "${1}.csr" -subj "/C=XX/ST=XX/L=XX/O=XX/OU=XX/CN=${1}"
