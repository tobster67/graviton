Graviton Application based on Symfony Standard Edition
======================================================

The Symfony Standard Edition with a slightly adapted Kernel

The Graviton Bundles are a linked into src/Graviton as a git submodule.

They originally reside here:
https://github.com/tobster67/graviton-core.git

## Install

Clone this,

type composer install

follow the instructions

the Dynamic Bundles will be generated in src/GravitonDyn

## Usage

### Start the REST-Service
php app/console server:start 0.0.0.0:8000

### Stop the REST-Service
php app/console server:stop 0.0.0.0:8000

### (Re)generate the Dynamic Bundles

delete them
rm -rf src/GravitonDyn

regenerate them
php app/console graviton:generate:dynamicbundles --srcDir=src/

clear the cache with a regular symfony command
php app/console cache:clear

### Generate the swagger.json

php app/console graviton:swagger:generate
