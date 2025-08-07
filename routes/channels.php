<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('process_docs.{session_id}', function($user, string $session_id) {
    return session()->getId() === $session_id;
});

Broadcast::channel('now-process_new_docs', function($user) {
    return $user->id_user === Auth::user()->id_user;
});


Broadcast::channel('now-status_upload.{session_id}' , function($user, string $session_id)  {
    return session()->getId() === $session_id;
});


Broadcast::channel('now-process_new_docs.{session_id}', function($user, string $session_id) {
    return session()->getId() === $session_id;
});


Broadcast::channel('general-notify-event', function() {
    request()->session()->getId() === session()->getId();
});