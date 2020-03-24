#!/bin/sh
if [ ! -d ./var ]; then
  mkdir ./var
  mkdir ./var/cache
  mkdir ./var/cache/dev
  chmod 777 -Rf ./var
fi

