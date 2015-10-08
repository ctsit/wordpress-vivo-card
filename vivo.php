<?php
/*
 * Purpose: This file generates "business cards" by reading VIVO 
 * content returned via sparql queries.
 *
 * Note: this file is imported by
 *  /var/www/portal/wp-content/themes/UFandShands/header.php
 * and only one function is used in 
 *  /var/www/portal/wp-content/themes/UFandShands/functions.php
 *
Example of sparql query:
http://sparql.vivo.ufl.edu/VIVO/query?query=%0D%0APREFIX+vivo%3A+%3Chttp%3A%2F%2Fvivoweb.org%2Fontology%2Fcore%23%3E%0D%0APREFIX+ufVivo%3A+%3Chttp%3A%2F%2Fvivo.ufl.edu%2Fontology%2Fvivo-ufl%2F%3E%0D%0APREFIX+rdfs%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F2000%2F01%2Frdf-schema%23%3E%0D%0APREFIX+vitro%3A+%3Chttp%3A%2F%2Fvitro.mannlib.cornell.edu%2Fns%2Fvitro%2Fpublic%23%3E%0D%0APREFIX+vcard%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F2006%2Fvcard%2Fns%23%3E%0D%0APREFIX+obo%3A+%3Chttp%3A%2F%2Fpurl.obolibrary.org%2Fobo%2F%3E%0D%0ASELECT+%3Fcontact_info+%3Flname+%3Ffname+%3Femailaddr+%3Ftelephone+%3Ftitle_label+%3FdeptLabel+%3Fphoto%0D%0AWHERE+{%0D%0A++++++++%3Chttp%3A%2F%2Fvivo.ufl.edu%2Findividual%2Fn3705822167%3E+obo%3AARG_2000028+%3Fcontact_info+.%0D%0A++++++++%3Fcontact_info+a+vcard%3AIndividual+.%0D%0A++++++++%3Fcontact_info+vcard%3AhasName+%3Fname+.%0D%0A++++++++%3Fname+vcard%3AgivenName+%3Ffname+.%0D%0A++++++++%3Fname+vcard%3AfamilyName+%3Flname+.%0D%0A++++++++OPTIONAL+{%3Fcontact_info+vcard%3AhasEmail+%3Femail+.+}%0D%0A++++++++OPTIONAL+{%3Femail+vcard%3Aemail+%3Femailaddr+.+}%0D%0A++++++++OPTIONAL+{%3Fcontact_info+vcard%3AhasTelephone+%3Fphone+.+}%0D%0A++++++++OPTIONAL+{%3Fphone+vcard%3Atelephone+%3Ftelephone+.+}%0D%0A++++++++OPTIONAL+{%3Fcontact_info+vcard%3AhasTitle+%3Ftitle+.+}%0D%0A++++++++OPTIONAL+{%3Ftitle+vcard%3Atitle+%3Ftitle_label+.+}%0D%0A++++++++OPTIONAL+{%3Chttp%3A%2F%2Fvivo.ufl.edu%2Findividual%2Fn3705822167%3E+ufVivo%3AhomeDept+%3Fdept+.+%3Fdept+rdfs%3Alabel+%3FdeptLabel+.+}%0D%0A++++++++OPTIONAL+{%3Chttp%3A%2F%2Fvivo.ufl.edu%2Findividual%2Fn3705822167%3E+vitro%3AmainImage+%3Fimage.+%3Fimage+vitro%3AthumbnailImage+%3Fthumb+.+%3Fthumb+vitro%3AdownloadLocation+%3Fphoto}%0D%0A}+LIMIT+1&output=text&stylesheet=%2Fxml-to-html.xsl
*/

require_once 'vivo_card.php';

function getVIVOPersonData($search) {
    $identifier = VivoCard::get_person_id_from_url($search);
    $personURI = "http://vivo.ufl.edu/individual/" . $identifier;
    $data = VivoCard::get_card_data($personURI);
    $html = VivoCard::format_card($data);
    echo $html;
}

?>
