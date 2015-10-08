# About

This folder stores php code used to communicate
with Vivo in order to generate "business cards"
displayed by the wordpress.

Examples:

* http://www.ctsi.ufl.edu/about/management/clinical-research-unit-directors/
* http://www.ctsi.ufl.edu/about/management/uf-ctsi-leadership/
* http://www.ctsi.ufl.edu/about/management/core-lab-directors/
* http://www.ctsi.ufl.edu/about/management/clinical-research-unit-directors/

* http://test.ctsi.ufl.edu/about/management/clinical-research-unit-directors/

# Deployment

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

Unvestigate if it would be feasible to put the entire wordpress content in a
forge repository.
