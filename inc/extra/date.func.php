<?php

function format_date($date_str) {
    $date = new DateTime($date_str);
    $now = new DateTime();
    $diff = $now->diff($date);
    if ($diff->y > 0) {
        return $diff->y . " year" . ($diff->y == 1 ? "" : "s") . " ago";
    } elseif ($diff->m > 0) {
        return $diff->m . " month" . ($diff->m == 1 ? "" : "s") . " ago";
    } elseif ($diff->d > 13) {
        return floor($diff->d / 7) . " week" . (floor($diff->d / 7) == 1 ? "" : "s") . " ago";
    } elseif ($diff->d > 0) {
        return $diff->d . " day" . ($diff->d == 1 ? "" : "s") . " ago";
    } elseif ($diff->h > 0) {
        return $diff->h . " hour" . ($diff->h == 1 ? "" : "s") . " ago";
    } else {
        return $diff->i . " minute" . ($diff->i == 1 ? "" : "s") . " ago";
    }
}
