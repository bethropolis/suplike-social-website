<?php
// Get the current page name with parameters
$page = $_SERVER['REQUEST_URI'];

// Parse the url and get only the path
$page = parse_url($page, PHP_URL_PATH);

// Get only the last part of the path
$page = basename($page);

// Initialize all the variables to an empty string
$home_active = '';
$profile_active = '';
$social_active = '';
$notification_active = '';
$topics_active = '';
$search_active = '';

// Check which side nav item should be active
switch ($page) {
    case 'home':
        $home_active = 'sidebar-active';
        break;
    case 'profile':
        $profile_active = 'sidebar-active';
        break;
    case 'social':
        $social_active = 'sidebar-active';
        break;
    case 'notification':
        $notification_active = 'sidebar-active';
        break;
    case 'topics':
        $topics_active = 'sidebar-active';
        break;
    case 'search':
        $search_active = 'sidebar-active';
        break;
    default:
        // Do nothing
}

?>

<div class="sidebar flex order-column sticky-top sticky-nav">
    <a href="home" class="<?php echo $home_active; ?>"><i class="fa fa-home"></i> <span> Home</span></a>
    <a href="profile" class="<?php echo $profile_active; ?>"><i class="fa fa-user"></i> <span> Profile</span></a>
    <a href="social" class="<?php echo $social_active; ?>"><i class="fa fa-users" title="Following"></i> <span>
            Following</span></a>
    <a href="topics" class="<?php echo $topics_active; ?>"><i class="fa fa-newspaper" title="Topics"></i> <span>
            Topics</span></a>
    <a href="notification" class="<?php echo $notification_active; ?>"><i class="fa fa-bell" title="Notifications"></i> <span> Notifications</span></a>
    <a href="search" class="<?php echo $search_active; ?>"><i class="fa fa-search" title="Search for Users or Posts"></i> <span> Search</span></a>

</div>