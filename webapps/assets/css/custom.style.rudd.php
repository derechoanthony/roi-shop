<?php
    header("Content-type: text/css; charset: UTF-8");
	
	$companyID=1;
	//$ibox_title_bckgrnd = DLOOKUP();
	
?>
@import url("//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;lang=en");
/*
 *
 *   INSPINIA - Responsive Admin Theme
 *   Copyright 2014 Webapplayers.com
 *
*/
h1,
h2,
h3,
h4,
h5,
h6 {
  font-weight: 100;
}
h1 {
  font-size: 30px;
}
h2 {
  font-size: 24px;
}
h3 {
  font-size: 16px;
}
h4 {
  font-size: 14px;
}
h5 {
  font-size: 12px;
}
h6 {
  font-size: 10px;
}
h3,
h4,
h5 {
  margin-top: 5px;
  font-weight: 600;
}
.nav > li > a {
  color: #a7b1c2;
  font-weight: 600;
  padding: 14px 20px 14px 25px;
}
.nav.navbar-right > li > a {
  color: #999c9e;
}
.nav > li.active > a {
  color: #ffffff;
}
.navbar-default .nav > li > a:hover,
.navbar-default .nav > li > a:focus {
  background-color: #293846;
  color: white;
}
.nav.navbar-top-links > li > a:hover,
.nav.navbar-top-links > li > a:focus {
  background-color: #ececec;
}
.nav > li > a i {
  margin-right: 6px;
}
.navbar {
  border: 0;
}
.navbar-default {
  background-color: transparent;
  border-color: #2f4050;
}
.navbar-top-links li {
  display: inline-block;
}
.navbar-top-links li:last-child {
  margin-right: 40px;
}
.navbar-top-links li a {
  padding: 20px 10px;
  min-height: 50px;
}
.dropdown-menu {
  border: medium none;
  border-radius: 3px;
  box-shadow: 0 0 3px rgba(86, 96, 117, 0.7);
  display: none;
  float: left;
  font-size: 12px;
  left: 0;
  list-style: none outside none;
  padding: 0;
  position: absolute;
  text-shadow: none;
  top: 100%;
  z-index: 1000;
  border-radius: 2px;
}
.dropdown-menu > li > a {
  border-radius: 3px;
  color: inherit;
  line-height: 25px;
  margin: 4px;
  text-align: left;
  font-weight: normal;
}
.dropdown-menu > li > a.font-bold {
  font-weight: 600;
}
.navbar-top-links .dropdown-menu li {
  display: block;
}
.navbar-top-links .dropdown-menu li:last-child {
  margin-right: 0;
}
.navbar-top-links .dropdown-menu li a {
  padding: 3px 20px;
  min-height: 0;
}
.navbar-top-links .dropdown-menu li a div {
  white-space: normal;
}
.navbar-top-links .dropdown-messages,
.navbar-top-links .dropdown-tasks,
.navbar-top-links .dropdown-alerts {
  width: 310px;
  min-width: 0;
}
.navbar-top-links .dropdown-messages {
  margin-left: 5px;
}
.navbar-top-links .dropdown-tasks {
  margin-left: -59px;
}
.navbar-top-links .dropdown-alerts {
  margin-left: -123px;
}
.navbar-top-links .dropdown-user {
  right: 0;
  left: auto;
}
.dropdown-messages,
.dropdown-alerts {
  padding: 10px 10px 10px 10px;
}
.dropdown-messages li a,
.dropdown-alerts li a {
  font-size: 12px;
}
.dropdown-messages li em,
.dropdown-alerts li em {
  font-size: 10px;
}
.nav.navbar-top-links .dropdown-alerts a {
  font-size: 12px;
}
.nav-header {
  padding: 33px 25px;
  background: url("patterns/header-profile.png") no-repeat;
}
.pace-done .nav-header {
  transition: all 0.5s;
}
.nav > li.active {
  border-left: 4px solid #19aa8d;
  background: #293846;
}
.nav.nav-second-level > li.active {
  border: none;
}
.nav.nav-second-level.collapse[style] {
  height: auto !important;
}
.nav-header a {
  color: #DFE4ED;
}
.nav-header .text-muted {
  color: #8095a8;
}
.minimalize-styl-2 {
  padding: 4px 12px;
  margin: 14px 5px 5px 20px;
  font-size: 14px;
  float: left;
}
.navbar-form-custom {
  float: left;
  height: 50px;
  padding: 0;
  width: 200px;
  display: inline-table;
}
.navbar-form-custom .form-group {
  margin-bottom: 0;
}
.nav.navbar-top-links a {
  font-size: 14px;
}
.navbar-form-custom .form-control {
  background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
  border: medium none;
  font-size: 14px;
  height: 60px;
  margin: 0;
  z-index: 2000;
}
.count-info .label {
  line-height: 12px;
  padding: 2px 5px;
  position: absolute;
  right: 6px;
  top: 12px;
}
.arrow {
  float: right;
}
.fa.arrow:before {
  content: "\f104";
}
.active > a > .fa.arrow:before {
  content: "\f107";
}
.nav-second-level li,
.nav-third-level li {
  border-bottom: none !important;
}
.nav-second-level li a {
  padding: 7px 10px 7px 10px;
  padding-left: 52px;
}
.nav-third-level li a {
  padding-left: 62px;
}
body:not(.fixed-sidebar).mini-navbar .nav li:hover > .nav-second-level,
.mini-navbar .nav li:focus > .nav-second-level {
  display: block;
  border-radius: 0 2px 2px 0;
  min-width: 140px;
  height: auto;
}
body.mini-navbar .navbar-default .nav > li > .nav-second-level li a {
  font-size: 12px;
  border-radius: 3px;
}
.fixed-nav .slimScrollDiv #side-menu {
  padding-bottom: 60px;
}
.mini-navbar .nav-second-level li a {
  padding: 10px 10px 10px 15px;
}
.mini-navbar .nav-second-level {
  position: absolute;
  left: 70px;
  top: 0px;
  background-color: #2f4050;
  padding: 10px 10px 10px 10px;
  font-size: 12px;
}
.mini-navbar li.active .nav-second-level {
  left: 65px;
}
.navbar-default .special_link a {
  background: #1ab394;
  color: white;
}
.navbar-default .special_link a:hover {
  background: #17987e !important;
  color: white;
}
.navbar-default .special_link a span.label {
  background: #fff;
  color: #1ab394;
}
.navbar-default .landing_link a {
  background: #1cc09f;
  color: white;
}
.navbar-default .landing_link a:hover {
  background: #1ab394 !important;
  color: white;
}
.navbar-default .landing_link a span.label {
  background: #fff;
  color: #1cc09f;
}
.logo-element {
  text-align: center;
  font-size: 18px;
  font-weight: 600;
  color: white;
  display: none;
  padding: 18px 0;
}
.pace-done .navbar-static-side,
.pace-done .nav-header,
.pace-done li.active,
.pace-done #page-wrapper,
.pace-done .footer {
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
  -o-transition: all 0.5s;
  transition: all 0.5s;
}
.navbar-fixed-top {
  background: #fff;
  transition-duration: 0.5s;
  border-bottom: 1px solid #e7eaec !important;
}
.navbar-fixed-top,
.navbar-static-top {
  background: #f3f3f4;
}
.fixed-nav #wrapper {
  margin-top: 60px;
}
.fixed-nav .minimalize-styl-2 {
  margin: 14px 5px 5px 15px;
}
.body-small .navbar-fixed-top {
  margin-left: 0px;
}
body.mini-navbar .navbar-static-side {
  width: 70px;
}
body.mini-navbar .profile-element,
body.mini-navbar .nav-label,
body.mini-navbar .navbar-default .nav li a span {
  display: none;
}
body:not(.fixed-sidebar).mini-navbar .nav-second-level {
  display: none;
}
body.mini-navbar .navbar-default .nav > li > a {
  font-size: 16px;
}
body.mini-navbar .logo-element {
  display: block;
}
body.mini-navbar .nav-header {
  padding: 0;
  background-color: #1ab394;
}
body.mini-navbar #page-wrapper {
  margin: 0 0 0 70px;
}
body.fixed-sidebar.mini-navbar .footer {
  margin: 0 0 0 0px;
}
body.mini-navbar #page-wrapper {
  margin: 0 0 0 70px;
}
body.fixed-sidebar .navbar-static-side {
  position: fixed;
  width: 220px;
  z-index: 101;
  height: 100%;
}
body.fixed-sidebar.mini-navbar .navbar-static-side {
  width: 0px;
}
body.fixed-sidebar.mini-navbar #page-wrapper {
  margin: 0 0 0 0px;
}
body.body-small.fixed-sidebar.mini-navbar #page-wrapper {
  margin: 0 0 0 220px;
}
body.body-small.fixed-sidebar.mini-navbar .navbar-static-side {
  width: 220px;
}
.fixed-sidebar.mini-navbar .nav li:focus > .nav-second-level {
  display: block;
  height: auto;
}
body.fixed-sidebar.mini-navbar .navbar-default .nav > li > .nav-second-level li a {
  font-size: 12px;
  border-radius: 3px;
}
.fixed-sidebar.mini-navbar .nav-second-level li a {
  padding: 10px 10px 10px 15px;
}
.fixed-sidebar.mini-navbar .nav-second-level {
  position: relative;
  padding: 0;
  font-size: 13px;
}
.fixed-sidebar.mini-navbar li.active .nav-second-level {
  left: 0px;
}
body.fixed-sidebar.mini-navbar .navbar-default .nav > li > a {
  font-size: 13px;
}
body.fixed-sidebar.mini-navbar .nav-label,
body.fixed-sidebar.mini-navbar .navbar-default .nav li a span {
  display: inline;
}
.fixed-sidebar.mini-navbar .nav-second-level li a {
  padding: 7px 10px 7px 52px;
}
.fixed-sidebar.mini-navbar .nav-second-level {
  left: 0px;
}
.btn {
  border-radius: 3px;
}
.float-e-margins .btn {
  margin-bottom: 5px;
}
.btn-w-m {
  min-width: 120px;
}
.btn-primary.btn-outline {
  color: #1ab394;
}
.btn-success.btn-outline {
  color: #1c84c6;
}
.btn-info.btn-outline {
  color: #23c6c8;
}
.btn-warning.btn-outline {
  color: #f8ac59;
}
.btn-danger.btn-outline {
  color: #ed5565;
}
.btn-primary.btn-outline:hover,
.btn-success.btn-outline:hover,
.btn-info.btn-outline:hover,
.btn-warning.btn-outline:hover,
.btn-danger.btn-outline:hover {
  color: #fff;
}

.btn-primary {
  background-color: #1ab394;
  border-color: #1ab394;
  color: #FFFFFF;
}

