#!/bin/sh
rm -rf /var/www/html/office
rm -rf /var/www/html/info
rm -rf /var/www/html/leavepermit
rm -rf /var/www/html/live
rm -rf /var/www/html/itsupport
#
ln -s /home/triasnet/public_html/ /var/www/html/office
ln -s /home/triasnet/public_html/info /var/www/html/info  
ln -s /home/triasnet/public_html/leavepermit /var/www/html/leavepermit
ln -s /home/triasnet/public_html/live/photo /var/www/html/live
ln -s /home/triasnet/public_html/itsupport /var/www/html/itsupport 
