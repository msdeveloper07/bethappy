<?php

/**
 * Report Model
 *
 * Handles Report Data Source Actions
 *
 * @package    Reports.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('PaymentAppModel', 'Payments.Model');

class Report extends PaymentAppModel {

    /**
     * Model name
     * @var $name string
     */
    public $name = 'Report';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var $useTable bool
     */
    public $useTable = false;

    public function getCssData() {
        return '<style type="text/css">'
                . 'body {margin:0;}'
                . 'div, h1, h2, h3, h4, h5 {display: block;}'
                . '*, *:before, *:after {box-sizing: border-box;}'
                . '#page-wrapper {width: 100%;}'
                . '.row {margin-right: -15px; margin-left: -15px;}'
                . '.row:before {display:table; content:" ";}'
                . '.clearfix:after, .container:after, .container-fluid:after, .row:after, .btn-toolbar:after, .nav:after, .navbar:after, .navbar-header:after, .navbar-collapse:after, .pager:after, .panel-body:after, .modal-footer:after { clear: both; }'
                . '.todisable a {color:#333333;pointer-events:none;cursor:pointer;text-decoration:none;}'
                . '.backtoagent {display: none;}'
                . 'h2 {font-size: 30px;}'
                . 'h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 {margin-top: 20px; margin-bottom: 10px; font-family: inherit; font-weight: 500; line-height: 1.1; color: inherit;}'
                . '.pull-right {float:right}'
                . '.pull-left {float:left}'
                . '.text-right {text-align:right}'
                . '.text-left {text-align:left}'
                . '.text-center {text-align:center}'
                . '.panel {margin-bottom: 20px; background-color: #fff; border: 1px solid transparent; border-radius: 4px; -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05); box-shadow: 0 1px 1px rgba(0, 0, 0, .05);}'
                . '.panel-heading {padding: 10px 15px; border-bottom: 1px solid transparent; border-top-left-radius: 3px; border-top-right-radius: 3px;}'
                . '.panel-primary {border-color:#428bca;}'
                . '.panel-primary > .panel-heading {color: #428bca; background-color: #428bca; border-color: #428bca;}'
                . '.panel-info {border-color: #bce8f1;}'
                . '.panel-info > .panel-heading {color: #31708f; background-color: #d9edf7; border-color: #bce8f1;}'
                . '.panel-warning {border-color: #faebcc;}'
                . '.panel-warning > .panel-heading {color: #8a6d3b; background-color: #fcf8e3; border-color: #faebcc; }'
                . '.panel-footer {padding: 10px 15px; background-color: #f5f5f5; border-top: 1px solid #ddd; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px;}'
                . '.announcement-text {margin: 0; white-space: pre-line;}'
                . '.col-lg-2, .col-md-2, .col-sm-2, .col-xs-2, .col-lg-3, .col-md-3, .col-sm-3, .col-xs-3, .col-lg-4, .col-md-4, .col-sm-4, .col-xs-4 {float:left; position: relative; min-height: 1px; padding-right: 3px; }'
                . '.col-lg-2, .col-md-2, .col-sm-2, .col-xs-2 { width: 16.666666666666664%; }'
                . '.col-lg-3, .col-md-3, .col-sm-3, .col-xs-3 { width: 25%; }'
                . '.col-lg-4, .col-md-4, .col-sm-4, .col-xs-4 { width: 33.33333333333333%; }'
                . '.col-lg-5, .col-md-5, .col-sm-5, .col-xs-5 { width: 41.66666666666667%; }'
                . '.col-lg-6, .col-md-6, .col-sm-6, .col-xs-6 { width: 50%; }'
                . '.col-lg-7, .col-md-7, .col-sm-7, .col-xs-7 { width: 58.333333333333336%; }'
                . '.col-lg-8, .col-md-8, .col-sm-8, .col-xs-8 { width: 66.66666666666666%; }'
                . '.col-lg-9, .col-md-9, .col-sm-9, .col-xs-9 { width: 75%; }'
                . '.col-lg-10, .col-md-10, .col-sm-10, .col-xs-10 { width: 83.33333333333334%; }'
                . '.col-lg-11, .col-md-11, .col-sm-11, .col-xs-11 { width: 91.66666666666666%; }'
                . '.col-lg-12, .col-md-12, .col-sm-12, .col-xs-12 { width: 100%; }'
                . '.table {width: 100%;margin-bottom: 20px; border-collapse:collapse;}'
                . '.table-bordered {border: 1px solid #ddd;} td {border: 1px solid #ddd;}'
                . 'th, td {padding:8px;border:1px solid #ddd;}'
                . '.red {color: #ea0000} .green {color: #458746}'
                . 'th.info, td.info {background-color:#d9edf7}'
                . 'th.warning, td.warning {background-color: #fcf8e3}'
                . 'th.active, td.active {background-color: #f5f5f5}'
                . 'th.success, td.success {background-color: #dff0d8}'
                . 'th.danger, td.danger {background-color: #f2dede}'
                . 'th.bg-primary, td.bg-primary {background-color:#428bca;}'
                .'.btn-success {color: #fff; background-color: #1cc88a; border-color: #1cc88a;}'
                .'.btn-danger {color: #fff; background-color: #e74a3b; border-color: #e74a3b;}'
                .'.btn-warning {color: #fff; background-color: #f6c23e; border-color: #f6c23e;}'
                . '</style>';
    }

}
