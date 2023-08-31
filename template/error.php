<?php

$error = http_response_code();

switch ($error) {
    case 404:
        echo '404!';
        break;
    case 500:
        echo '500!';
        break;
}
