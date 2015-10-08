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

Process:

Inside the wp page there are calls that look like the following:

[vivo]http://vivo.ufl.edu/individual/n23877[/vivo]<!-- Batich, Chris -->
[vivo]http://vivo.ufl.edu/individual/n106202[/vivo]<!-- Beltz, Susan -->
[vivo]http://vivo.ufl.edu/individual/n29435[/vivo]<!-- Brantly, Mark -->

This in turn makes a call to functions.php which is stored in
/var/www/portal/wp-content/themes/UFandShands/functions.php

Within functions.php is the following function:

function getvivoperson($atts, $content = null) {
        ob_start();
        getVIVOPersonData($content);
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
        }
add_shortcode('vivo','getvivoperson');

The call to getVivoPersonData passes the URI to
/var/www/portal/wp-content/themes/UFandShands/vivo/vivo.php

This is then passed to vivo_card.php that runs the sparql query
and parses the return data.