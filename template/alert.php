<?php

if (isset($_GET['error'])) {
    echo '<div class="alert alert-danger text-center" role="alert">';

    switch ($_GET['error']) {
        case 'notset':
            echo '<h5>the database has not been configured <a href="./inc/setup/"><button class="btn mx-2 btn-info">setup</button> </a></h5> ';
            break;
        case 'emptyfields':
            echo '<h5 > enter input on all fields</h5>';
            break;
        case 'sqlerror':
            echo '<h5>there is a server error. please contact admin</h5>';
            break;
        case 'disabled':
            echo '<h5>account has been disabled, contact admin.</h5>';
            break;
        case 'wrongpwd':
            echo '<h5>wrong password</h5>';
            break;
        case 'yrpost':
            echo '<h5>you cannot repost your own post</h5>';
            break;
        default:
        echo '<h5>an error occured</h5>';
            break;
    }

    echo '</div>';
}