.btn-primary:hover,
.btn-primary:focus,
.btn-primary:active,
.btn-primary.active,
.open .dropdown-toggle.btn-primary {
  background-color: #18a689;
  border-color: #18a689;
  color: #FFFFFF;
}
.btn-primary:active,
.btn-primary.active,
.open .dropdown-toggle.btn-primary {
  background-image: none;
}
.btn-primary.disabled,
.btn-primary.disabled:hover,
.btn-primary.disabled:focus,
.btn-primary.disabled:active,
.btn-primary.disabled.active,
.btn-primary[disabled],
.btn-primary[disabled]:hover,
.btn-primary[disabled]:focus,
.btn-primary[disabled]:active,
.btn-primary.active[disabled],
fieldset[disabled] .btn-primary,
fieldset[disabled] .btn-primary:hover,
fieldset[disabled] .btn-primary:focus,
fieldset[disabled] .btn-primary:active,
fieldset[disabled] .btn-primary.active {
  background-color: #1dc5a3;
  border-color: #1dc5a3;
}
.btn-success {
  background-color: #1c84c6;
  border-color: #1c84c6;
  color: #FFFFFF;
}
.btn-success:hover,
.btn-success:focus,
.btn-success:active,
.btn-success.active,
.open .dropdown-toggle.btn-success {
  background-color: #1a7bb9;
  border-color: #1a7bb9;
  color: #FFFFFF;
}
.btn-success:active,
.btn-success.active,
.open .dropdown-toggle.btn-success {
  background-image: none;
}
.btn-success.disabled,
.btn-success.disabled:hover,
.btn-success.disabled:focus,
.btn-success.disabled:active,
.btn-success.disabled.active,
.btn-success[disabled],
.btn-success[disabled]:hover,
.btn-success[disabled]:focus,
.btn-success[disabled]:active,
.btn-success.active[disabled],
fieldset[disabled] .btn-success,
fieldset[disabled] .btn-success:hover,
fieldset[disabled] .btn-success:focus,
fieldset[disabled] .btn-success:active,
fieldset[disabled] .btn-success.active {
  background-color: #1f90d8;
  border-color: #1f90d8;
}
.btn-info {
  background-color: #23c6c8;
  border-color: #23c6c8;
  color: #FFFFFF;
}
.btn-info:hover,
.btn-info:focus,
.btn-info:active,
.btn-info.active,
.open .dropdown-toggle.btn-info {
  background-color: #21b9bb;
  border-color: #21b9bb;
  color: #FFFFFF;
}
.btn-info:active,
.btn-info.active,
.open .dropdown-toggle.btn-info {
  background-image: none;
}
.btn-info.disabled,
.btn-info.disabled:hover,
.btn-info.disabled:focus,
.btn-info.disabled:active,
.btn-info.disabled.active,
.btn-info[disabled],
.btn-info[disabled]:hover,
.btn-info[disabled]:focus,
.btn-info[disabled]:active,
.btn-info.active[disabled],
fieldset[disabled] .btn-info,
fieldset[disabled] .btn-info:hover,
fieldset[disabled] .btn-info:focus,
fieldset[disabled] .btn-info:active,
fieldset[disabled] .btn-info.active {
  background-color: #26d7d9;
  border-color: #26d7d9;
}
.btn-default {
  background-color: #c2c2c2;
  border-color: #c2c2c2;
  color: #FFFFFF;
}
.btn-default:hover,
.btn-default:focus,
.btn-default:active,
.btn-default.active,
.open .dropdown-toggle.btn-default {
  background-color: #bababa;
  border-color: #bababa;
  color: #FFFFFF;
}
.btn-default:active,
.btn-default.active,
.open .dropdown-toggle.btn-default {
  background-image: none;
}
.btn-default.disabled,
.btn-default.disabled:hover,
.btn-default.disabled:focus,
.btn-default.disabled:active,
.btn-default.disabled.active,
.btn-default[disabled],
.btn-default[disabled]:hover,
.btn-default[disabled]:focus,
.btn-default[disabled]:active,
.btn-default.active[disabled],
fieldset[disabled] .btn-default,
fieldset[disabled] .btn-default:hover,
fieldset[disabled] .btn-default:focus,
fieldset[disabled] .btn-default:active,
fieldset[disabled] .btn-default.active {
  background-color: #cccccc;
  border-color: #cccccc;
}
.btn-warning {
  background-color: #f8ac59;
  border-color: #f8ac59;
  color: #FFFFFF;
}
.btn-warning:hover,
.btn-warning:focus,
.btn-warning:active,
.btn-warning.active,
.open .dropdown-toggle.btn-warning {
  background-color: #f7a54a;
  border-color: #f7a54a;
  color: #FFFFFF;
}
.btn-warning:active,
.btn-warning.active,
.open .dropdown-toggle.btn-warning {
  background-image: none;
}
.btn-warning.disabled,
.btn-warning.disabled:hover,
.btn-warning.disabled:focus,
.btn-warning.disabled:active,
.btn-warning.disabled.active,
.btn-warning[disabled],
.btn-warning[disabled]:hover,
.btn-warning[disabled]:focus,
.btn-warning[disabled]:active,
.btn-warning.active[disabled],
fieldset[disabled] .btn-warning,
fieldset[disabled] .btn-warning:hover,
fieldset[disabled] .btn-warning:focus,
fieldset[disabled] .btn-warning:active,
fieldset[disabled] .btn-warning.active {
  background-color: #f9b66d;
  border-color: #f9b66d;
}
.btn-danger {
  background-color: #ed5565;
  border-color: #ed5565;
  color: #FFFFFF;
}
.btn-danger:hover,
.btn-danger:focus,
.btn-danger:active,
.btn-danger.active,
.open .dropdown-toggle.btn-danger {
  background-color: #ec4758;
  border-color: #ec4758;
  color: #FFFFFF;
}
.btn-danger:active,
.btn-danger.active,
.open .dropdown-toggle.btn-danger {
  background-image: none;
}
.btn-danger.disabled,
.btn-danger.disabled:hover,
.btn-danger.disabled:focus,
.btn-danger.disabled:active,
.btn-danger.disabled.active,
.btn-danger[disabled],
.btn-danger[disabled]:hover,
.btn-danger[disabled]:focus,
.btn-danger[disabled]:active,
.btn-danger.active[disabled],
fieldset[disabled] .btn-danger,
fieldset[disabled] .btn-danger:hover,
fieldset[disabled] .btn-danger:focus,
fieldset[disabled] .btn-danger:active,
fieldset[disabled] .btn-danger.active {
  background-color: #ef6776;
  border-color: #ef6776;
}
.btn-link {
  color: inherit;
}
.btn-link:hover,
.btn-link:focus,
.btn-link:active,
.btn-link.active,
.open .dropdown-toggle.btn-link {
  color: #1c84c6;
}
.btn-link:active,
.btn-link.active,
.open .dropdown-toggle.btn-link {
  background-image: none;
}
.btn-link.disabled,
.btn-link.disabled:hover,
.btn-link.disabled:focus,
.btn-link.disabled:active,
.btn-link.disabled.active,
.btn-link[disabled],
.btn-link[disabled]:hover,
.btn-link[disabled]:focus,
.btn-link[disabled]:active,
.btn-link.active[disabled],
fieldset[disabled] .btn-link,
fieldset[disabled] .btn-link:hover,
fieldset[disabled] .btn-link:focus,
fieldset[disabled] .btn-link:active,
fieldset[disabled] .btn-link.active {
  color: #cacaca;
}
.btn-white {
  color: inherit;
  background: white;
  border: 1px solid #e7eaec;
}
.btn-white:hover,
.btn-white:focus,
.btn-white:active,
.btn-white.active,
.open .dropdown-toggle.btn-white {
  color: inherit;
  border: 1px solid #d2d2d2;
}
.btn-white:active,
.btn-white.active {
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15) inset;
}
.btn-white:active,
.btn-white.active,
.open .dropdown-toggle.btn-white {
  background-image: none;
}
.btn-white.disabled,
.btn-white.disabled:hover,
.btn-white.disabled:focus,
.btn-white.disabled:active,
.btn-white.disabled.active,
.btn-white[disabled],
.btn-white[disabled]:hover,
.btn-white[disabled]:focus,
.btn-white[disabled]:active,
.btn-white.active[disabled],
fieldset[disabled] .btn-white,
fieldset[disabled] .btn-white:hover,
fieldset[disabled] .btn-white:focus,
fieldset[disabled] .btn-white:active,
fieldset[disabled] .btn-white.active {
  color: #cacaca;
}
.form-control,
.form-control:focus,
.has-error .form-control:focus,
.has-success .form-control:focus,
.has-warning .form-control:focus,
.navbar-collapse,
.navbar-form,
.navbar-form-custom .form-control:focus,
.navbar-form-custom .form-control:hover,
.open .btn.dropdown-toggle,
.panel,
.popover,
.progress,
.progress-bar {
  box-shadow: none;
}
.btn-outline {
  color: inherit;
  background-color: transparent;
  transition: all .5s;
}
.btn-rounded {
  border-radius: 50px;
}
.btn-large-dim {
  width: 90px;
  height: 90px;
  font-size: 42px;
}
button.dim {
  display: inline-block;
  color: #fff;
  text-decoration: none;
  text-transform: uppercase;
  text-align: center;
  padding-top: 6px;
  margin-right: 10px;
  position: relative;
  cursor: pointer;
  border-radius: 5px;
  font-weight: 600;
  margin-bottom: 20px !important;
}
button.dim:active {
  top: 3px;
}
button.btn-primary.dim {
  box-shadow: inset 0px 0px 0px #16987e, 0px 5px 0px 0px #16987e, 0px 10px 5px #999999;
}
button.btn-primary.dim:active {
  box-shadow: inset 0px 0px 0px #16987e, 0px 2px 0px 0px #16987e, 0px 5px 3px #999999;
}
button.btn-default.dim {
  box-shadow: inset 0px 0px 0px #b3b3b3, 0px 5px 0px 0px #b3b3b3, 0px 10px 5px #999999;
}
button.btn-default.dim:active {
  box-shadow: inset 0px 0px 0px #b3b3b3, 0px 2px 0px 0px #b3b3b3, 0px 5px 3px #999999;
}
button.btn-warning.dim {
  box-shadow: inset 0px 0px 0px #f79d3c, 0px 5px 0px 0px #f79d3c, 0px 10px 5px #999999;
}
button.btn-warning.dim:active {
  box-shadow: inset 0px 0px 0px #f79d3c, 0px 2px 0px 0px #f79d3c, 0px 5px 3px #999999;
}
button.btn-info.dim {
  box-shadow: inset 0px 0px 0px #1eacae, 0px 5px 0px 0px #1eacae, 0px 10px 5px #999999;
}
button.btn-info.dim:active {
  box-shadow: inset 0px 0px 0px #1eacae, 0px 2px 0px 0px #1eacae, 0px 5px 3px #999999;
}
button.btn-success.dim {
  box-shadow: inset 0px 0px 0px #1872ab, 0px 5px 0px 0px #1872ab, 0px 10px 5px #999999;
}
button.btn-success.dim:active {
  box-shadow: inset 0px 0px 0px #1872ab, 0px 2px 0px 0px #1872ab, 0px 5px 3px #999999;
}
button.btn-danger.dim {
  box-shadow: inset 0px 0px 0px #ea394c, 0px 5px 0px 0px #ea394c, 0px 10px 5px #999999;
}
button.btn-danger.dim:active {
  box-shadow: inset 0px 0px 0px #ea394c, 0px 2px 0px 0px #ea394c, 0px 5px 3px #999999;
}
button.dim:before {
  font-size: 50px;
  line-height: 1em;
  font-weight: normal;
  color: #fff;
  display: block;
  padding-top: 10px;
}
button.dim:active:before {
  top: 7px;
  font-size: 50px;
}
.label {
  background-color: #d1dade;
  color: #5e5e5e;
  font-family: 'Open Sans';
  font-size: 10px;
  font-weight: 600;
  padding: 3px 8px;
  text-shadow: none;
}
.badge {
  background-color: #d1dade;
  color: #5e5e5e;
  font-family: 'Open Sans';
  font-size: 11px;
  font-weight: 600;
  padding-bottom: 4px;
  padding-left: 6px;
  padding-right: 6px;
  text-shadow: none;
}
.label-primary,
.badge-primary {
  background-color: #1ab394;
  color: #FFFFFF;
}
.label-success,
.badge-success {
  background-color: #1c84c6;
  color: #FFFFFF;
}
.label-warning,
.badge-warning {
  background-color: #f8ac59;
  color: #FFFFFF;
}
.label-warning-light,
.badge-warning-light {
  background-color: #f8ac59;
  color: #ffffff;
}
.label-danger,
.badge-danger {
  background-color: #ed5565;
  color: #FFFFFF;
}
.label-info,
.badge-info {
  background-color: #23c6c8;
  color: #FFFFFF;
}
.label-inverse,
.badge-inverse {
  background-color: #262626;
  color: #FFFFFF;
}
.label-white,
.badge-white {
  background-color: #FFFFFF;
  color: #5E5E5E;
}
.label-white,
.badge-disable {
  background-color: #2A2E36;
  color: #8B91A0;
}
/* TOOGLE SWICH */
.onoffswitch {
  position: relative;
  width: 64px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
}
.onoffswitch-checkbox {
  display: none;
}
.onoffswitch-label {
  display: block;
  overflow: hidden;
  cursor: pointer;
  border: 2px solid #1ab394;
  border-radius: 2px;
}
.onoffswitch-inner {
  width: 200%;
  margin-left: -100%;
  -moz-transition: margin 0.3s ease-in 0s;
  -webkit-transition: margin 0.3s ease-in 0s;
  -o-transition: margin 0.3s ease-in 0s;
  transition: margin 0.3s ease-in 0s;
}
.onoffswitch-inner:before,
.onoffswitch-inner:after {
  float: left;
  width: 50%;
  height: 20px;
  padding: 0;
  line-height: 20px;
  font-size: 12px;
  color: white;
  font-family: Trebuchet, Arial, sans-serif;
  font-weight: bold;
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}
.onoffswitch-inner:before {
  content: "ON";
  padding-left: 10px;
  background-color: #1ab394;
  color: #FFFFFF;
}
.onoffswitch-inner:after {
  content: "OFF";
  padding-right: 10px;
  background-color: #FFFFFF;
  color: #999999;
  text-align: right;
}
.onoffswitch-switch {
  width: 20px;
  margin: 0px;
  background: #FFFFFF;
  border: 2px solid #1ab394;
  border-radius: 2px;
  position: absolute;
  top: 0;
  bottom: 0;
  right: 44px;
  -moz-transition: all 0.3s ease-in 0s;
  -webkit-transition: all 0.3s ease-in 0s;
  -o-transition: all 0.3s ease-in 0s;
  transition: all 0.3s ease-in 0s;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
  margin-left: 0;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
  right: 0px;
}
/* CHOSEN PLUGIN */
.chosen-container-single .chosen-single {
  background: #ffffff;
  box-shadow: none;
  -moz-box-sizing: border-box;
  background-color: #FFFFFF;
  border: 1px solid #CBD5DD;
  border-radius: 2px;
  cursor: text;
  height: auto !important;
  margin: 0;
  min-height: 30px;
  overflow: hidden;
  padding: 4px 12px;
  position: relative;
  width: 100%;
}
.chosen-container-multi .chosen-choices li.search-choice {
  background: #f1f1f1;
  border: 1px solid #ededed;
  border-radius: 2px;
  box-shadow: none;
  color: #333333;
  cursor: default;
  line-height: 13px;
  margin: 3px 0 3px 5px;
  padding: 3px 20px 3px 5px;
  position: relative;
}
/* PAGINATIN */
.pagination > .active > a,
.pagination > .active > span,
.pagination > .active > a:hover,
.pagination > .active > span:hover,
.pagination > .active > a:focus,
.pagination > .active > span:focus {
  background-color: #f4f4f4;
  border-color: #DDDDDD;
  color: inherit;
  cursor: default;
  z-index: 2;
}
.pagination > li > a,
.pagination > li > span {
  background-color: #FFFFFF;
  border: 1px solid #DDDDDD;
  color: inherit;
  float: left;
  line-height: 1.42857;
  margin-left: -1px;
  padding: 4px 10px;
  position: relative;
  text-decoration: none;
}
/* TOOLTIPS */
.tooltip-inner {
  background-color: #2F4050;
}
.tooltip.top .tooltip-arrow {
  border-top-color: #2F4050;
}
.tooltip.right .tooltip-arrow {
  border-right-color: #2F4050;
}
.tooltip.bottom .tooltip-arrow {
  border-bottom-color: #2F4050;
}
.tooltip.left .tooltip-arrow {
  border-left-color: #2F4050;
}
/* EASY PIE CHART*/
.easypiechart {
  position: relative;
  text-align: center;
}
.easypiechart .h2 {
  margin-left: 10px;
  margin-top: 10px;
  display: inline-block;
}
.easypiechart canvas {
  top: 0;
  left: 0;
}
.easypiechart .easypie-text {
  line-height: 1;
  position: absolute;
  top: 33px;
  width: 100%;
  z-index: 1;
}
.easypiechart img {
  margin-top: -4px;
}
.jqstooltip {
  -webkit-box-sizing: content-box;
  -moz-box-sizing: content-box;
  box-sizing: content-box;
}
/* FULLCALENDAR */
.fc-state-default {
  background-color: #ffffff;
  background-image: none;
  background-repeat: repeat-x;
  box-shadow: none;
  color: #333333;
  text-shadow: none;
}
.fc-state-default {
  border: 1px solid;
}
.fc-button {
  color: inherit;
  border: 1px solid #e7eaec;
  cursor: pointer;
  display: inline-block;
  height: 1.9em;
  line-height: 1.9em;
  overflow: hidden;
  padding: 0 0.6em;
  position: relative;
  white-space: nowrap;
}
.fc-state-active {
  background-color: #1ab394;
  border-color: #1ab394;
  color: #ffffff;
}
.fc-header-title h2 {
  font-size: 16px;
  font-weight: 600;
  color: inherit;
}
.fc-content .fc-widget-header,
.fc-content .fc-widget-content {
  border-color: #e7eaec;
  font-weight: normal;
}
.fc-border-separate tbody {
  background-color: #F8F8F8;
}
.fc-state-highlight {
  background: none repeat scroll 0 0 #FCF8E3;
}
.external-event {
  padding: 5px 10px;
  border-radius: 2px;
  cursor: pointer;
  margin-bottom: 5px;
}
.fc-ltr .fc-event-hori.fc-event-end,
.fc-rtl .fc-event-hori.fc-event-start {
  border-radius: 2px;
}
.fc-event,
.fc-agenda .fc-event-time,
.fc-event a {
  padding: 4px 6px;
  background-color: #1ab394;
  /* background color */
  border-color: #1ab394;
  /* border color */
}
.fc-event-time,
.fc-event-title {
  color: #717171;
  padding: 0 1px;
}
.ui-calendar .fc-event-time,
.ui-calendar .fc-event-title {
  color: #fff;
}
/* Chat */
.chat-activity-list .chat-element {
  border-bottom: 1px solid #e7eaec;
}
.chat-element:first-child {
  margin-top: 0;
}
.chat-element {
  padding-bottom: 15px;
}
.chat-element,
.chat-element .media {
  margin-top: 15px;
}
.chat-element,
.media-body {
  overflow: hidden;
}
.media-body {
  display: block;
}
.chat-element > .pull-left {
  margin-right: 10px;
}
.chat-element img.img-circle,
.dropdown-messages-box img.img-circle {
  width: 38px;
  height: 38px;
}
.chat-element .well {
  border: 1px solid #e7eaec;
  box-shadow: none;
  margin-top: 10px;
  margin-bottom: 5px;
  padding: 10px 20px;
  font-size: 11px;
  line-height: 16px;
}
.chat-element .actions {
  margin-top: 10px;
}
.chat-element .photos {
  margin: 10px 0;
}
.right.chat-element > .pull-right {
  margin-left: 10px;
}
.chat-photo {
  max-height: 180px;
  border-radius: 4px;
  overflow: hidden;
  margin-right: 10px;
  margin-bottom: 10px;
}
.chat {
  margin: 0;
  padding: 0;
  list-style: none;
}
.chat li {
  margin-bottom: 10px;
  padding-bottom: 5px;
  border-bottom: 1px dotted #B3A9A9;
}
.chat li.left .chat-body {
  margin-left: 60px;
}
.chat li.right .chat-body {
  margin-right: 60px;
}
.chat li .chat-body p {
  margin: 0;
  color: #777777;
}
.panel .slidedown .glyphicon,
.chat .glyphicon {
  margin-right: 5px;
}
.chat-panel .panel-body {
  height: 350px;
  overflow-y: scroll;
}
/* LIST GROUP */
a.list-group-item.active,
a.list-group-item.active:hover,
a.list-group-item.active:focus {
  background-color: #1ab394;
  border-color: #1ab394;
  color: #FFFFFF;
  z-index: 2;
}
.list-group-item-heading {
  margin-top: 10px;
}
.list-group-item-text {
  margin: 0 0 10px;
  color: inherit;
  font-size: 12px;
  line-height: inherit;
}
.no-padding .list-group-item {
  border-left: none;
  border-right: none;
  border-bottom: none;
}
.no-padding .list-group-item:first-child {
  border-left: none;
  border-right: none;
  border-bottom: none;
  border-top: none;
}
.no-padding .list-group {
  margin-bottom: 0;
}
.list-group-item {
  background-color: inherit;
  border: 1px solid #e7eaec;
  display: block;
  margin-bottom: -1px;
  padding: 10px 15px;
  position: relative;
}
/* FLOT CHART  */
.flot-chart {
  display: block;
  height: 200px;
}
.widget .flot-chart.dashboard-chart {
  display: block;
  height: 120px;
  margin-top: 40px;
}
.flot-chart.dashboard-chart {
  display: block;
  height: 180px;
  margin-top: 40px;
}
.flot-chart-content {
  width: 100%;
  height: 100%;
}
.flot-chart-pie-content {
  width: 200px;
  height: 200px;
  margin: auto;
}
.jqstooltip {
  position: absolute;
  display: block;
  left: 0px;
  top: 0px;
  visibility: hidden;
  background: #2b303a;
  background-color: rgba(43, 48, 58, 0.8);
  color: white;
  text-align: left;
  white-space: nowrap;
  z-index: 10000;
  padding: 5px 5px 5px 5px;
  min-height: 22px;
  border-radius: 3px;
}
.jqsfield {
  color: white;
  text-align: left;
}
.h-200 {
  min-height: 200px;
}
.legendLabel {
  padding-left: 5px;
}
.stat-list li:first-child {
  margin-top: 0;
}
.stat-list {
  list-style: none;
  padding: 0;
  margin: 0;
}
.stat-percent {
  float: right;
}
.stat-list li {
  margin-top: 15px;
  position: relative;
}
/* DATATABLES */
table.dataTable thead .sorting,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc,
table.dataTable thead .sorting_asc_disabled,
table.dataTable thead .sorting_desc_disabled {
  background: transparent;
}
table.dataTable thead .sorting_asc:after {
  float: right;
  font-family: fontawesome;
}
table.dataTable thead .sorting_desc:after {
  content: "\f0dd";
  float: right;
  font-family: fontawesome;
}
table.dataTable thead .sorting:after {
  content: "\f0dc";
  float: right;
  font-family: fontawesome;
  color: rgba(50, 50, 50, 0.5);
}
.dataTables_wrapper {
  padding-bottom: 30px;
}
/* CIRCLE */
.img-circle {
  border-radius: 50%;
}
.btn-circle {
  width: 30px;
  height: 30px;
  padding: 6px 0;
  border-radius: 15px;
  text-align: center;
  font-size: 12px;
  line-height: 1.428571429;
}
.btn-circle.btn-lg {
  width: 50px;
  height: 50px;
  padding: 10px 16px;
  border-radius: 25px;
  font-size: 18px;
  line-height: 1.33;
}
.btn-circle.btn-xl {
  width: 70px;
  height: 70px;
  padding: 10px 16px;
  border-radius: 35px;
  font-size: 24px;
  line-height: 1.33;
}
.show-grid [class^="col-"] {
  padding-top: 10px;
  padding-bottom: 10px;
  border: 1px dashed #ddd;
  background-color: #eee !important;
}
.show-grid {
  margin: 15px 0;
}

