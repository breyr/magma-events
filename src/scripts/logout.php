<?php
    // logout user by deleting session
    session_start();
    session_unset();
    session_destroy();
?>