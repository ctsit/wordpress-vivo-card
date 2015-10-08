# Installation

Archive and scp the tar.gz file to the target server:

<pre>
tar cvzf vc.tar.gz wordpress-vivo-card/
scp vc.tar.gz test.ctsi.ufl.edu:/home/asura/
ssh test.ctsi.ufl.edu
tar xvzf vc.tar.gz
mv wordpress-vivo-card /var/www/vivo
ln -s /var/www/vivo /var/www/portal/wp-content/themes/UFandShands/vivo
</pre>


# TODO

- add instructions on how to call the code from wordpress