.show-grid .roicolumn.selected {
  border: 1px dashed #58D3F7;
}

/* ANIMATION */
.css-animation-box h1 {
  font-size: 44px;
}
.animation-efect-links a {
  padding: 4px 6px;
  font-size: 12px;
}
#animation_box {
  background-color: #f9f8f8;
  border-radius: 16px;
  width: 80%;
  margin: 0 auto;
  padding-top: 80px;
}
.animation-text-box {
  position: absolute;
  margin-top: 40px;
  left: 50%;
  margin-left: -100px;
  width: 200px;
}
.animation-text-info {
  position: absolute;
  margin-top: -60px;
  left: 50%;
  margin-left: -100px;
  width: 200px;
  font-size: 10px;
}
.animation-text-box h2 {
  font-size: 54px;
  font-weight: 600;
  margin-bottom: 5px;
}
.animation-text-box p {
  font-size: 12px;
  text-transform: uppercase;
}
/* PEACE */
.pace .pace-progress {
  background: #1ab394;
  position: fixed;
  z-index: 2000;
  top: 0px;
  left: 220px;
  height: 2px;
  -webkit-transition: width 1s;
  -moz-transition: width 1s;
  -o-transition: width 1s;
  transition: width 1s;
}
.body-small .pace .pace-progress {
  left: 0px;
}
.pace-inactive {
  display: none;
}
/* WIDGETS */
.widget {
  border-radius: 5px;
  padding: 15px 20px;
  margin-bottom: 10px;
  margin-top: 10px;
}
.widget.style1 h2 {
  font-size: 30px;
}
.widget h2,
.widget h3 {
  margin-top: 5px;
  margin-bottom: 0;
}
.widget-text-box {
  padding: 20px;
  border: 1px solid #e7eaec;
  background: #ffffff;
}
.widget-head-color-box {
  border-radius: 5px 5px 0px 0px;
  margin-top: 10px;
}
.widget .flot-chart {
  height: 100px;
}
.vertical-align div {
  display: inline-block;
  vertical-align: middle;
}
.vertical-align h2,
.vertical-align h3 {
  margin: 0;
}
.todo-list {
  list-style: none outside none;
  margin: 0;
  padding: 0;
  font-size: 14px;
}
.todo-list.small-list {
  font-size: 12px;
}
.todo-list.small-list > li {
  background: #f3f3f4;
  border-left: none;
  border-right: none;
  border-radius: 4px;
  color: inherit;
  margin-bottom: 2px;
  padding: 6px 6px 6px 12px;
}
.todo-list.small-list .btn-xs,
.todo-list.small-list .btn-group-xs > .btn {
  border-radius: 5px;
  font-size: 10px;
  line-height: 1.5;
  padding: 1px 2px 1px 5px;
}
.todo-list > li {
  background: #f3f3f4;
  border-left: 6px solid #e7eaec;
  border-right: 6px solid #e7eaec;
  border-radius: 4px;
  color: inherit;
  margin-bottom: 2px;
  padding: 10px;
}
.todo-list > li.selected {
  background: #cececa;
}
.todo-list .handle {
  cursor: move;
  display: inline-block;
  font-size: 16px;
  margin: 0 5px;
}
.todo-list > li .label {
  font-size: 9px;
  margin-left: 10px;
}
.check-link {
  font-size: 16px;
}
.todo-completed {
  text-decoration: line-through;
}
.geo-statistic h1 {
  font-size: 36px;
  margin-bottom: 0;
}
/* INPUTS */
.inline {
  display: inline-block !important;
}
.input-s-sm {
  width: 120px;
}
.input-s {
  width: 200px;
}
.input-s-lg {
  width: 250px;
}
.i-checks {
  padding-left: 0;
}
.form-control,
.single-line {
  background-color: #FFFFFF;
  background-image: none;
  border: 1px solid #c5c6c7;
  border-radius: 1px;
  color: inherit;
  display: block;
  padding: 6px 12px;
  transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
  width: 100%;
  font-size: 14px;
}
.form-control:focus,
.single-line:focus {
  border-color: #1ab394;
}
.has-success .form-control {
  border-color: #1ab394;
}
.has-warning .form-control {
  border-color: #f8ac59;
}
.has-error .form-control {
  border-color: #ed5565;
}
.has-success .control-label {
  color: #1ab394;
}
.has-warning .control-label {
  color: #f8ac59;
}
.has-error .control-label {
  color: #ed5565;
}
.input-group-addon {
  background-color: #fff;
  border: 1px solid #E5E6E7;
  border-radius: 1px;
  color: inherit;
  font-size: 14px;
  font-weight: 400;
  line-height: 1;
  padding: 6px 12px;
  text-align: center;
}
.spinner-buttons.input-group-btn .btn-xs {
  line-height: 1.13;
}
.spinner-buttons.input-group-btn {
  width: 20%;
}
.noUi-connect {
  background: none repeat scroll 0 0 #1ab394;
  box-shadow: none;
}
.slider_red .noUi-connect {
  background: none repeat scroll 0 0 #ed5565;
  box-shadow: none;
}
/* UI Sortable */
.ui-sortable .ibox-title {
  cursor: move;
}
.ui-sortable-placeholder {
  border: 1px dashed #cecece !important;
  visibility: visible !important;
  background: #e7eaec;
}
.ibox.ui-sortable-placeholder {
  margin: 0px 0px 23px !important;
}
/* SWITCHES */
.onoffswitch {
  position: relative;
  width: 54px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
}
.onoffswitch-checkbox {
  display: none;
}
.onoffswitch-label {
  display: block;
  overflow: hidden;
  cursor: pointer;
  border: 2px solid #1AB394;
  border-radius: 3px;
}
.onoffswitch-inner {
  display: block;
  width: 200%;
  margin-left: -100%;
  -moz-transition: margin 0.3s ease-in 0s;
  -webkit-transition: margin 0.3s ease-in 0s;
  -o-transition: margin 0.3s ease-in 0s;
  transition: margin 0.3s ease-in 0s;
}
.onoffswitch-inner:before,
.onoffswitch-inner:after {
  display: block;
  float: left;
  width: 50%;
  height: 16px;
  padding: 0;
  line-height: 16px;
  font-size: 10px;
  color: white;
  font-family: Trebuchet, Arial, sans-serif;
  font-weight: bold;
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}
.onoffswitch-inner:before {
  content: "ON";
  padding-left: 7px;
  background-color: #1AB394;
  color: #FFFFFF;
}
.onoffswitch-inner:after {
  content: "OFF";
  padding-right: 7px;
  background-color: #FFFFFF;
  color: #919191;
  text-align: right;
}
.onoffswitch-switch {
  display: block;
  width: 18px;
  margin: 0px;
  background: #FFFFFF;
  border: 2px solid #1AB394;
  border-radius: 3px;
  position: absolute;
  top: 0;
  bottom: 0;
  right: 36px;
  -moz-transition: all 0.3s ease-in 0s;
  -webkit-transition: all 0.3s ease-in 0s;
  -o-transition: all 0.3s ease-in 0s;
  transition: all 0.3s ease-in 0s;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
  margin-left: 0;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
  right: 0px;
}
/* jqGrid */
.ui-jqgrid {
  -moz-box-sizing: content-box;
}
.ui-jqgrid-btable {
  border-collapse: separate;
}
.ui-jqgrid-htable {
  border-collapse: separate;
}
.ui-jqgrid-titlebar {
  height: 40px;
  line-height: 15px;
  color: #676a6c;
  background-color: #F9F9F9;
  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
}
.ui-jqgrid .ui-jqgrid-title {
  float: left;
  margin: 1.1em 1em 0.2em;
}
.ui-jqgrid .ui-jqgrid-titlebar {
  position: relative;
  border-left: 0px solid;
  border-right: 0px solid;
  border-top: 0px solid;
}
.ui-widget-header {
  background: none;
  background-image: none;
  background-color: #f5f5f6;
  text-transform: uppercase;
  border-top-left-radius: 0px;
  border-top-right-radius: 0px;
}
.ui-jqgrid tr.ui-row-ltr td {
  border-right-color: inherit;
  border-right-style: solid;
  border-right-width: 1px;
  text-align: left;
  border-color: #DDDDDD;
  background-color: inherit;
}
.ui-search-toolbar input[type="text"] {
  font-size: 12px;
  height: 15px;
  border: 1px solid #CCCCCC;
  border-radius: 0px;
}
.ui-state-default,
.ui-widget-content .ui-state-default,
.ui-widget-header .ui-state-default {
  background: #F9F9F9;
  border: 1px solid #DDDDDD;
  line-height: 15px;
  font-weight: bold;
  color: #676a6c;
  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
}
.ui-widget-content {
  box-sizing: content-box;
}
.ui-icon-triangle-1-n {
  background-position: 1px -16px;
}
.ui-jqgrid tr.ui-search-toolbar th {
  border-top-width: 0px !important;
  border-top-color: inherit !important;
  border-top-style: ridge !important;
}
.ui-state-hover,
.ui-widget-content .ui-state-hover,
.ui-state-focus,
.ui-widget-content .ui-state-focus,
.ui-widget-header .ui-state-focus {
  background: #f5f5f5;
  border-collapse: separate;
}
.ui-state-highlight,
.ui-widget-content .ui-state-highlight,
.ui-widget-header .ui-state-highlight {
  background: #f2fbff;
}
.ui-state-active,
.ui-widget-content .ui-state-active,
.ui-widget-header .ui-state-active {
  border: 1px solid #dddddd;
  background: #ffffff;
  font-weight: normal;
  color: #212121;
}
.ui-jqgrid .ui-pg-input {
  font-size: inherit;
  width: 50px;
  border: 1px solid #CCCCCC;
  height: 15px;
}
.ui-jqgrid .ui-pg-selbox {
  display: block;
  font-size: 1em;
  height: 25px;
  line-height: 18px;
  margin: 0;
  width: auto;
}
.ui-jqgrid .ui-pager-control {
  position: relative;
}
.ui-jqgrid .ui-jqgrid-pager {
  height: 32px;
  position: relative;
}
.ui-pg-table .navtable .ui-corner-all {
  border-radius: 0px;
}
.ui-jqgrid .ui-pg-button:hover {
  padding: 1px;
  border: 0px;
}
.ui-jqgrid .loading {
  position: absolute;
  top: 45%;
  left: 45%;
  width: auto;
  height: auto;
  z-index: 101;
  padding: 6px;
  margin: 5px;
  text-align: center;
  font-weight: bold;
  display: none;
  border-width: 2px !important;
  font-size: 11px;
}
.ui-jqgrid .form-control {
  height: 10px;
  width: auto;
  display: inline;
  padding: 10px 12px;
}
.ui-jqgrid-pager {
  height: 32px;
}
.ui-corner-all,
.ui-corner-top,
.ui-corner-left,
.ui-corner-tl {
  border-top-left-radius: 0;
}
.ui-corner-all,
.ui-corner-top,
.ui-corner-right,
.ui-corner-tr {
  border-top-right-radius: 0;
}
.ui-corner-all,
.ui-corner-bottom,
.ui-corner-left,
.ui-corner-bl {
  border-bottom-left-radius: 0;
}
.ui-corner-all,
.ui-corner-bottom,
.ui-corner-right,
.ui-corner-br {
  border-bottom-right-radius: 0;
}
.ui-widget-content {
  border: 1px solid #ddd;
}
.ui-jqgrid .ui-jqgrid-titlebar {
  padding: 0;
}
.ui-jqgrid .ui-jqgrid-titlebar {
  border-bottom: 1px solid #ddd;
}
.ui-jqgrid tr.jqgrow td {
  padding: 6px;
}
.ui-jqdialog .ui-jqdialog-titlebar {
  padding: 10px 10px;
}
.ui-jqdialog .ui-jqdialog-title {
  float: none !important;
}
.ui-jqdialog > .ui-resizable-se {
  position: absolute;
}
/* Nestable list */
.dd {
  position: relative;
  display: block;
  margin: 0;
  padding: 0;
  list-style: none;
  font-size: 13px;
  line-height: 20px;
}
.dd-list {
  display: block;
  position: relative;
  margin: 0;
  padding: 0;
  list-style: none;
}
.dd-list .dd-list {
  padding-left: 30px;
}
.dd-collapsed .dd-list {
  display: none;
}
.dd-item,
.dd-empty,
.dd-placeholder {
  display: block;
  position: relative;
  margin: 0;
  padding: 0;
  min-height: 20px;
  font-size: 13px;
  line-height: 20px;
}
.dd-handle {
  display: block;
  margin: 5px 0;
  padding: 5px 10px;
  color: #333;
  text-decoration: none;
  border: 1px solid #e7eaec;
  background: #f5f5f5;
  -webkit-border-radius: 3px;
  border-radius: 3px;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
}
.dd-header {
	display: block;
	margin: 5px 0;
	padding: 5px 10px;
	color: #333;
	text-decoration: none;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	box-sizing: border-box;
	-moz-box-sizing: border-box;	
	background: #e5e5e5;
	border: 1px solid #ccc;
}
.dd-handle span {
  font-weight: bold;
}
.dd-handle:hover {
  background: #f0f0f0;
  cursor: pointer;
  font-weight: bold;
}
.dd-item > button {
  display: block;
  position: relative;
  cursor: pointer;
  float: left;
  width: 25px;
  height: 20px;
  margin: 5px 0;
  padding: 0;
  text-indent: 100%;
  white-space: nowrap;
  overflow: hidden;
  border: 0;
  background: transparent;
  font-size: 12px;
  line-height: 1;
  text-align: center;
  font-weight: bold;
}
.dd-item > button:before {
  content: '+';
  display: block;
  position: absolute;
  width: 100%;
  text-align: center;
  text-indent: 0;
}
.dd-item > button[data-action="collapse"]:before {
  content: '-';
}
#nestable2 .dd-item > button {
  font-family: FontAwesome;
  height: 34px;
  width: 33px;
  color: #c1c1c1;
}
#nestable2 .dd-item > button:before {
  content: "\f067";
}
#nestable2 .dd-item > button[data-action="collapse"]:before {
  content: "\f068";
}
.dd-placeholder,
.dd-empty {
  margin: 5px 0;
  padding: 0;
  min-height: 30px;
  background: #f2fbff;
  border: 1px dashed #b6bcbf;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
}
.dd-empty {
  border: 1px dashed #bbb;
  min-height: 100px;
  background-color: #e5e5e5;
  background-image: -webkit-linear-gradient(45deg, #ffffff 25%, transparent 25%, transparent 75%, #ffffff 75%, #ffffff), -webkit-linear-gradient(45deg, #ffffff 25%, transparent 25%, transparent 75%, #ffffff 75%, #ffffff);
  background-image: -moz-linear-gradient(45deg, #ffffff 25%, transparent 25%, transparent 75%, #ffffff 75%, #ffffff), -moz-linear-gradient(45deg, #ffffff 25%, transparent 25%, transparent 75%, #ffffff 75%, #ffffff);
  background-image: linear-gradient(45deg, #ffffff 25%, transparent 25%, transparent 75%, #ffffff 75%, #ffffff), linear-gradient(45deg, #ffffff 25%, transparent 25%, transparent 75%, #ffffff 75%, #ffffff);
  background-size: 60px 60px;
  background-position: 0 0, 30px 30px;
}
.dd-dragel {
  position: absolute;
  z-index: 9999;
  pointer-events: none;
}
.dd-dragel > .dd-item .dd-handle {
  margin-top: 0;
}
.dd-dragel .dd-handle {
  -webkit-box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, 0.1);
  box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, 0.1);
}
/**
* Nestable Extras
*/
.nestable-lists {
  display: block;
  clear: both;
  padding: 30px 0;
  width: 100%;
  border: 0;
  border-top: 2px solid #ddd;
  border-bottom: 2px solid #ddd;
}
#nestable-menu {
  padding: 0;
  margin: 10px 0 20px 0;
}
#nestable-output,
#nestable2-output {
  width: 100%;
  font-size: 0.75em;
  line-height: 1.333333em;
  font-family: open sans, lucida grande, lucida sans unicode, helvetica, arial, sans-serif;
  padding: 5px;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
}
#nestable2 .dd-handle {
  color: inherit;
  border: 1px dashed #e7eaec;
  background: #f3f3f4;
  padding: 10px;
}
#nestable2 .dd-handle:hover {
  /*background: #bbb;*/
}
#nestable2 span.label {
  margin-right: 10px;
}
#nestable-output,
#nestable2-output {
  font-size: 12px;
  padding: 25px;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
}
/* CodeMirror */
.CodeMirror {
  border: 1px solid #eee;
  height: auto;
}
.CodeMirror-scroll {
  overflow-y: hidden;
  overflow-x: auto;
}
/* Google Maps */
.google-map {
  height: 300px;
}
/* Validation */
label.error {
  color: #cc5965;
  display: inline-block;
  margin-left: 5px;
}
.form-control.error {
  border: 1px dotted #cc5965;
}
/* ngGrid */
.gridStyle {
  border: 1px solid #d4d4d4;
  width: 100%;
  height: 400px;
}
.gridStyle2 {
  border: 1px solid #d4d4d4;
  width: 500px;
  height: 300px;
}
.ngH eaderCell {
  border-right: none;
  border-bottom: 1px solid #e7eaec;
}
.ngCell {
  border-right: none;
}
.ngTopPanel {
  background: #F5F5F6;
}
.ngRow.even {
  background: #f9f9f9;
}
.ngRow.selected {
  background: #EBF2F1;
}
.ngRow {
  border-bottom: 1px solid #e7eaec;
}
.ngCell {
  background-color: transparent;
}
.ngHeaderCell {
  border-right: none;
}
/* Toastr custom style */
#toast-container > .toast {
  background-image: none !important;
}
#toast-container > .toast:before {
  position: fixed;
  font-family: FontAwesome;
  font-size: 24px;
  line-height: 24px;
  float: left;
  color: #FFF;
  padding-right: 0.5em;
  margin: auto 0.5em auto -1.5em;
}
#toast-container > .toast-warning:before {
  content: "\f003";
}
#toast-container > .toast-error:before {
  content: "\f001";
}
#toast-container > .toast-info:before {
  content: "\f005";
}
#toast-container > div {
  -moz-box-shadow: 0 0 3px #999;
  -webkit-box-shadow: 0 0 3px #999;
  box-shadow: 0 0 3px #999;
  opacity: .9;
  -ms-filter: alpha(opacity=90);
  filter: alpha(opacity=90);
}
#toast-container > :hover {
  -moz-box-shadow: 0 0 4px #999;
  -webkit-box-shadow: 0 0 4px #999;
  box-shadow: 0 0 4px #999;
  opacity: 1;
  -ms-filter: alpha(opacity=100);
  filter: alpha(opacity=100);
  cursor: pointer;
}
.toast {
  background-color: #1ab394;
}
.toast-success {
  background-color: #1ab394;
}
.toast-error {
  background-color: #ed5565;
}
.toast-info {
  background-color: #23c6c8;
}
.toast-warning {
  background-color: #f8ac59;
}
.toast-top-full-width {
  margin-top: 20px;
}
.toast-bottom-full-width {
  margin-bottom: 20px;
}
/* Notifie */
.cg-notify-message.inspinia-notify {
  background: #fff;
  padding: 0;
  box-shadow: 0 0 1px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.2);
  -webkit-box-shadow: 0 0 1 px rgba(0, 0, 0, 0.1), 0 2 px 4 px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 0 0 1 px rgba(0, 0, 0, 0.1), 0 2 px 4 px rgba(0, 0, 0, 0.2);
  border: none ;
  margin-top: 30px;
  color: inherit;
}
.inspinia-notify.alert-warning {
  border-left: 6px solid #f8ac59;
}
.inspinia-notify.alert-success {
  border-left: 6px solid #1c84c6;
}
.inspinia-notify.alert-danger {
  border-left: 6px solid #ed5565;
}
.inspinia-notify.alert-info {
  border-left: 6px solid #1ab394;
}
/* Image cropper style */
.img-container,
.img-preview {
  overflow: hidden;
  text-align: center;
  width: 100%;
}
.img-preview-sm {
  height: 130px;
  width: 200px;
}
/* Forum styles  */
.forum-post-container .media {
  margin: 10px 10px 10px 10px;
  padding: 20px 10px 20px 10px;
  border-bottom: 1px solid #f1f1f1;
}
.forum-avatar {
  float: left;
  margin-right: 20px;
  text-align: center;
  width: 110px;
}
.forum-avatar .img-circle {
  height: 48px;
  width: 48px;
}
.author-info {
  color: #676a6c;
  font-size: 11px;
  margin-top: 5px;
  text-align: center;
}
.forum-post-info {
  padding: 9px 12px 6px 12px;
  background: #f9f9f9;
  border: 1px solid #f1f1f1;
}
.media-body > .media {
  background: #f9f9f9;
  border-radius: 3px;
  border: 1px solid #f1f1f1;
}
.forum-post-container .media-body .photos {
  margin: 10px 0;
}
.forum-photo {
  max-width: 140px;
  border-radius: 3px;
}
.media-body > .media .forum-avatar {
  width: 70px;
  margin-right: 10px;
}
.media-body > .media .forum-avatar .img-circle {
  height: 38px;
  width: 38px;
}
.mid-icon {
  font-size: 66px;
}
.forum-item {
  margin: 10px 0;
  padding: 10px 0 20px;
  border-bottom: 1px solid #f1f1f1;
}
.views-number {
  font-size: 24px;
  line-height: 18px;
  font-weight: 400;
}
.forum-container,
.forum-post-container {
  padding: 30px !important;
}
.forum-item small {
  color: #999;
}
.forum-item .forum-sub-title {
  color: #999;
  margin-left: 50px;
}
.forum-title {
  margin: 15px 0 15px 0;
}
.forum-info {
  text-align: center;
}
.forum-desc {
  color: #999;
}
.forum-icon {
  float: left;
  width: 30px;
  margin-right: 20px;
  text-align: center;
}
a.forum-item-title {
  color: inherit;
  display: block;
  font-size: 18px;
  font-weight: 600;
}
a.forum-item-title:hover {
  color: inherit;
}
.forum-icon .fa {
  font-size: 30px;
  margin-top: 8px;
  color: #9b9b9b;
}
.forum-item.active .fa {
  color: #1ab394;
}
.forum-item.active a.forum-item-title {
  color: #1ab394;
}
@media (max-width: 992px) {
  .forum-info {
    margin: 15px 0 10px 0px;
    /* Comment this is you want to show forum info in small devices */
    display: none;
  }
  .forum-desc {
    float: none !important;
  }
}
/* New Timeline style */
.vertical-container {
  /* this class is used to give a max-width to the element it is applied to, and center it horizontally when it reaches that max-width */
  width: 90%;
  max-width: 1170px;
  margin: 0 auto;
}
.vertical-container::after {
  /* clearfix */
  content: '';
  display: table;
  clear: both;
}
#vertical-timeline {
  position: relative;
  padding: 0;
  margin-top: 2em;
  margin-bottom: 2em;
}
#vertical-timeline::before {
  content: '';
  position: absolute;
  top: 0;
  left: 18px;
  height: 100%;
  width: 4px;
  background: #f1f1f1;
}
.vertical-timeline-content .btn {
  float: right;
}
#vertical-timeline.light-timeline:before {
  background: #e7eaec;
}
.dark-timeline .vertical-timeline-content:before {
  border-color: transparent #f5f5f5 transparent transparent ;
}
.dark-timeline.center-orientation .vertical-timeline-content:before {
  border-color: transparent  transparent transparent #f5f5f5;
}
.dark-timeline .vertical-timeline-block:nth-child(2n) .vertical-timeline-content:before,
.dark-timeline.center-orientation .vertical-timeline-block:nth-child(2n) .vertical-timeline-content:before {
  border-color: transparent #f5f5f5 transparent transparent;
}
.dark-timeline .vertical-timeline-content,
.dark-timeline.center-orientation .vertical-timeline-content {
  background: #f5f5f5;
}
@media only screen and (min-width: 1170px) {
  #vertical-timeline.center-orientation {
    margin-top: 3em;
    margin-bottom: 3em;
  }
  #vertical-timeline.center-orientation:before {
    left: 50%;
    margin-left: -2px;
  }
}
@media only screen and (max-width: 1170px) {
  .center-orientation.dark-timeline .vertical-timeline-content:before {
    border-color: transparent #f5f5f5 transparent transparent;
  }
}
.vertical-timeline-block {
  position: relative;
  margin: 2em 0;
}
.vertical-timeline-block:after {
  content: "";
  display: table;
  clear: both;
}
.vertical-timeline-block:first-child {
  margin-top: 0;
}
.vertical-timeline-block:last-child {
  margin-bottom: 0;
}
@media only screen and (min-width: 1170px) and (max-width: 1400px){
  h1 {
	font-size: 24px;
  }
  .col-lg-4 > .pull-right > .section-total {
	font-size: 11px;
  }
}
@media only screen and (min-width: 1170px) {
  .center-orientation .vertical-timeline-block {
    margin: 4em 0;
  }
  .center-orientation .vertical-timeline-block:first-child {
    margin-top: 0;
  }
  .center-orientation .vertical-timeline-block:last-child {
    margin-bottom: 0;
  }
}
.vertical-timeline-icon {
  position: absolute;
  top: 0;
  left: 0;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  font-size: 16px;
  border: 3px solid #f1f1f1;
  text-align: center;
}
.vertical-timeline-icon i {
  display: block;
  width: 24px;
  height: 24px;
  position: relative;
  left: 50%;
  top: 50%;
  margin-left: -12px;
  margin-top: -9px;
}
@media only screen and (min-width: 1170px) {
  .center-orientation .vertical-timeline-icon {
    width: 50px;
    height: 50px;
    left: 50%;
    margin-left: -25px;
    -webkit-transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    font-size: 19px;
  }
  .center-orientation .vertical-timeline-icon i {
    margin-left: -12px;
    margin-top: -10px;
  }
  .center-orientation .cssanimations .vertical-timeline-icon.is-hidden {
    visibility: hidden;
  }
}
.vertical-timeline-content {
  position: relative;
  margin-left: 60px;
  background: white;
  border-radius: 0.25em;
  padding: 1em;
}
.vertical-timeline-content:after {
  content: "";
  display: table;
  clear: both;
}
.vertical-timeline-content h2 {
  font-weight: 400;
  margin-top: 4px;
}
.vertical-timeline-content p {
  margin: 1em 0;
  line-height: 1.6;
}
.vertical-timeline-content .vertical-date {
  float: left;
  font-weight: 500;
}
.vertical-date small {
  color: #1ab394;
  font-weight: 400;
}
.vertical-timeline-content::before {
  content: '';
  position: absolute;
  top: 16px;
  right: 100%;
  height: 0;
  width: 0;
  border: 7px solid transparent;
  border-right: 7px solid white;
}
@media only screen and (min-width: 768px) {
  .vertical-timeline-content h2 {
    font-size: 18px;
  }
  .vertical-timeline-content p {
    font-size: 13px;
  }
}
@media only screen and (min-width: 1170px) {
  .center-orientation .vertical-timeline-content {
    margin-left: 0;
    padding: 1.6em;
    width: 45%;
  }
  .center-orientation .vertical-timeline-content::before {
    top: 24px;
    left: 100%;
    border-color: transparent;
    border-left-color: white;
  }
  .center-orientation .vertical-timeline-content .btn {
    float: left;
  }
  .center-orientation .vertical-timeline-content .vertical-date {
    position: absolute;
    width: 100%;
    left: 122%;
    top: 2px;
    font-size: 14px;
  }
  .center-orientation .vertical-timeline-block:nth-child(even) .vertical-timeline-content {
    float: right;
  }
  .center-orientation .vertical-timeline-block:nth-child(even) .vertical-timeline-content::before {
    top: 24px;
    left: auto;
    right: 100%;
    border-color: transparent;
    border-right-color: white;
  }
  .center-orientation .vertical-timeline-block:nth-child(even) .vertical-timeline-content .btn {
    float: right;
  }
  .center-orientation .vertical-timeline-block:nth-child(even) .vertical-timeline-content .vertical-date {
    left: auto;
    right: 122%;
    text-align: right;
  }
  .center-orientation .cssanimations .vertical-timeline-content.is-hidden {
    visibility: hidden;
  }
}
.sidebard-panel {
  width: 220px;
  background: #ebebed;
  padding: 10px 20px;
  position: absolute;
  right: 0;
}
.sidebard-panel .feed-element img.img-circle {
  width: 32px;
  height: 32px;
}
.sidebard-panel .feed-element,
.media-body,
.sidebard-panel p {
  font-size: 12px;
}
.sidebard-panel .feed-element {
  margin-top: 20px;
  padding-bottom: 0;
}
.sidebard-panel .list-group {
  margin-bottom: 10px;
}
.sidebard-panel .list-group .list-group-item {
  padding: 5px 0;
  font-size: 12px;
  border: 0;
}
.sidebar-content .wrapper,
.wrapper.sidebar-content {
  padding-right: 230px !important;
}
.body-small .sidebar-content .wrapper,
.body-small .wrapper.sidebar-content {
  padding-right: 20px !important;
}
body {
  font-family: "open sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
  background-color: #2f4050;
  font-size: 13px;
  color: #676a6c;
  overflow-x: hidden;
}
body.boxed-layout {
  background: url('patterns/shattered.png');
}
body.boxed-layout #wrapper {
  background-color: #2f4050;
  max-width: 1200px;
  margin: 0 auto;
  -webkit-box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75);
  -moz-box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75);
  box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75);
}
.block {
  display: block;
}
.clear {
  display: block;
  overflow: hidden;
}
a {
  cursor: pointer;
}
a:hover,
a:focus {
  text-decoration: none;
}
.border-bottom {
  border-bottom: 1px solid #e7eaec !important;
}
.font-bold {
  font-weight: 600;
}
.font-noraml {
  font-weight: 400;
}
.text-uppercase {
  text-transform: uppercase;
}
.b-r {
  border-right: 1px solid #e7eaec;
}
.hr-line-dashed {
  border-top: 1px dashed #e7eaec;
  color: #ffffff;
  background-color: #ffffff;
  height: 1px;
  margin: 20px 0;
}
.hr-line-solid {
  border-bottom: 1px solid #e7eaec;
  background-color: rgba(0, 0, 0, 0);
  border-style: solid !important;
  margin-top: 15px;
  margin-bottom: 15px;
}
video {
  width: 100%    !important;
  height: auto   !important;
}
/* GALLERY */
.gallery > .row > div {
  margin-bottom: 15px;
}
.fancybox img {
  margin-bottom: 5px;
  /* Only for demo */
  width: 24%;
}
/* Summernote text editor  */
.note-editable {
	height: 100%;
}
/* MODAL */
.modal-content {
  background-clip: padding-box;
  background-color: #FFFFFF;
  border: 1px solid rgba(0, 0, 0, 0);
  border-radius: 4px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
  outline: 0 none;
  position: relative;
}
.modal-dialog {
  z-index: 1200;
}
.modal-body {
  padding: 20px 30px 30px 30px;
}
.inmodal .modal-body {
  background: #f8fafb;
}
.inmodal .modal-header {
  padding: 30px 15px;
  text-align: center;
}
.animated.modal.fade .modal-dialog {
  -webkit-transform: none;
  -ms-transform: none;
  -o-transform: none;
  transform: none;
}
.inmodal .modal-title {
  font-size: 26px;
}
.inmodal .modal-icon {
  font-size: 84px;
  color: #e2e3e3;
}
.modal-footer {
  margin-top: 0;
}
/* WRAPPERS */
#wrapper {
  width: 100%;
  overflow-x: hidden;
}
.wrapper {
  padding: 0 20px;
}
.wrapper-content {
  padding: 20px 10px 40px;
}
#page-wrapper {
  padding: 0 15px;
  min-height: 568px;
  position: relative !important;
}
@media (min-width: 768px) {
  #page-wrapper {
    position: inherit;
    margin: 0 0 0 240px;
    min-height: 1000px;
  }
}
.title-action {
  text-align: right;
  padding-top: 30px;
}
.ibox-content h1,
.ibox-content h2,
.ibox-content h3,
.ibox-content h4,
.ibox-content h5,
.ibox-title h1,
.ibox-title h2,
.ibox-title h3,
.ibox-title h4,
.ibox-title h5,
.subsection-header h5 {
  margin-top: 5px;
}
ul.unstyled,
ol.unstyled {
  list-style: none outside none;
  margin-left: 0;
}
.big-icon {
  font-size: 160px;
  color: #e5e6e7;
}
/* FOOTER */
.footer {
  background: none repeat scroll 0 0 white;
  border-top: 1px solid #e7eaec;
  bottom: 0;
  left: 0;
  padding: 10px 20px;
  position: absolute;
  right: 0;
}
.footer.fixed_full {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  padding: 10px 20px;
  background: white;
  border-top: 1px solid #e7eaec;
}
.footer.fixed {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  padding: 10px 20px;
  background: white;
  border-top: 1px solid #e7eaec;
  margin-left: 220px;
}
body.mini-navbar .footer.fixed,
body.body-small.mini-navbar .footer.fixed {
  margin: 0 0 0 70px;
}
body.fixed-sidebar.body-small.mini-navbar .footer.fixed {
  margin: 0 0 0 220px;
}
body.body-small .footer.fixed {
  margin-left: 0px;
}
/* PANELS */
.page-heading {
  border-top: 0;
  padding: 0px 10px 20px 10px;
}
.panel-heading h1,
.panel-heading h2 {
  margin-bottom: 5px;
}
/* TABLES */
.table-bordered {
  border: 1px solid #EBEBEB;
}
.table-bordered > thead > tr > th,
.table-bordered > thead > tr > td {
  background-color: #F5F5F6;
  border-bottom-width: 1px;
}
.table-bordered > thead > tr > th,
.table-bordered > tbody > tr > th,
.table-bordered > tfoot > tr > th,
.table-bordered > thead > tr > td,
.table-bordered > tbody > tr > td,
.table-bordered > tfoot > tr > td {
  border: 1px solid #e7e7e7;
}
.table > thead > tr > th {
  border-bottom: 1px solid #DDDDDD;
  vertical-align: bottom;
}
.table > thead > tr > th,
.table > tbody > tr > th,
.table > tfoot > tr > th,
.table > thead > tr > td,
.table > tbody > tr > td,
.table > tfoot > tr > td {
  border-top: 1px solid #e7eaec;
  line-height: 1.42857;
  padding: 8px;
  vertical-align: top;
}
/* Tabs */
.tabs-container .panel-body {
  background: #fff;
  border: 1px solid #e7eaec;
  border-radius: 2px;
  padding: 20px;
  position: relative;
}
.tabs-container .nav-tabs > li.active > a,
.tabs-container .nav-tabs > li.active > a:hover,
.tabs-container .nav-tabs > li.active > a:focus {
  border: 1px solid #e7eaec;
  border-bottom-color: transparent;
  background-color: #fff;
}
.tabs-container .nav-tabs > li {
  float: left;
  margin-bottom: -1px;
}
.tabs-container .tab-pane .panel-body {
  border-top: none;
}
.tabs-container .nav-tabs > li.active > a,
.tabs-container .nav-tabs > li.active > a:hover,
.tabs-container .nav-tabs > li.active > a:focus {
  border: 1px solid #e7eaec;
  border-bottom-color: transparent;
}
.tabs-container .nav-tabs {
  border-bottom: 1px solid #e7eaec;
}
.tabs-container .tab-pane .panel-body {
  border-top: none;
}
.tabs-container .tabs-left .tab-pane .panel-body,
.tabs-container .tabs-right .tab-pane .panel-body {
  border-top: 1px solid #e7eaec;
}
.tabs-container .nav-tabs > li a:hover {
  background: transparent;
  border-color: transparent;
}
.tabs-container .tabs-below > .nav-tabs,
.tabs-container .tabs-right > .nav-tabs,
.tabs-container .tabs-left > .nav-tabs {
  border-bottom: 0;
}
.tabs-container .tabs-left .panel-body {
  position: static;
}
.tabs-container .tabs-left > .nav-tabs,
.tabs-container .tabs-right > .nav-tabs {
  width: 20%;
}
.tabs-container .tabs-left .panel-body {
  width: 80%;
  margin-left: 20%;
}
.tabs-container .tabs-right .panel-body {
  width: 80%;
  margin-right: 20%;
}
.tabs-container .tab-content > .tab-pane,
.tabs-container .pill-content > .pill-pane {
  display: none;
}
.tabs-container .tab-content > .active,
.tabs-container .pill-content > .active {
  display: block;
}
.tabs-container .tabs-below > .nav-tabs {
  border-top: 1px solid #e7eaec;
}
.tabs-container .tabs-below > .nav-tabs > li {
  margin-top: -1px;
  margin-bottom: 0;
}
.tabs-container .tabs-below > .nav-tabs > li > a {
  -webkit-border-radius: 0 0 4px 4px;
  -moz-border-radius: 0 0 4px 4px;
  border-radius: 0 0 4px 4px;
}
.tabs-container .tabs-below > .nav-tabs > li > a:hover,
.tabs-container .tabs-below > .nav-tabs > li > a:focus {
  border-top-color: #e7eaec;
  border-bottom-color: transparent;
}
.tabs-container .tabs-left > .nav-tabs > li,
.tabs-container .tabs-right > .nav-tabs > li {
  float: none;
}
.tabs-container .tabs-left > .nav-tabs > li > a,
.tabs-container .tabs-right > .nav-tabs > li > a {
  min-width: 74px;
  margin-right: 0;
  margin-bottom: 3px;
}
.tabs-container .tabs-left > .nav-tabs {
  float: left;
  margin-right: 19px;
}
.tabs-container .tabs-left > .nav-tabs > li > a {
  margin-right: -1px;
  -webkit-border-radius: 4px 0 0 4px;
  -moz-border-radius: 4px 0 0 4px;
  border-radius: 4px 0 0 4px;
}
.tabs-container .tabs-left > .nav-tabs .active > a,
.tabs-container .tabs-left > .nav-tabs .active > a:hover,
.tabs-container .tabs-left > .nav-tabs .active > a:focus {
  border-color: #e7eaec transparent #e7eaec #e7eaec;
  *border-right-color: #ffffff;
}
.tabs-container .tabs-right > .nav-tabs {
  float: right;
  margin-left: 19px;
}
.tabs-container .tabs-right > .nav-tabs > li > a {
  margin-left: -1px;
  -webkit-border-radius: 0 4px 4px 0;
  -moz-border-radius: 0 4px 4px 0;
  border-radius: 0 4px 4px 0;
}
.tabs-container .tabs-right > .nav-tabs .active > a,
.tabs-container .tabs-right > .nav-tabs .active > a:hover,
.tabs-container .tabs-right > .nav-tabs .active > a:focus {
  border-color: #e7eaec #e7eaec #e7eaec transparent;
  *border-left-color: #ffffff;
  z-index: 1;
}
/* PANELS */
.panel.blank-panel {
  background: none;
  margin: 0;
}
.blank-panel .panel-heading {
  padding-bottom: 0;
}
.nav-tabs > li.active > a,
.nav-tabs > li.active > a:hover,
.nav-tabs > li.active > a:focus {
  -moz-border-bottom-colors: none;
  -moz-border-left-colors: none;
  -moz-border-right-colors: none;
  -moz-border-top-colors: none;
  background: none;
  border-color: #dddddd #dddddd rgba(0, 0, 0, 0);
  border-bottom: #f3f3f4;
  border-image: none;
  border-style: solid;
  border-width: 1px;
  color: #555555;
  cursor: default;
}
.nav.nav-tabs li {
  background: none;
  border: none;
}
.nav-tabs > li > a {
  color: #A7B1C2;
  font-weight: 600;
  padding: 10px 20px 10px 25px;
}
.nav-tabs > li > a:hover,
.nav-tabs > li > a:focus {
  background-color: #e6e6e6;
  color: #676a6c;
}
.ui-tab .tab-content {
  padding: 20px 0px;
}
/* GLOBAL  */
.no-padding {
  padding: 0 !important;
}
.no-borders {
  border: none !important;
}
.no-margins {
  margin: 0 !important;
}
.no-top-border {
  border-top: 0 !important;
}
.ibox-content.text-box {
  padding-bottom: 0px;
  padding-top: 15px;
}
.border-left-right {
  border-left: 1px solid #e7eaec;
  border-right: 1px solid #e7eaec;
  border-top: none;
  border-bottom: none;
}
.full-width {
  width: 100% !important;
}
.link-block {
  font-size: 12px;
  padding: 10px;
}
.nav.navbar-top-links .link-block a {
  font-size: 12px;
}
.link-block a {
  font-size: 10px;
  color: inherit;
}
body.mini-navbar .branding {
  display: none;
}
img.circle-border {
  border: 6px solid #FFFFFF;
  border-radius: 50%;
}
.branding {
  float: left;
  color: #FFFFFF;
  font-size: 18px;
  font-weight: 600;
  padding: 17px 20px;
  text-align: center;
  background-color: #1ab394;
}
.login-panel {
  margin-top: 25%;
}
.icons-box h3 {
  margin-top: 10px;
  margin-bottom: 10px;
}
.icons-box .infont a i {
  font-size: 25px;
  display: block;
  color: #676a6c;
}
.icons-box .infont a {
  color: #a6a8a9;
}
.icons-box .infont a {
  padding: 10px;
  margin: 1px;
  display: block;
}
.ui-draggable .ibox-title {
  cursor: move;
}
.breadcrumb {
  background-color: #ffffff;
  padding: 0;
  margin-bottom: 0;
}
.breadcrumb > li a {
  color: inherit;
}
.breadcrumb > .active {
  color: inherit;
}
code {
  background-color: #F9F2F4;
  border-radius: 4px;
  color: #ca4440;
  font-size: 90%;
  padding: 2px 4px;
  white-space: nowrap;
}
.ibox {
  clear: both;
  margin-bottom: 25px;
  margin-top: 0;
  padding: 0;
}
.ibox:after,
.ibox:before {
  display: table;
}
.ibox-title {
  -moz-border-bottom-colors: none;
  -moz-border-left-colors: none;
  -moz-border-right-colors: none;
  -moz-border-top-colors: none;
  background-color: #ffffff;
  border-color: #e7eaec;
  border-image: none;
  border-style: solid solid none;
  border-width: 4px 0px 0;
  color: inherit;
  margin-bottom: 0;
  padding: 14px 15px 7px;
  height: 48px;
}
.ibox-content {
  background-color: #ffffff;
  color: inherit;
  padding: 15px 20px 20px 20px;
  border-color: #e7eaec;
  border-image: none;
  border-style: solid solid none;
  border-width: 1px 0px;
}
.ibox-content.props {
  background-color: #f2f2f2;
  color: inherit;
  padding: 15px 20px 20px 20px;
  border-color: #e7eaec;
  border-image: none;
  border-style: solid solid none;
  border-width: 1px 0px;
}
table.table-mail tr td {
  padding: 12px;
}
.table-mail .check-mail {
  padding-left: 20px;
}
.table-mail .mail-date {
  padding-right: 20px;
}
.star-mail,
.check-mail {
  width: 40px;
}
.unread td a,
.unread td {
  font-weight: 600;
  color: inherit;
}
.read td a,
.read td {
  font-weight: normal;
  color: inherit;
}
.unread td {
  background-color: #f9f8f8;
}
.ibox-content {
  clear: both;
}
.ibox-heading {
  background-color: #f3f6fb;
  border-bottom: none;
}
.ibox-heading h3 {
  font-weight: 200;
  font-size: 24px;
}
.ibox-title h5, .subsection-header h5 {
  display: inline-block;
  font-size: 18px;
  margin: 0 0 7px;
  padding: 0;
  text-overflow: ellipsis;
  float: left;
}
.subsection-header h5 {
	font-size: 18px;
}
.form-group .subsection-header {
	padding-top: 15px;
	margin: 15px;
	padding: 0;
	border-bottom: 1px dashed rgb(103,106,108);
}
.ibox-title .label {
  float: left;
  margin-left: 4px;
}
.ibox-tools {
  display: inline-block;
  float: right;
  margin-top: 0;
  position: relative;
  padding: 0;
}
.ibox-tools a {
  cursor: pointer;
  margin-left: 5px;
  color: #c4c4c4;
}
.ibox-tools a.btn-primary {
  color: #fff;
}
.ibox-tools .dropdown-menu > li > a {
  padding: 4px 10px;
  font-size: 12px;
}
.ibox .open > .dropdown-menu {
  left: auto;
  right: 0;
}
/* BACKGROUNDS */
.gray-bg {
  background-color: #f3f3f4;
}
.white-bg {
  background-color: #ffffff;
  border: 0.8px solid rgb(231,234,236);
  border-top: 4px solid rgb(231,234,236);
}
.navy-bg {
  background-color: rgb(47,64,80);
  color: #ffffff;
}
.blue-bg {
  background-color: #1c84c6;
  color: #ffffff;
}
.lazur-bg {
  background-color: #23c6c8;
  color: #ffffff;
}
.yellow-bg {
  background-color: #f8ac59;
  color: #ffffff;
}
.red-bg {
  background-color: #ed5565;
  color: #ffffff;
}
.black-bg {
  background-color: #262626;
}
.panel-primary {
  border-color: #1ab394;
}
.panel-primary > .panel-heading {
  background-color: #1ab394;
  border-color: #1ab394;
}
.panel-success {
  border-color: #1c84c6;
}
.panel-success > .panel-heading {
  background-color: #1c84c6;
  border-color: #1c84c6;
  color: #ffffff;
}
.panel-info {
  border-color: #23c6c8;
}
.panel-info > .panel-heading {
  background-color: #23c6c8;
  border-color: #23c6c8;
  color: #ffffff;
}
.panel-warning {
  border-color: #f8ac59;
}
.panel-warning > .panel-heading {
  background-color: #f8ac59;
  border-color: #f8ac59;
  color: #ffffff;
}
.panel-danger {
  border-color: #ed5565;
}
.panel-danger > .panel-heading {
  background-color: #ed5565;
  border-color: #ed5565;
  color: #ffffff;
}
.progress-bar {
  background-color: #1ab394;
}
.progress-small,
.progress-small .progress-bar {
  height: 10px;
}
.progress-small,
.progress-mini {
  margin-top: 5px;
}
.progress-mini,
.progress-mini .progress-bar {
  height: 5px;
  margin-bottom: 0px;
}
.progress-bar-navy-light {
  background-color: #3dc7ab;
}
.progress-bar-success {
  background-color: #1c84c6;
}
.progress-bar-info {
  background-color: #23c6c8;
}
.progress-bar-warning {
  background-color: #f8ac59;
}
.progress-bar-danger {
  background-color: #ed5565;
}
.panel-title {
  font-size: inherit;
}
.jumbotron {
  border-radius: 6px;
  padding: 40px;
}
.jumbotron h1 {
  margin-top: 0;
}
/* COLORS */
.text-navy {
  color: #1ab394;
}
.text-primary {
  color: inherit;
}
.text-success {
  color: #1c84c6;
}
.text-info {
  color: #23c6c8;
}
.text-warning {
  color: #f8ac59;
}
.text-danger {
  color: #ed5565;
}
.text-muted {
  color: #888888;
}
/* For handle diferent bg color in AngularJS version */
.dashboards\.dashboard_2 nav.navbar,
.dashboards\.dashboard_3 nav.navbar,
.mailbox\.inbox nav.navbar,
.mailbox\.email_view nav.navbar,
.mailbox\.email_compose nav.navbar {
  background: #fff;
}
/* For handle diferent bg color in MVC version */
.Dashboard_2 .navbar.navbar-static-top,
.Dashboard_3 .navbar.navbar-static-top,
.ComposeEmail .navbar.navbar-static-top,
.EmailView .navbar.navbar-static-top,
.Inbox .navbar.navbar-static-top {
  background: #fff;
}
/* MARGINS & PADDINGS */
.p-xxs {
  padding: 5px;
}
.p-xs {
  padding: 10px;
}
.p-sm {
  padding: 15px;
}
.p-m {
  padding: 20px;
}
.p-md {
  padding: 25px;
}
.p-lg {
  padding: 30px;
}
.p-xl {
  padding: 40px;
}
.m-xxs {
  margin: 2px 4px;
}
.m-xs {
  margin: 5px;
}
.m-sm {
  margin: 10px;
}
.m {
  margin: 15px;
}
.m-md {
  margin: 20px;
}
.m-lg {
  margin: 30px;
}
.m-xl {
  margin: 50px;
}
.m-n {
  margin: 0 !important;
}
.m-l-none {
  margin-left: 0;
}
.m-l-xs {
  margin-left: 5px;
}
.m-l-sm {
  margin-left: 10px;
}
.m-l {
  margin-left: 15px;
}
.m-l-md {
  margin-left: 20px;
}
.m-l-lg {
  margin-left: 30px;
}
.m-l-xl {
  margin-left: 40px;
}
.m-l-n-xxs {
  margin-left: -1px;
}
.m-l-n-xs {
  margin-left: -5px;
}
.m-l-n-sm {
  margin-left: -10px;
}
.m-l-n {
  margin-left: -15px;
}
.m-l-n-md {
  margin-left: -20px;
}
.m-l-n-lg {
  margin-left: -30px;
}
.m-l-n-xl {
  margin-left: -40px;
}
.m-t-none {
  margin-top: 0;
}
.m-t-xxs {
  margin-top: 1px;
}
.m-t-xs {
  margin-top: 5px;
}
.m-t-sm {
  margin-top: 10px;
}
.m-t {
  margin-top: 15px;
}
.m-t-md {
  margin-top: 20px;
}
.m-t-lg {
  margin-top: 30px;
}
.m-t-xl {
  margin-top: 40px;
}
.m-t-n-xxs {
  margin-top: -1px;
}
.m-t-n-xs {
  margin-top: -5px;
}
.m-t-n-sm {
  margin-top: -10px;
}
.m-t-n {
  margin-top: -15px;
}
.m-t-n-md {
  margin-top: -20px;
}
.m-t-n-lg {
  margin-top: -30px;
}
.m-t-n-xl {
  margin-top: -40px;
}
.m-r-none {
  margin-right: 0;
}
.m-r-xxs {
  margin-right: 1px;
}
.m-r-xs {
  margin-right: 5px;
}
.m-r-sm {
  margin-right: 10px;
}
.m-r {
  margin-right: 15px;
}
.m-r-md {
  margin-right: 20px;
}
.m-r-lg {
  margin-right: 30px;
}
.m-r-xl {
  margin-right: 40px;
}
.m-r-n-xxs {
  margin-right: -1px;
}
.m-r-n-xs {
  margin-right: -5px;
}
.m-r-n-sm {
  margin-right: -10px;
}
.m-r-n {
  margin-right: -15px;
}
.m-r-n-md {
  margin-right: -20px;
}
.m-r-n-lg {
  margin-right: -30px;
}
.m-r-n-xl {
  margin-right: -40px;
}
.m-b-none {
  margin-bottom: 0;
}
.m-b-xxs {
  margin-bottom: 1px;
}
.m-b-xs {
  margin-bottom: 5px;
}
.m-b-sm {
  margin-bottom: 10px;
}
.m-b {
  margin-bottom: 15px;
}
.m-b-md {
  margin-bottom: 20px;
}
.m-b-lg {
  margin-bottom: 30px;
}
.m-b-xl {
  margin-bottom: 40px;
}
.m-b-n-xxs {
  margin-bottom: -1px;
}
.m-b-n-xs {
  margin-bottom: -5px;
}
.m-b-n-sm {
  margin-bottom: -10px;
}
.m-b-n {
  margin-bottom: -15px;
}
.m-b-n-md {
  margin-bottom: -20px;
}
.m-b-n-lg {
  margin-bottom: -30px;
}
.m-b-n-xl {
  margin-bottom: -40px;
}
.space-15 {
  margin: 15px 0;
}
.space-20 {
  margin: 20px 0;
}
.space-25 {
  margin: 25px 0;
}
.space-30 {
  margin: 30px 0;
}
body.modal-open {
  padding-right: inherit !important;
}
/* SEARCH PAGE */
.search-form {
  margin-top: 10px;
}
.search-result h3 {
  margin-bottom: 0;
  color: #1E0FBE;
}
.search-result .search-link {
  color: #006621;
}
.search-result p {
  font-size: 12px;
  margin-top: 5px;
}
/* CONTACTS */
.contact-box {
  background-color: #ffffff;
  border: 1px solid #e7eaec;
  padding: 20px;
  margin-bottom: 20px;
}
.contact-box a {
  color: inherit;
}
/* INVOICE */
.invoice-table tbody > tr > td:last-child,
.invoice-table tbody > tr > td:nth-child(4),
.invoice-table tbody > tr > td:nth-child(3),
.invoice-table tbody > tr > td:nth-child(2) {
  text-align: right;
}
.invoice-table thead > tr > th:last-child,
.invoice-table thead > tr > th:nth-child(4),
.invoice-table thead > tr > th:nth-child(3),
.invoice-table thead > tr > th:nth-child(2) {
  text-align: right;
}
.invoice-total > tbody > tr > td:first-child {
  text-align: right;
}
.invoice-total > tbody > tr > td {
  border: 0 none;
}
.invoice-total > tbody > tr > td:last-child {
  border-bottom: 1px solid #DDDDDD;
  text-align: right;
  width: 15%;
}
/* ERROR & LOGIN & LOCKSCREEN*/
.middle-box {
  height: 400px;
  width: 400px;
  position: absolute;
  top: 50%;
  left: 50%;
  margin-top: -250px;
  margin-left: -200px;
  z-index: 100;
}
.lockscreen.middle-box {
  width: 200px;
  margin-left: -100px;
  margin-top: -190px;
}
.loginscreen.middle-box {
  width: 300px;
  margin-left: -150px;
  margin-top: -330px;
}
.logo-name {
  color: #e6e6e6;
  font-size: 180px;
  font-weight: 800;
  letter-spacing: -10px;
  margin-bottom: 0px;
}
.middle-box h1 {
  font-size: 170px;
}
.wrapper .middle-box {
  margin-top: 140px;
}
.lock-word {
  z-index: 10;
  position: absolute;
  top: 50%;
  left: 50%;
  margin-top: -180px;
  margin-left: -470px;
}
.lock-word span {
  font-size: 100px;
  font-weight: 600;
  color: #e9e9e9;
  display: inline-block;
}
.lock-word .first-word {
  margin-right: 160px;
}
/* DASBOARD */
.dashboard-header {
  border-top: 0;
  padding: 20px 20px 20px 20px;
}
.dashboard-header h2 {
  margin-top: 10px;
  font-size: 26px;
}
.fist-item {
  border-top: none !important;
}
.statistic-box {
  margin-top: 40px;
}
.dashboard-header .list-group-item span.label {
  margin-right: 10px;
}
.list-group.clear-list .list-group-item {
  border-top: 1px solid #e7eaec;
  border-bottom: 0;
  border-right: 0;
  border-left: 0;
  padding: 10px 0;
}
ul.clear-list:first-child {
  border-top: none !important;
}
/* Intimeline */
.timeline-item .date i {
  position: absolute;
  top: 0;
  right: 0;
  padding: 5px;
  width: 30px;
  text-align: center;
  border-top: 1px solid #e7eaec;
  border-bottom: 1px solid #e7eaec;
  border-left: 1px solid #e7eaec;
  background: #f8f8f8;
}
.timeline-item .date i.fa-times {
  position: absolute;
  top: 0;
  right: 30px;
  padding: 5px;
  width: 30px;
  text-align: center;
  border-top: 1px solid #e7eaec;
  border-bottom: 1px solid #e7eaec;
  border-left: 1px solid #e7eaec;
  background: #f8f8f8;
}
.timeline-item .date i:hover {
	cursor: pointer;
	color: green;
	transition: all 0.4s;
}
.timeline-item .date i.fa-times:hover {
	color: red;
}
.timeline-item .date {
  text-align: right;
  width: 110px;
  position: relative;
  padding-top: 30px;
}
.timeline-item .content {
  border-left: 1px solid #e7eaec;
  border-top: 1px solid #e7eaec;
  padding-top: 10px;
  min-height: 100px;
}
.timeline-item .content:hover {
  background: #f6f6f6;
}
/* PIN BOARD */
ul.notes li,
ul.tag-list li {
  list-style: none;
}
ul.notes li h4 {
  margin-top: 20px;
  font-size: 16px;
}
ul.notes li div {
  text-decoration: none;
  color: #000;
  background: #ffc;
  display: block;
  height: 140px;
  width: 140px;
  padding: 1em;
  position: relative;
}
ul.notes li div small {
  position: absolute;
  top: 5px;
  right: 5px;
  font-size: 10px;
}
ul.notes li div a {
  position: absolute;
  right: 10px;
  bottom: 10px;
  color: inherit;
}
ul.notes li {
  margin: 10px 40px 50px 0px;
  float: left;
}
ul.notes li div p {
  font-size: 12px;
}
ul.notes li div {
  text-decoration: none;
  color: #000;
  background: #ffc;
  display: block;
  height: 140px;
  width: 140px;
  padding: 1em;
  /* Firefox */
  -moz-box-shadow: 5px 5px 2px #212121;
  /* Safari+Chrome */
  -webkit-box-shadow: 5px 5px 2px rgba(33, 33, 33, 0.7);
  /* Opera */
  box-shadow: 5px 5px 2px rgba(33, 33, 33, 0.7);
}
ul.notes li div {
  -webkit-transform: rotate(-6deg);
  -o-transform: rotate(-6deg);
  -moz-transform: rotate(-6deg);
}
ul.notes li:nth-child(even) div {
  -o-transform: rotate(4deg);
  -webkit-transform: rotate(4deg);
  -moz-transform: rotate(4deg);
  position: relative;
  top: 5px;
}
ul.notes li:nth-child(3n) div {
  -o-transform: rotate(-3deg);
  -webkit-transform: rotate(-3deg);
  -moz-transform: rotate(-3deg);
  position: relative;
  top: -5px;
}
ul.notes li:nth-child(5n) div {
  -o-transform: rotate(5deg);
  -webkit-transform: rotate(5deg);
  -moz-transform: rotate(5deg);
  position: relative;
  top: -10px;
}
ul.notes li div:hover,
ul.notes li div:focus {
  -webkit-transform: scale(1.1);
  -moz-transform: scale(1.1);
  -o-transform: scale(1.1);
  position: relative;
  z-index: 5;
}
ul.notes li div {
  text-decoration: none;
  color: #000;
  background: #ffc;
  display: block;
  height: 210px;
  width: 210px;
  padding: 1em;
  -moz-box-shadow: 5px 5px 7px #212121;
  -webkit-box-shadow: 5px 5px 7px rgba(33, 33, 33, 0.7);
  box-shadow: 5px 5px 7px rgba(33, 33, 33, 0.7);
  -moz-transition: -moz-transform 0.15s linear;
  -o-transition: -o-transform 0.15s linear;
  -webkit-transition: -webkit-transform 0.15s linear;
}
/* FILE MANAGER */
.file-box {
  float: left;
  width: 220px;
}
.file-manager h5 {
  text-transform: uppercase;
}
.file-manager {
  list-style: none outside none;
  margin: 0;
  padding: 0;
}
.folder-list li a {
  color: #666666;
  display: block;
  padding: 5px 0;
}
.folder-list li {
  border-bottom: 1px solid #e7eaec;
  display: block;
}
.folder-list li i {
  margin-right: 8px;
  color: #3d4d5d;
}
.category-list li a {
  color: #666666;
  display: block;
  padding: 5px 0;
}
.category-list li {
  display: block;
}
.category-list li i {
  margin-right: 8px;
  color: #3d4d5d;
}
.category-list li a .text-navy {
  color: #1ab394;
}
.category-list li a .text-primary {
  color: #1c84c6;
}
.category-list li a .text-info {
  color: #23c6c8;
}
.category-list li a .text-danger {
  color: #EF5352;
}
.category-list li a .text-warning {
  color: #F8AC59;
}
.file-manager h5.tag-title {
  margin-top: 20px;
}
.file-manager .tag-list li {
  float: left;
}
.file-manager .tag-list li a {
  font-size: 10px;
  background-color: #f3f3f4;
  padding: 5px 12px;
  color: inherit;
  border-radius: 2px;
  border: 1px solid #e7eaec;
  margin-right: 5px;
  margin-top: 5px;
  display: block;
}
.file {
  border: 1px solid #e7eaec;
  padding: 0;
  background-color: #ffffff;
  position: relative;
  margin-bottom: 20px;
  margin-right: 20px;
}
.file-manager .hr-line-dashed {
  margin: 15px 0;
}
.file .icon,
.file .image {
  height: 100px;
  overflow: hidden;
}
.file .icon {
  padding: 15px 10px;
  text-align: center;
}
.file-control {
  color: inherit;
  font-size: 11px;
  margin-right: 10px;
}
.file-control.active {
  text-decoration: underline;
}
.file .icon i {
  font-size: 70px;
  color: #dadada;
}
.file .file-name {
  padding: 10px;
  background-color: #f8f8f8;
  border-top: 1px solid #e7eaec;
}
.file-name small {
  color: #676a6c;
}
.corner {
  position: absolute;
  display: inline-block;
  width: 0;
  height: 0;
  line-height: 0;
  border: 0.6em solid transparent;
  border-right: 0.6em solid #f1f1f1;
  border-bottom: 0.6em solid #f1f1f1;
  right: 0em;
  bottom: 0em;
}
a.compose-mail {
  padding: 8px 10px;
}
.mail-search {
  max-width: 300px;
}
/* PROFILE */
.profile-content {
  border-top: none !important;
}
.feed-activity-list .feed-element {
  border-bottom: 1px solid #e7eaec;
}
.feed-element:first-child {
  margin-top: 0;
}
.feed-element {
  padding-bottom: 15px;
}
.feed-element,
.feed-element .media {
  margin-top: 15px;
}
.feed-element,
.media-body {
  overflow: hidden;
}
.feed-element > .pull-left {
  margin-right: 10px;
}
.feed-element img.img-circle,
.dropdown-messages-box img.img-circle {
  width: 38px;
  height: 38px;
}
.feed-element .well {
  border: 1px solid #e7eaec;
  box-shadow: none;
  margin-top: 10px;
  margin-bottom: 5px;
  padding: 10px 20px;
  font-size: 11px;
  line-height: 16px;
}
.feed-element .actions {
  margin-top: 10px;
}
.feed-element .photos {
  margin: 10px 0;
}
.feed-photo {
  max-height: 180px;
  border-radius: 4px;
  overflow: hidden;
  margin-right: 10px;
  margin-bottom: 10px;
}
/* MAILBOX */
.mail-box {
  background-color: #ffffff;
  border: 1px solid #e7eaec;
  border-top: 0;
  padding: 0px;
  margin-bottom: 20px;
}
.mail-box-header {
  background-color: #ffffff;
  border: 1px solid #e7eaec;
  border-bottom: 0;
  padding: 30px 20px 20px 20px;
}
.mail-box-header h2 {
  margin-top: 0px;
}
.mailbox-content .tag-list li a {
  background: #ffffff;
}
.mail-body {
  border-top: 1px solid #e7eaec;
  padding: 20px;
}
.mail-text {
  border-top: 1px solid #e7eaec;
}
.mail-text .note-toolbar {
  padding: 10px 15px;
}
.mail-body .form-group {
  margin-bottom: 5px;
}
.mail-text .note-editor .note-toolbar {
  background-color: #F9F8F8;
}
.mail-attachment {
  border-top: 1px solid #e7eaec;
  padding: 20px;
  font-size: 12px;
}
.mailbox-content {
  background: none;
  border: none;
  padding: 10px;
}
.mail-ontact {
  width: 23%;
}
/* PROJECTS */
.project-people,
.project-actions {
  text-align: right;
  vertical-align: middle;
}
dd.project-people {
  text-align: left;
  margin-top: 5px;
}
.project-people img {
  width: 32px;
  height: 32px;
}
.project-title a {
  font-size: 14px;
  color: #676a6c;
  font-weight: 600;
}
.project-list table tr td {
  border-top: none;
  border-bottom: 1px solid #e7eaec;
  padding: 15px 10px;
  vertical-align: middle;
}
.project-manager .tag-list li a {
  font-size: 10px;
  background-color: white;
  padding: 5px 12px;
  color: inherit;
  border-radius: 2px;
  border: 1px solid #e7eaec;
  margin-right: 5px;
  margin-top: 5px;
  display: block;
}
.project-files li a {
  font-size: 11px;
  color: #676a6c;
  margin-left: 10px;
  line-height: 22px;
}
/* FAQ */
.faq-item {
  padding: 20px;
  margin-bottom: 2px;
  background: #fff;
}
.faq-question {
  font-size: 18px;
  font-weight: 600;
  color: #1ab394;
  display: block;
}
.faq-question:hover {
  color: #179d82;
}
.faq-answer {
  margin-top: 10px;
  background: #f3f3f4;
  border: 1px solid #e7eaec;
  border-radius: 3px;
  padding: 15px;
}
.faq-item .tag-item {
  background: #f3f3f4;
  padding: 2px 6px;
  font-size: 10px;
  text-transform: uppercase;
}
/*
 *
 *   This is style for skin config
 *   Use only in demo theme
 *
*/
.theme-config {
  position: absolute;
  top: 90px;
  right: 0px;
  overflow: hidden;
}
.theme-config-box {
  margin-right: -220px;
  position: relative;
  z-index: 2000;
  transition-duration: 0.8s;
}
.theme-config-box.show {
  margin-right: 0px;
}
.spin-icon {
  background: #1ab394;
  position: absolute;
  padding: 7px 10px 7px 13px;
  border-radius: 20px 0px 0px 20px;
  font-size: 16px;
  top: 0;
  left: 0px;
  width: 40px;
  color: #fff;
  cursor: pointer;
}
.skin-setttings {
  width: 220px;
  margin-left: 40px;
  background: #f3f3f4;
}
.skin-setttings .title {
  background: #efefef;
  text-align: center;
  text-transform: uppercase;
  font-weight: 600;
  display: block;
  padding: 10px 15px;
  font-size: 12px;
}
.setings-item {
  padding: 10px 30px;
}
.setings-item.skin {
  text-align: center;
}
.setings-item .switch {
  float: right;
}
.skin-name a {
  text-transform: uppercase;
}
.setings-item a {
  color: #fff;
}
.default-skin,
.blue-skin,
.ultra-skin,
.yellow-skin {
  text-align: center;
}
.default-skin {
  font-weight: 600;
  background: #1ab394;
}
.default-skin:hover {
  background: #199d82;
}
.blue-skin {
  font-weight: 600;
  background: url("patterns/header-profile-skin-1.png") repeat scroll 0 0;
}
.blue-skin:hover {
  background: #0d8ddb;
}
.yellow-skin {
  font-weight: 600;
  background: url("patterns/header-profile-skin-3.png") repeat scroll 0 100%;
}
.yellow-skin:hover {
  background: #ce8735;
}
.ultra-skin {
  font-weight: 600;
  background: url("patterns/header-profile-skin-2.png") repeat scroll 0 0;
}
.ultra-skin:hover {
  background: #1a2d40;
}
/*
 *
 *   SKIN 1 - INSPINIA - Responsive Admin Theme
 *   NAME - Blue light
 *
*/
.skin-1 .minimalize-styl-2 {
  margin: 14px 5px 5px 30px;
}
.skin-1 .navbar-top-links li:last-child {
  margin-right: 30px;
}
.skin-1.fixed-nav .minimalize-styl-2 {
  margin: 14px 5px 5px 15px;
}
.skin-1 .spin-icon {
  background: #0e9aef !important;
}
.skin-1 .nav-header {
  background: #0e9aef;
  background: url('patterns/header-profile-skin-1.png');
}
.skin-1.mini-navbar .nav-second-level {
  background: #3e495f;
}
.skin-1 .breadcrumb {
  background: transparent;
}
.skin-1 .page-heading {
  border: none;
}
.skin-1 .nav > li.active {
  background: #3a4459;
}
.skin-1 .nav > li > a {
  color: #9ea6b9;
}
.skin-1 .nav > li.active > a {
  color: #fff;
}
.skin-1 .navbar-minimalize {
  background: #0e9aef;
  border-color: #0e9aef;
}
body.skin-1 {
  background: #3e495f;
}
.skin-1 .navbar-static-top {
  background: #ffffff;
}
.skin-1 .dashboard-header {
  background: transparent;
  border-bottom: none !important;
  border-top: none;
  padding: 20px 30px 10px 30px;
}
.fixed-nav.skin-1 .navbar-fixed-top {
  background: #fff;
}
.skin-1 .wrapper-content {
  padding: 30px 15px;
}
.skin-1 #page-wrapper {
  background: #f4f6fa;
}
.skin-1 .ibox-title,
.skin-1 .ibox-content {
  border-width: 1px;
}
.skin-1 .ibox-content:last-child {
  border-style: solid solid solid solid;
}
.skin-1 .nav > li.active {
  border: none;
}
.skin-1 .nav-header {
  padding: 35px 25px 25px 25px;
}
.skin-1 .nav-header a.dropdown-toggle {
  color: #fff;
  margin-top: 10px;
}
.skin-1 .nav-header a.dropdown-toggle .text-muted {
  color: #fff;
  opacity: 0.8;
}
.skin-1 .profile-element {
  text-align: center;
}
.skin-1 .img-circle {
  border-radius: 5px;
}
.skin-1 .navbar-default .nav > li > a:hover,
.skin-1 .navbar-default .nav > li > a:focus {
  background: #3a4459;
  color: #fff;
}
.skin-1 .nav.nav-tabs > li.active > a {
  color: #555;
}
.skin-1 .nav.nav-tabs > li.active {
  background: transparent;
}
/*
 *
 *   SKIN 2 - INSPINIA - Responsive Admin Theme
 *   NAME - Inspinia Ultra
 *
*/
body.skin-2 {
  color: #565758 !important;
}
.skin-2 .minimalize-styl-2 {
  margin: 14px 5px 5px 25px;
}
.skin-2 .navbar-top-links li:last-child {
  margin-right: 25px;
}
.skin-2 .spin-icon {
  background: #23c6c8 !important;
}
.skin-2 .nav-header {
  background: #23c6c8;
  background: url('patterns/header-profile-skin-2.png');
}
.skin-2.mini-navbar .nav-second-level {
  background: #ededed;
}
.skin-2 .breadcrumb {
  background: transparent;
}
.skin-2.fixed-nav .minimalize-styl-2 {
  margin: 14px 5px 5px 15px;
}
.skin-2 .page-heading {
  border: none;
  background: rgba(255, 255, 255, 0.7);
}
.skin-2 .nav > li.active {
  background: #e0e0e0;
}
.skin-2 .logo-element {
  padding: 17px 0;
}
.skin-2 .nav > li > a,
.skin-2 .welcome-message {
  color: #edf6ff;
}
.skin-2 #top-search::-moz-placeholder {
  color: #edf6ff;
  opacity: 0.5;
}
.skin-2 #side-menu > li > a,
.skin-2 .nav.nav-second-level > li > a {
  color: #586b7d;
}
.skin-2 .nav > li.active > a {
  color: #213a53;
}
.skin-2.mini-navbar .nav-header {
  background: #213a53;
}
.skin-2 .navbar-minimalize {
  background: #23c6c8;
  border-color: #23c6c8;
}
.skin-2 .border-bottom {
  border-bottom: none !important;
}
.skin-2 #top-search {
  color: #fff;
}
body.skin-2 #wrapper {
  background-color: #ededed;
}
.skin-2 .navbar-static-top {
  background: #213a53;
}
.fixed-nav.skin-2 .navbar-fixed-top {
  background: #213a53;
  border-bottom: none !important;
}
.skin-2 .nav-header {
  padding: 30px 25px 30px 25px;
}
.skin-2 .dashboard-header {
  background: rgba(255, 255, 255, 0.4);
  border-bottom: none !important;
  border-top: none;
  padding: 20px 30px 20px 30px;
}
.skin-2 .wrapper-content {
  padding: 30px 15px;
}
.skin-2 .dashoard-1 .wrapper-content {
  padding: 0px 30px 25px 30px;
}
.skin-2 .ibox-title {
  background: rgba(255, 255, 255, 0.7);
  border: none;
  margin-bottom: 1px;
}
.skin-2 .ibox-content {
  background: rgba(255, 255, 255, 0.4);
  border: none !important;
}
.skin-2 #page-wrapper {
  background: #f6f6f6;
  background: -webkit-radial-gradient(center, ellipse cover, #f6f6f6 20%, #d5d5d5 100%);
  background: -o-radial-gradient(center, ellipse cover, #f6f6f6 20%, #d5d5d5 100%);
  background: -ms-radial-gradient(center, ellipse cover, #f6f6f6 20%, #d5d5d5 100%);
  background: radial-gradient(ellipse at center, #f6f6f6 20%, #d5d5d5 100%);
  -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#f6f6f6, endColorstr=#d5d5d5)";
}
.skin-2 .ibox-title,
.skin-2 .ibox-content {
  border-width: 1px;
}
.skin-2 .ibox-content:last-child {
  border-style: solid solid solid solid;
}
.skin-2 .nav > li.active {
  border: none;
}
.skin-2 .nav-header a.dropdown-toggle {
  color: #edf6ff;
  margin-top: 10px;
}
.skin-2 .nav-header a.dropdown-toggle .text-muted {
  color: #edf6ff;
  opacity: 0.8;
}
.skin-2 .img-circle {
  border-radius: 10px;
}
.skin-2 .nav.navbar-top-links > li > a:hover,
.skin-2 .nav.navbar-top-links > li > a:focus {
  background: #1a2d41;
}
.skin-2 .navbar-default .nav > li > a:hover,
.skin-2 .navbar-default .nav > li > a:focus {
  background: #e0e0e0;
  color: #213a53;
}
.skin-2 .nav.nav-tabs > li.active > a {
  color: #555;
}
.skin-2 .nav.nav-tabs > li.active {
  background: transparent;
}
/*
 *
 *   SKIN 3 - INSPINIA - Responsive Admin Theme
 *   NAME - Yellow/purple
 *
*/
.skin-3 .minimalize-styl-2 {
  margin: 14px 5px 5px 30px;
}
.skin-3 .navbar-top-links li:last-child {
  margin-right: 30px;
}
.skin-3.fixed-nav .minimalize-styl-2 {
  margin: 14px 5px 5px 15px;
}
.skin-3 .spin-icon {
  background: #ecba52 !important;
}
body.boxed-layout.skin-3 #wrapper {
  background: #3e2c42;
}
.skin-3 .nav-header {
  background: #ecba52;
  background: url('patterns/header-profile-skin-3.png');
}
.skin-3.mini-navbar .nav-second-level {
  background: #3e2c42;
}
.skin-3 .breadcrumb {
  background: transparent;
}
.skin-3 .page-heading {
  border: none;
}
.skin-3 .nav > li.active {
  background: #38283c;
}
.fixed-nav.skin-3 .navbar-fixed-top {
  background: #fff;
}
.skin-3 .nav > li > a {
  color: #948b96;
}
.skin-3 .nav > li.active > a {
  color: #fff;
}
.skin-3 .navbar-minimalize {
  background: #ecba52;
  border-color: #ecba52;
}
body.skin-3 {
  background: #3e2c42;
}
.skin-3 .navbar-static-top {
  background: #ffffff;
}
.skin-3 .dashboard-header {
  background: transparent;
  border-bottom: none !important;
  border-top: none;
  padding: 20px 30px 10px 30px;
}
.skin-3 .wrapper-content {
  padding: 30px 15px;
}
.skin-3 #page-wrapper {
  background: #f4f6fa;
}
.skin-3 .ibox-title,
.skin-3 .ibox-content {
  border-width: 1px;
}
.skin-3 .ibox-content:last-child {
  border-style: solid solid solid solid;
}
.skin-3 .nav > li.active {
  border: none;
}
.skin-3 .nav-header {
  padding: 35px 25px 25px 25px;
}
.skin-3 .nav-header a.dropdown-toggle {
  color: #fff;
  margin-top: 10px;
}
.skin-3 .nav-header a.dropdown-toggle .text-muted {
  color: #fff;
  opacity: 0.8;
}
.skin-3 .profile-element {
  text-align: center;
}
.skin-3 .img-circle {
  border-radius: 5px;
}
.skin-3 .navbar-default .nav > li > a:hover,
.skin-3 .navbar-default .nav > li > a:focus {
  background: #38283c;
  color: #fff;
}
.skin-3 .nav.nav-tabs > li.active > a {
  color: #555;
}
.skin-3 .nav.nav-tabs > li.active {
  background: transparent;
}
@media (min-width: 768px) {
  #page-wrapper {
    position: inherit;
    margin: 0 0 0 220px;
    min-height: 1200px;
  }
  .navbar-static-side {
    z-index: 101;
    position: absolute;
    width: 220px;
  }
  .navbar-top-links .dropdown-messages,
  .navbar-top-links .dropdown-tasks,
  .navbar-top-links .dropdown-alerts {
    margin-left: auto;
  }
}
@media (max-width: 768px) {
  #page-wrapper {
    position: inherit;
    margin: 0 0 0 0px;
    min-height: 1000px;
  }
  .body-small .navbar-static-side {
    display: none;
    z-index: 1;
    position: absolute;
    width: 70px;
  }
  .body-small.mini-navbar .navbar-static-side {
    display: block;
  }
  .lock-word {
    display: none;
  }
  .navbar-form-custom {
    display: none;
  }
  .navbar-header {
    display: inline;
    float: left;
  }
  .sidebard-panel {
    z-index: 2;
    position: relative;
    width: auto;
    min-height: 100% !important;
  }
  .sidebar-content .wrapper {
    padding-right: 0px;
    z-index: 1;
  }
  .fixed-sidebar.body-small .navbar-static-side {
    display: none;
    z-index: 1;
    position: fixed;
    width: 220px;
  }
  .fixed-sidebar.body-small.mini-navbar .navbar-static-side {
    display: block;
  }
}
@media (max-width: 350px) {
  .timeline-item .date {
    text-align: left;
    width: 110px;
    position: relative;
    padding-top: 30px;
  }
  .timeline-item .date i {
    position: absolute;
    top: 0;
    left: 15px;
    padding: 5px;
    width: 30px;
    text-align: center;
    border: 1px solid #e7eaec;
    background: #f8f8f8;
  }
  .timeline-item .content {
    border-left: none;
    border-top: 1px solid #e7eaec;
    padding-top: 10px;
    min-height: 100px;
  }
  .nav.navbar-top-links li.dropdown {
    display: none;
  }
}
/* Only demo */
@media (max-width: 1000px) {
  .welcome-message {
    display: none;
  }
}

