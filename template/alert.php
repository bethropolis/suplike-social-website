<?php

if (isset($_GET['error'])) {
    echo '<div class="alert alert-danger text-center" role="alert">';

    switch ($_GET['error']) {
        case 'notset':
            echo '<h5>the database has not been configured <a href="./inc/setup/"><button class="btn mx-2 btn-info">setup</button> </a></h5> ';
            break;
        case 'emptyfields':
            echo '<h5 > enter input on all required fields</h5>';
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
        case 'postoff':
            echo '<h5>Posting has been disabled by admin</h5>';
            break;
        case 'invaliduid':
            echo '<h5>The username you entered is invalid</h5>';
            break;;
        case 'invalidmail':
            echo '<h5>It looks like the email you entered is not a valid email address</h5>';
            break;
        case 'emailtaken':
            echo '<h5>The email you entered is already associated with an account.</h5>';
            break;
        case 'signupoff':
            echo '<h5>Signing up for a new account is currently not possible due to admin restrictions</h5>';
            break;
        case 'usertaken':
            echo '<h5>It looks like that username is already being used by another account.</h5>';
            break;
        default:
            echo '<h5>an error occured</h5>';
            break;
    }

    echo '</div>';
}
