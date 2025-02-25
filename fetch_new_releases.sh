#!/bin/bash

# URLs to fetch
URL1="http://45.159.230.7/fetch-new-releases-js"
URL2="http://45.159.230.7/fetch-spotify-new-releases"

# Fetch the URLs
curl -s $URL1 > /dev/null
curl -s $URL2 > /dev/null
