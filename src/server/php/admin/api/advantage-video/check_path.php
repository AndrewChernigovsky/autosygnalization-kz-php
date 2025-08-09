<?php
    header('Content-Type: text/plain');
    echo "Current user: " . get_current_user() . "\n\n";
    echo "PATH variable:\n";
    echo shell_exec('path');
    ?>