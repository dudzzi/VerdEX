<?php

/*
|--------------------------------------------------------------------------
| VerdEX Access Control
|--------------------------------------------------------------------------
| Owner:
| Home, Inventory, Calendar, Sales,
| Farm Status, Forum, Reports
|
| Helper:
| Home, Inventory, Calendar,
| Farm Status, Forum
|--------------------------------------------------------------------------
*/


/* =========================================
   START SESSION
   ========================================= */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* =========================================
   CHECK IF LOGGED IN
   ========================================= */

function isLoggedIn()
{
    return (
        isset($_SESSION["loggedin"]) &&
        $_SESSION["loggedin"] === true
    );
}


/* =========================================
   CHECK OWNER
   ========================================= */

function isOwner()
{
    return (
        isLoggedIn() &&
        isset($_SESSION["role"]) &&
        $_SESSION["role"] === "owner"
    );
}


/* =========================================
   CHECK HELPER
   ========================================= */

function isHelper()
{
    return (
        isLoggedIn() &&
        isset($_SESSION["role"]) &&
        $_SESSION["role"] === "helper"
    );
}


/* =========================================
   REQUIRE LOGIN
   ========================================= */

function requireLogin()
{
    if (!isLoggedIn()) {

        header(
            "Location: login.php"
        );

        exit;
    }
}


/* =========================================
   OWNER-ONLY PAGE
   ========================================= */

function requireOwner()
{
    requireLogin();


    if (!isOwner()) {

        header(
            "Location: home.php"
        );

        exit;
    }
}