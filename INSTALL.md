# Installation

Archive and scp the tar.gz file to the target server:

<pre>
tar cvzf apache_ctsi.tar.gz apache_ctsi/
scp apache_ctsi.tar.gz test.ctsi.ufl.edu:/home/asura/
ssh test.ctsi.ufl.edu
tar xvzf apache_ctsi.tar.gz
mv apache_ctsi /var/www/vivo
ln -s /var/www/vivo /var/www/portal/wp-content/themes/UFandShands/vivo
</pre>


# TODO

- add instructions on how to call the code from wordpress