/* Added Styles */

.pod-header {
	height: 60px;
	overflow: hidden;
}

.widget:hover {
	border-top-color: #2f4050;
}

.txt-center {
	text-align: center;
}

.txt-right {
	text-align: right;
}

.progress {
	margin: 15px 0;
}

.pod-total {
	font-weight: 700;
}

.alert:before {
    background-color: rgba(0, 0, 0, 0.25);
    content: "";
    height: 101%;
    left: 0;
    position: absolute;
    top: 0;
    width: 50px;
    z-index: 2;
}

.alert-info:before {
    background-color: rgb(244, 115, 33);
}

.alert.alert-info:after {
    content: "";
}

.alert:after {
    color: #ffffff;
    font-family: "FontAwesome";
    font-size: 20px;
    left: 17px;
    position: absolute;
    top: 10px;
    z-index: 3;
}

.alert-info {
    color: rgb(67, 29, 3);
	font-size: 14px;
}

.alert-info {
    background-color: rgb(251, 206, 176);
}

.alert {
    border: medium none;
    border-radius: 0;
    margin: 20px 0;
    overflow: hidden;
    padding-left: 70px;
    position: relative;
    z-index: 1;
}

.form-horizontal .control-label { text-align: left; }

.player a {
   position: absolute;
   display: block;
   background: transparent;
   height: 100%;
   width: 100%;
   top: 0;
   left: 0;
   z-index: 50;
}

