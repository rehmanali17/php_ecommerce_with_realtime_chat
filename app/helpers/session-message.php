<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['alert-message'])) {
    echo "
            <div class='alert " . $_SESSION['alert-type'] . " alert-dismissible fade show' role='alert'>
                " . $_SESSION['alert-message'] . "
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
         ";
    unset($_SESSION['alert-message']);
}
