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