h2.pod-header:hover {
	cursor: pointer;
	color: rgb(47, 64, 80);
	transition: all 1.2s;
}

.form-group {
	min-height: 34px;
}

.input-slider {
	margin-top: 8px;
}

span.input-group-addon {
	border: 1px solid rgb(197,198,199);
	background-color: #eee;
}

.form-group label {
	font-family: 'Open Sans', sans-serif;
	font-weight: 400;
	font-size: 15px;
}

.ibox:hover > .ibox-title {
	border-color: #2f4050;
	border-radius: 5px;
	transition: all 0.8s;
}

.chosen-container-single .chosen-single span {
	margin-top: 2px;
}

.section-writeup {
	color: rgb(86, 86, 86);
}

.input-adivce {
	margin-top: 5px;
	font-size: 16px;
}

.input-tooltip {
	color: blue;
}

.input-calculation {
	color: green;
}

.fa-question-circle, .fa-calculator {
	cursor: help;
}

p.caption-text {
	font-family: "Open Sans", Helvetica, Arial, sans-serif;
	font-size: 14px;
	line-height: 20.5px;
}

.pull-right{
	text-align: right;
}

.ibox-heading {
	border: 0.8px solid rgb(197, 198, 199);
}

.savings-breakdown {
	border: 6px solid white;
}

