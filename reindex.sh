#!/bin/bash
nohup php reindex.php > /www/libis/web/lias_html/collectiveaccess/cag_media/reindex_ca_cag.log 2> /www/libis/web/lias_html/collectiveaccess/cag_media/reindex_ca_cag_error.txt < /dev/null &
