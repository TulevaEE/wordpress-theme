<?php

/*
 * A role that can list users and nothing else, for the read-only collector of the access
 * review (the /ligipaasud skill in the tuleva repo). It cannot edit posts, settings or
 * users, so its application password is not an admin key.
 *
 * add_role stores the role in the options table, so it is added once and left alone after.
 */
function tuleva_register_access_reviewer_role()
{
    if (get_role('access_reviewer') === null) {
        add_role('access_reviewer', 'Access reviewer', ['read' => true, 'list_users' => true]);
    }
}

/*
 * The collector reads users here rather than at /wp/v2/users: the security plugins block
 * that route against username discovery whatever the caller's capabilities. This route
 * gives each user's login, e-mail and roles, and nothing else, to whoever can list_users.
 */
function tuleva_access_review_users(array $users)
{
    return array_map(fn($user) => [
        'login' => $user->user_login,
        'email' => $user->user_email,
        'roles' => array_values($user->roles),
    ], $users);
}

if (function_exists('add_action')) {
    add_action('init', 'tuleva_register_access_reviewer_role');
    add_action('rest_api_init', function () {
        register_rest_route('tuleva/v1', '/access-review/users', [
            'methods' => 'GET',
            'callback' => fn() => tuleva_access_review_users(get_users()),
            'permission_callback' => fn() => current_user_can('list_users'),
        ]);
    });
}