.annual-totals hr.calculation-divider {
	border: none;
	border-top: 1px solid rgb( 167, 168, 169 );
}

.annual-totals ul {
	list-style: outside none none;
	padding: 10px 0 0 0;
}

.mfp-content > .white-popup-block {
  max-width: 950px;
}

.chosen-container .chosen-single div b {
	position: relative;
	top: 4px;
}

#company_logo {
	border: 0 none;
	height: auto;
	max-width: 100%;
	vertical-align: middle;
}

.txt-money {
	color: #216C2A;
}

.txt-removed {
	color: #bd362f;
}

blockquote {
	font-family: Georgia, serif;
	font-size: 18px;
	font-style: italic;
	margin: 0.25em 0;
	padding: 0.25em 40px;
	line-height: 1.45;
	position: relative;
	color: #383838;
	border: none;
}

blockquote:before {
	display: block;
	content: "\201C";
	font-size: 80px;
	position: absolute;
	left: -5px;
	top: -20px;
	color: #7a7a7a;
}

blockquote cite {
	color: #999999;
	font-size: 14px;
	display: block;
	margin-top: 5px;
}
 
blockquote cite:before {
	content: "\2014 \2009";
}

.ver-link-output {
	font-size: 16px;
	width: 100%;
	border: none;
	resize: none;
	background: none;
}

