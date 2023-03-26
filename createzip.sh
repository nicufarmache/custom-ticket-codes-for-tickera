#!/bin/sh

rm -rf build
mkdir build
cd build
mkdir custom-ticket-codes-for-tickera
cp ../*.php custom-ticket-codes-for-tickera/
zip -r custom-ticket-codes-for-tickera.zip custom-ticket-codes-for-tickera
cd ..
