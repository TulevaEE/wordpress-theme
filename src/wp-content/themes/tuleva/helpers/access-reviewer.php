<?php

/*
 * A role that can list users and nothing else, for the read-only collector of the access
 * review (the /ligipaasud skill in the tuleva repo). The REST API returns users with their
 * roles (/wp/v2/users?context=edit) to anyone who can list_users; this role cannot edit
 * posts, settings or users, so its application password is not an admin key.
 *
 * add_role stores the role in the options table, so it is added once and left alone after.
 */
function tuleva_register_access_reviewer_role()
{
    if (get_role('access_reviewer') === null) {
        add_role('access_reviewer', 'Access reviewer', ['read' => true, 'list_users' => true]);
    }
}

if (function_exists('add_action')) {
    add_action('init', 'tuleva_register_access_reviewer_role');
}
