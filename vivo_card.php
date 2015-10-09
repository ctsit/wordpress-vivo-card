<?php

class VivoCard {
    const TYPE_PHONE = 'phone';
    const TYPE_FAX = 'fax';
    const TYPE_EMAIL = 'email';
    const TYPE_LINK = 'link';
    const TYPE_IMAGE = 'img';


    public static function format_div($id, $string, $type='') {
        // Generate a html div element with the content formated
        // according to the $type attribute
        $html = '';

        if (strlen($string) > 0) {
            if ('' == $type) {
                $html = '<div class="'. $id . '">' .$string.'</div>';
            }
            else if (self::TYPE_PHONE == $type) {
                $html = '<div class="'. $id . '">Phone: ' .$string.'</div>';
            }
            else if (self::TYPE_FAX == $type) {
                $html = '<div class="'. $id . '">Fax: ' .$string.'</div>';
            }
            else if (self::TYPE_EMAIL == $type) {
                $html = '<div class="'.$id.'"><a href="mailto:' .$string.'">'.$string.'</a></div>';
            }
            else if (self::TYPE_LINK == $type) {
                $html = '<div class="'.$id.'"><a href="'.$string.'">'.$string.'</a></div>';
            }
            else if (self::TYPE_IMAGE == $type) {
                $html = '<img class="'.$id.'" src="'.$string.'" alt="no image">';
            }
        }
        return $html;
    }

    public static function get_image_path_from_image_url($image_url) {
        $path = $image_url;
        $image_id = self::get_person_id_from_url($image_url);

        if (strlen($image_id) > 0) {
            // If the image was harvested, the URL will be different from uploaded
            // pictures, but it will have a string "ufid" in its name.
            if (strpos($path, "ufid") !== false) {
                $filename = substr_replace($path, "", 0, 4);
                $path = "http://vivo.ufl.edu/harvestedImages/thumbnails/thumbnail" . $filename;
            }
        }
        else {
            // If the image URL is empty show a placeholder
            $path = '/wp-content/themes/UFandShands/vivo/noimage.png';
        }
        return strip_tags($path);
    }

    public static function get_person_id_from_url($url) {
        // get the "ABC" fragment from an URL like "http://site.com/path/ABC/"
        // @return empty string if the url is invalid
        $url = rtrim($url, '/');
        $parsed_data = parse_url($url,  PHP_URL_PATH);
        $id = '';

        if (! is_null($parsed_data)) {
            $parts = explode('/', $parsed_data);
            $num_parts = count($parts);

            if ($num_parts) {
                $id = $parts[$num_parts - 1];
            }
        }
        return $id;
    }

