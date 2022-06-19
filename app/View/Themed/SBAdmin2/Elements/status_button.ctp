<?php

//var_dump($status);
if ($status === 0 || $status === '0' || $status === 10)
    $status = 'Pending';

if ($status === 11)
    $status = 'Completed';

if ($status === 1 || $status === '1')
    $status = 'Approved';

if ($status === -1 || $status === '-1')
    $status = 'Rejected';

if ($status === -11)
    $status = 'Cancelled';

if ($status === -12)
    $status = 'Declined';

if ($status === -13)
    $status = 'Failed';

if ($text) {
    switch ($status):
        case 'Completed':
        case 'Active':
        case 'Approved':
        case 'success':
            echo '<span class="text-success">' . $status . '</span>';
            break;
        case 'Cancelled':
        case 'Declined':
        case 'Failed':
        case 'Rejected':
        case 'Inactive':
        case 'danger':
            echo '<span class="text-danger">' . $status . '</span>';
            break;
        case 'Pending':
        case 'warning':
            echo '<span class="text-warning">' . $status . '</span>';
            break;
        default :
    endswitch;
} else {
    switch ($status):
        case 'Completed':
        case 'Active':
        case 'success':
        case 'Approved':
            echo '<button class="btn btn-sm btn-block btn-success">' . $status . '</button>';
            break;
        case 'Cancelled':
        case 'Declined':
        case 'Failed':
        case 'Rejected':
        case 'Inactive':
        case 'danger':
            echo '<button class="btn btn-sm btn-block btn-danger">' . $status . '</button>';
            break;
        case 'Pending':
        case 'warning':
            echo '<button class="btn btn-sm btn-block btn-warning">' . $status . '</button>';
            break;
        default :
    endswitch;
}
?>