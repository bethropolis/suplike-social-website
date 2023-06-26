<?php
function saveSvgImage($searchTerm)
{
    global $error;
    // Get the SVG from the cache or the API
    $dir = "../../../img/";
    $filename = "{$searchTerm}.svg";
    $filepath = $dir . $filename;

    if (file_exists($filepath)) {
        return $filename;
    }

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.dicebear.com/5.x/adventurer/svg?seed={$searchTerm}&backgroundColor=c0aede",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Accept: image/svg+xml"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        $error->err("API", 26, $err);
    }

    // Save the SVG to the cache and return the filename
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($filepath, $response);
    return $filename;
}
