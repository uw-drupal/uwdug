#!/bin/bash

DOMAIN='https://depts.washington.edu/uwdrupal'

echo "Configure file system..."
drush variable-set --exact file_temporary_path /tmp

echo "Disable caching and asset compression..."
drush variable-set --exact cache 0
drush variable-set --exact block_cache 0
drush variable-set --exact preprocess_css 0
drush variable-set --exact preprocess_js 0
drush variable-set --exact jquery_update_compression_type 'none'

echo "Setting up Stage File Proxy module to request images from the server..."
drush pm-enable --yes stage_file_proxy
drush variable-set stage_file_proxy_origin "$DOMAIN"
drush variable-set stage_file_proxy_origin_dir "sites/default/files"
drush variable-set stage_file_proxy_hotlink 1
drush variable-set stage_file_proxy_use_imagecache_root 0
drush variable-set preprocess_css 0

echo "Disabling other production-only modules"
drush pm-disable --yes googleanalytics

drush cache-clear all

echo -e "\n\n\033[0;32mDon't forget the subdirectory path!\033[0m Access the running environment at \033[0;32mhttp://uwdug.lndo.site/uwdrupal\033[0m"
