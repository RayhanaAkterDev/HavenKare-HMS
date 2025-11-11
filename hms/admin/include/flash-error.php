<?php
if (!empty($_SESSION['flash_error'])) {
    foreach ($_SESSION['flash_error'] as $msg) {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">';
        echo '<strong class="font-bold">Error: </strong>';
        echo '<span class="block sm:inline">' . htmlentities($msg) . '</span>';
        echo '<span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button"
                    onclick="this.parentElement.parentElement.style.display=\'none\';" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 5.652a1 1 0 0 0-1.414 0L10 8.586 7.066 5.652a1 1 0 1 0-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 1 0 1.414 1.414L10 11.414l2.934 2.934a1 1 0 0 0 1.414-1.414L11.414 10l2.934-2.934a1 1 0 0 0 0-1.414z" />
                </svg>
              </span>';
        echo '</div>';
    }
    // Clear after showing
    unset($_SESSION['flash_error']);
}