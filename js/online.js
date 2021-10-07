const _set_user = sessionStorage.getItem('user') || null;
if (_set_user != null) {
    $.post('./inc/online.inc.php', {
        user: _set_user
    });
}
