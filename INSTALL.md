# Installation

Clone the repo to the right location on the target server:

<pre>
ssh test.ctsi.ufl.edu
mv /var/www/vivo /var/www/vivo_backup

# clone the latest version of the code:
git clone https://github.com/ctsit/wordpress-vivo-card.git /var/www/vivo

# or download a specific version archive file:
wget https://github.com/ctsit/wordpress-vivo-card/archive/0.0.2.tar.gz
tar xvzf 0.0.2.tar.gz
mv wordpress-vivo-card /var/www/vivo
</pre>

Create a soft link so wordpress can access the code:

<pre>
ln -s /var/www/vivo /var/www/portal/wp-content/themes/UFandShands/vivo
</pre>

Test if the page is loading:

<pre>
curl -ks http://test.ctsi.ufl.edu/about/management/clinical-research-unit-directors/ | grep -i vivoName | head -1
</pre>


# Wordpress Integration


Edit the wordpress page to include function calls like the following:

<pre>
[vivo]http://vivo.ufl.edu/individual/n23877[/vivo]<!-- Batich, Chris -->
[vivo]http://vivo.ufl.edu/individual/n106202[/vivo]<!-- Beltz, Susan -->
[vivo]http://vivo.ufl.edu/individual/n29435[/vivo]<!-- Brantly, Mark -->
</pre>

This in turn makes a call to functions.php which is stored in
`/var/www/portal/wp-content/themes/UFandShands/functions.php`

Within `functions.php` you have to have the following function:

```php

function getvivoperson($atts, $content = null) {
    ob_start();
    getVIVOPersonData($content);
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}
add_shortcode('vivo', 'getvivoperson');
```

The call to `getVivoPersonData()` passes the URI to
`/var/www/portal/wp-content/themes/UFandShands/vivo/vivo.php`

This is then processed by the function in the `vivo_card.php` class 
which run the sparql query and parses the return data.