.modal-content .modal-footer {
	margin-top: 0;
}

.project-title {
	vertical-align: center;
	font-size: 14px;
}

#loadvalues-overlay, #pdf-progress-overlay {
	background: rgba(0, 0, 0, 0);
	height: 100%;
	left: 0;
	position: fixed;
	top: 0;
	width: 100%;
	z-index: 10000;
}

.pdf-progress, .loading-spinner {
	background: none repeat scroll 0 0 #fff;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 53, 98, 0.5);
	color: #555;
	margin: 20% auto 25%;
	padding: 20px;
	text-align: center;
	width: 500px;
}

a.table-scroll {
	color: #676a6c;
}

a.table-scroll:hover {
	color: #2a6496;
	text-decoration: underline;
}

.input-group-addon > .chosen-container > .chosen-single {
	border-radius: 0;
	border: none;
}

.input-group-addon > .chosen-container > .chosen-single:hover {
	cursor: pointer;
}

.input-group-addon.append {
	background-color: #fff;
	background: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(20%, #ffffff), color-stop(50%, #f6f6f6), color-stop(52%, #eeeeee), color-stop(100%, #f4f4f4));
	background: -webkit-linear-gradient(top, #ffffff 20%, #f6f6f6 50%, #eeeeee 52%, #f4f4f4 100%);
	background: -moz-linear-gradient(top, #ffffff 20%, #f6f6f6 50%, #eeeeee 52%, #f4f4f4 100%);
	background: -o-linear-gradient(top, #ffffff 20%, #f6f6f6 50%, #eeeeee 52%, #f4f4f4 100%);
	background: linear-gradient(top, #ffffff 20%, #f6f6f6 50%, #eeeeee 52%, #f4f4f4 100%);
}

.nohover {
	cursor: auto;
}

.input-context-menu:hover {
	cursor: pointer;
	color: #428bca;
}



.col-label{
	font-size: 10px;
	color: red;
	margin: 0px 0px 0px 0px;
	padding: 0px 0px 0px 0px;
	border-style: none;
}

.col-label.hidden{
	visibility: hidden;
}

.section-writeup {
	padding: 3mm;
	font-size: 4mm;
	font-family: Arial, Helvetica, sans-serif;
}

.grandtotal {
	color: green;
	font-size: 24mm;
	text-align: center;
	font-weight: 900;
	margin: 0;
	line-height: 1;
}

.content-bubble.right {
	border-radius: 2mm 0 0 2mm;
	float: right;
}

.content-bubble {
	font-family: 'Trebuchet MS';
	padding: 3mm;
	width: 95mm;
	background: rgb(1,47,71);
	color: #000;
}

.content-header {
	color: white;
	font-size: 6mm;
	margin: 0;
	padding: 0;
	font-family: 'Trebuchet MS';
	text-align: left;
	font-variant: small-caps;
}

.pdf-section-header {
	width: 100%;
	line-height: 8mm;
	font-size: 6mm;
	background-color: rgb(1,47,71);
	color: rgb(255,255,255);
	margin-top: 0;
	padding: 2mm 4mm;
	font-variant: small-caps;
}

.pdf-section-header .prepared-for {
	float: right;
	white-space: nowrap;
}

.contributor-table {
	font-size:5mm;
	font-family: 'Georgia', serif;
	line-height: 22px;
	font-weight: 100;
	width: 100%;
	margin: 0 auto;
}

.prepared-header {
	text-align: center;
	font-size: 9mm;
	font-family: 'Georgia', serif;
	line-height: 10mm;
	font-variant: small-caps;
	font-weight: 100;
}

.pdf-header {
	width: 80mm;
}

.company-name {
	text-align: center;
	font-size: 9mm;
	font-family: 'Georgia', serif;
	line-height: 10mm;
	font-variant: small-caps;
	font-weight: 100;
	margin-bottom: 0.5mm;
}

.cover-user-table {
	font-size: 5.5mm;
	font-family: 'Georgia', serif;
	line-height: 7mm;
	font-weight: 100;
}










.point-highlight {
	font-family: 'Trebuchet MS';
	font-size: 24px;
	padding: 15px;
	width: 375px;
	background: rgb(1,47,71); /* Old browsers */
	color: #000;
	border-radius: 0 5px 5px 0;
}

.content-bubble h3, .point-highlight h3 {
	font-size: 21px;
	color: #eef;
	margin: 0;
	font-variant: small-caps;
}

.small h3 {
	font-size: 5mm;
	color: #eef;
	margin-left: 1.5mm;
	font-variant: none;
}

.content-list {
	border-bottom: 1px solid white;
}

.content-bubble ul li {
	color: #eef;
	font-size: 18px;
	margin: 0;
	padding: 0;
	margin-left: 60px;
}

div.list {
	margin-left: 10px;
	padding-top: 0;
}


.savingsTable thead {	
	font-weight: bold; 
	vertical-align: bottom;
}

.savingsTable tfoot {	
	font-weight: bold; 
	vertical-align: top;
}

.savingsTable thead td { 
	font-weight: bold; 
}

.savingsTable tfoot td { 
	font-weight: bold; 
}

.savingsTable th {
	font-weight: bold; 
	vertical-align: top; 
	text-align:left; 
	padding-left: 2mm; 
	padding-right: 2mm; 
	padding-top: 0.5mm; 
	padding-bottom: 0.5mm; 
}

.savingsTable td {
	padding-left: 2mm; 
	vertical-align: top; 
	text-align:left; 
	padding-right: 2mm; 
	padding-top: 0.5mm; 
	padding-bottom: 0.5mm;
	height: 8mm;
}

.savingsTable { 
	background-color: #e3ece4;
}

.savingsTable td, .savingsTable th {
	border-top: 1px solid #FFFFFF; 
}

table.pdf-page-header {
	width: 100%;
	color: white;
	line-height: 8mm;
	font-size: 6mm;
}

.pdf-section-total {
	color: green;
	font-size: 18mm;
	text-align: center;
	font-weight: 900;
	margin: 0;
	line-height: 1;
}

.quotation blockquote {
	font-size: 4mm;
}

.quotation blockquote:before {
	content: "";
}

.content-paragraph {
	font-size: 5mm;
	color: white;
}

.sfdc-link:hover {
	cursor: pointer;
	text-decoration: underline;
}

.pdf-progress-bar {
	-webkit-transition: width 0s;
	-moz-transition: width 0s;
	-o-transition: width 0s;
	transition: width 0s;
}

.modal-xlg {
	width: 90%;
}

.dd-handle:first-child {
	margin-top: 0;
}

.hovered{
	background: #f2fbff;
	border: 1px dashed #b6bcbf;
	font-weight: 700;
}

.remove-sfdc-link {
	visibility: hidden;
	margin: 0 5px;
}

.sfdc-link:hover{
	text-decoration: none;
}

.sfdc-link:hover .remove-sfdc-link {
	visibility: visible;
}

.note-editor .note-toolbar {
    padding-bottom: 5px;
    padding-left: 10px;
    padding-top: 5px;
    margin: 0;
    background-color: #f5f5f5;
    border-bottom: 1px solid #E7EAEC;
}

/* AGILE BOARD */
.sortable-list {
  padding: 10px 0;
}
.agile-list {
  list-style: none;
  margin: 0;
}
.agile-list li {
  background: #FAFAFB;
  border: 1px solid #e7eaec;
  margin: 0px 0 10px 0;
  padding: 10px;
  border-radius: 2px;
}
.agile-list li:hover {
  cursor: pointer;
  background: #fff;
}
.agile-list li.warning-element {
  border-left: 3px solid #f8ac59;
}
.agile-list li.danger-element {
  border-left: 3px solid #ed5565;
}
.agile-list li.info-element {
  border-left: 3px solid #1c84c6;
}
.agile-list li.success-element {
  border-left: 3px solid #1ab394;
}
.agile-detail {
  margin-top: 5px;
  font-size: 12px;
}

#cssload-contain {
	width: 146px;
	height: 49px;
	top: 15px;
	right: 0;
	left: 0;
	bottom: 0;
	margin: auto;
	opacity: 0;
	animation: fadeIn 1.15s 1;
		-o-animation: fadeIn 1.15s 1;
		-ms-animation: fadeIn 1.15s 1;
		-webkit-animation: fadeIn 1.15s 1;
		-moz-animation: fadeIn 1.15s 1;
	animation-fill-mode: forwards;
		-o-animation-fill-mode: forwards;
		-ms-animation-fill-mode: forwards;
		-webkit-animation-fill-mode: forwards;
		-moz-animation-fill-mode: forwards;
}

.cssload-wrap {
	animation: translate 1150ms infinite ease-in-out alternate, zindex 2300ms infinite ease-in-out;
		-o-animation: translate 1150ms infinite ease-in-out alternate, zindex 2300ms infinite ease-in-out;
		-ms-animation: translate 1150ms infinite ease-in-out alternate, zindex 2300ms infinite ease-in-out;
		-webkit-animation: translate 1150ms infinite ease-in-out alternate, zindex 2300ms infinite ease-in-out;
		-moz-animation: translate 1150ms infinite ease-in-out alternate, zindex 2300ms infinite ease-in-out;
	position: absolute;
}

.cssload-ball {
	width: 49px;
	height: 49px;
	box-shadow: 0 -6.25px 0 rgba(0,0,0,0.15) inset;
	background-color: rgb(227,116,107);
	border-radius: 50%;
	animation: scale 1150ms infinite ease-in-out alternate;
		-o-animation: scale 1150ms infinite ease-in-out alternate;
		-ms-animation: scale 1150ms infinite ease-in-out alternate;
		-webkit-animation: scale 1150ms infinite ease-in-out alternate;
		-moz-animation: scale 1150ms infinite ease-in-out alternate;
	animation-delay: -575ms;
		-o-animation-delay: -575ms;
		-ms-animation-delay: -575ms;
		-webkit-animation-delay: -575ms;
		-moz-animation-delay: -575ms;
	transform: scale(0.5);
		-o-transform: scale(0.5);
		-ms-transform: scale(0.5);
		-webkit-transform: scale(0.5);
		-moz-transform: scale(0.5);
	border: 2px solid black;
}

.cssload-ball:after {
	content: "";
	width: 49px;
	height: 13px;
	background: rgb(238,238,238);
	position: absolute;
	top: 68px;
	border-radius: 50%;
}

#cssload-wrap2 {
	animation-delay: -1150ms;
		-o-animation-delay: -1150ms;
		-ms-animation-delay: -1150ms;
		-webkit-animation-delay: -1150ms;
		-moz-animation-delay: -1150ms;
}

#cssload-ball2 {
	background-color: rgb(57,123,249);
	animation-delay: -1725ms;
		-o-animation-delay: -1725ms;
		-ms-animation-delay: -1725ms;
		-webkit-animation-delay: -1725ms;
		-moz-animation-delay: -1725ms;
}

#cssload-wrap3 {
	animation-delay: -1725ms;
		-o-animation-delay: -1725ms;
		-ms-animation-delay: -1725ms;
		-webkit-animation-delay: -1725ms;
		-moz-animation-delay: -1725ms;
}

#cssload-ball3 {
	background-color: rgb(244,180,0);
	animation-delay: -2300ms;
}