    public static function get_card_data($personURI) {
        $curlInit = curl_init();
        $sparqlServer = "http://sparql.vivo.ufl.edu:3030/VIVO/query?query=";

        // The query is build to bring the attributes for a person, the "LIMIT 1" is set to get around duplicate results being return.
        $sparqlQuery = "
       PREFIX foaf: <http://xmlns.com/foaf/0.1/>
PREFIX vivo: <http://vivoweb.org/ontology/core#>
PREFIX ufVivo: <http://vivo.ufl.edu/ontology/vivo-ufl/>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX vitro: <http://vitro.mannlib.cornell.edu/ns/vitro/public#>
PREFIX vcard: <http://www.w3.org/2006/vcard/ns#>
PREFIX obo: <http://purl.obolibrary.org/obo/>

SELECT ?lname ?fname ?emailaddr ?telephone ?actual_fax_num ?title_label ?deptLabel ?photo
WHERE {
        <$personURI> obo:ARG_2000028 ?contact_info .
        ?contact_info vcard:hasName ?name .
        ?name vcard:givenName ?fname .
        ?name vcard:familyName ?lname .
        OPTIONAL {<$personURI> obo:ARG_2000028 ?contact_info .
                    ?contact_info vcard:hasEmail ?email .
                        ?email vcard:email ?emailaddr .  }
        OPTIONAL {?contact_info vcard:hasTelephone ?phone .
                    ?phone vcard:telephone ?telephone . }
        OPTIONAL {?contact_info vcard:hasTelephone ?fax_num .
                    ?fax_num a vcard:Fax .
                        ?fax_num vcard:telephone ?actual_fax_num}
        OPTIONAL {?contact_info vcard:hasTitle ?title .
                    ?title vcard:title ?title_label . }
        OPTIONAL {<$personURI> ufVivo:homeDept ?dept .
                    ?dept rdfs:label ?deptLabel . }
        OPTIONAL {<$personURI> vitro:mainImage ?image .
                    ?image vitro:thumbnailImage ?thumb .
                        ?thumb vitro:downloadLocation ?photo }
} LIMIT 1 ";

        // print $sparqlQuery;
        // Encode the query for sparql ready
        $query = urlencode($sparqlQuery);

        // Set the result set to json
        $outputFormat = '&output=json';

        // Build the query string in to include the sparql endpoint plus the query string and the result set format option.
        $fullURL = $sparqlServer . $query . $outputFormat;
        // print "$fullURL";

        // Initialize curl
        $curlInit = curl_init();

        // Set the URL to fetch to the fullURL.
        curl_setopt($curlInit, CURLOPT_URL, $fullURL);

        // Set the return to be a string of the return value. If false the return would be output.
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

        // Now actually set up the curl options for the session.
        $curlReturn = curl_exec($curlInit);

        // Set up the curl response to an Array
        $sparqlArray = json_decode($curlReturn, true);
        // print_r($sparqlArray);

        // Get the attributes
        $vivoLastName = $sparqlArray["results"]["bindings"][0]["lname"]["value"];
        $vivoFirstName = $sparqlArray["results"]["bindings"][0]["fname"]["value"];

        if ($vivoLastName != null && $vivoFirstName != null) {
            $vivoName = $vivoLastName . ", " . $vivoFirstName;
        }
        else {
            $vivoName = "";
        }

        $vivoTitle = isset($sparqlArray["results"]["bindings"][0]["title_label"])
            ? $sparqlArray["results"]["bindings"][0]["title_label"]["value"]
            : '';
        $vivoPhone = $sparqlArray["results"]["bindings"][0]["telephone"]["value"];
        $vivoEmail = $sparqlArray["results"]["bindings"][0]["emailaddr"]["value"];
        $vivoFax = isset($sparqlArray["results"]["bindings"][0]["actual_fax_num"])
            ? $sparqlArray["results"]["bindings"][0]["actual_fax_num"]["value"]
            : '';
        $vivoDepartment = $sparqlArray["results"]["bindings"][0]["deptLabel"]["value"];
        $image_url = isset($sparqlArray["results"]["bindings"][0]["photo"])
            ? $sparqlArray["results"]["bindings"][0]["photo"]["value"]
            : '';
        $vivoImage = self::get_image_path_from_image_url($image_url);

        $data = array(
            'vivoImage' => $vivoImage,
            'vivoName'  => $vivoName,
            'vivoTitle' => $vivoTitle,
            'vivoDepartment' => $vivoDepartment,
            'vivoPhone' => $vivoPhone,
            'vivoFax' => $vivoFax,
            'vivoEmail' => $vivoEmail,
            'vivoLink' => $personURI
        );

        return $data;
    }

    public static function format_card($data) {
        // Assemble multiple pieces of information specified by the $data
        // argument into a html "business card"
        $html = '<div id="vivoPerson">'
            . self::format_div("vivoImage", $data['vivoImage'], self::TYPE_IMAGE)
            . self::format_div("vivoName", $data['vivoName'])
            . self::format_div("vivoTitle", $data['vivoTitle'])
            . self::format_div("vivoDepartment", $data['vivoDepartment'])
            . self::format_div("vivoPhone", $data['vivoPhone'], self::TYPE_PHONE)
            . self::format_div("vivoFax", $data['vivoFax'], self::TYPE_FAX)
            . self::format_div("vivoEmail", $data['vivoEmail'], self::TYPE_EMAIL)
            . self::format_div("vivoLink", $data['vivoLink'], self::TYPE_LINK)
            . '</div>';
        return $html;
    }
}

