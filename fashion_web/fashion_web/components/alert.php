<?php
if (isset($success_msg)) {
    foreach ($success_msg as $msg) {
        echo '<script>swal("' . addslashes($msg) . '", "", "success");</script>';
    }
}

if (isset($warning_msg)) {
    foreach ($warning_msg as $msg) {
        echo '<script>swal("' . addslashes($msg) . '", "", "warning");</script>';
    }
}

if (isset($info_msg)) {
    foreach ($info_msg as $msg) {
        echo '<script>swal("' . addslashes($msg) . '", "", "info");</script>';
    }
}

if (isset($error_msg)) {
    foreach ($error_msg as $msg) {
        echo '<script>swal("' . addslashes($msg) . '", "", "error");</script>';
    }
}
?>