#cssload-wrap4 {
	animation-delay: -2875ms;
		-o-animation-delay: -2875ms;
		-ms-animation-delay: -2875ms;
		-webkit-animation-delay: -2875ms;
		-moz-animation-delay: -2875ms;
}

#cssload-ball4 {
	background-color: rgb(15,157,88);
	animation-delay: -3450ms;
		-o-animation-delay: -3450ms;
		-ms-animation-delay: -3450ms;
		-webkit-animation-delay: -3450ms;
		-moz-animation-delay: -3450ms;
}






@keyframes translate {
	100% {
		transform: translateX(97px);
	}
}

@-o-keyframes translate {
	100% {
		-o-transform: translateX(97px);
	}
}

@-ms-keyframes translate {
	100% {
		-ms-transform: translateX(97px);
	}
}

@-webkit-keyframes translate {
	100% {
		-webkit-transform: translateX(97px);
	}
}

@-moz-keyframes translate {
	100% {
		-moz-transform: translateX(97px);
	}
}

@keyframes scale {
	100% {
		transform: scale(1);
	}
}

@-o-keyframes scale {
	100% {
		-o-transform: scale(1);
	}
}

@-ms-keyframes scale {
	100% {
		-ms-transform: scale(1);
	}
}

@-webkit-keyframes scale {
	100% {
		-webkit-transform: scale(1);
	}
}

@-moz-keyframes scale {
	100% {
		-moz-transform: scale(1);
	}
}

@keyframes zindex {
	25% {
		z-index: 1;
	}
	75% {
		z-index: -1;
	}
}

@-o-keyframes zindex {
	25% {
		z-index: 1;
	}
	75% {
		z-index: -1;
	}
}

@-ms-keyframes zindex {
	25% {
		z-index: 1;
	}
	75% {
		z-index: -1;
	}
}

@-webkit-keyframes zindex {
	25% {
		z-index: 1;
	}
	75% {
		z-index: -1;
	}
}

@-moz-keyframes zindex {
	25% {
		z-index: 1;
	}
	75% {
		z-index: -1;
	}
}

@keyframes fadeIn {
	100% {
		opacity: 1;
	}
}

@-o-keyframes fadeIn {
	100% {
		opacity: 1;
	}
}

@-ms-keyframes fadeIn {
	100% {
		opacity: 1;
	}
}

@-webkit-keyframes fadeIn {
	100% {
		opacity: 1;
	}
}

@-moz-keyframes fadeIn {
	100% {
		opacity: 1;
	}
}


.m-b {
	width: 100%;
}