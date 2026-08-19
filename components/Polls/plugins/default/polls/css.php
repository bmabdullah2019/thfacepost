.ossn-polls {}

.ossn-polls-form-questions .progress {
	height: 20px;
	font-weight: bold;
}

.ossn-polls-has-no-progress {
	color: #000;
}

.ossn-polls-form-questions .panel-body {
	padding-top: 0;
	padding-bottom: 0;
}

.ossn-poll-loading-submit {
	margin: 0 auto;
	width: 40px;
	display: none;
}

.ossn-polls-form-questions label.btn span {
	font-size: 1.5em;
}

.ossn-polls-form-questions label input[type="radio"]~i.fa.fa-circle-o {
	color: #c8c8c8;
	display: inline;
}

.ossn-polls-form-questions label input[type="radio"]~i.fa.fa-dot-circle-o {
	display: none;
}

.ossn-polls-form-questions label input[type="radio"]:checked~i.fa.fa-circle-o {
	display: none;
}

.ossn-polls-form-questions label input[type="radio"]:checked~i.fa.fa-dot-circle-o {
	color: #0b769c;
	display: inline;
}

.ossn-polls-form-questions label:hover input[type="radio"]~i.fa {
	color: #0b769c;
}

.ossn-polls-form-questions label input[type="checkbox"]~i.fa.fa-square-o {
	color: #c8c8c8;
	display: inline;
}

.ossn-polls-form-questions label input[type="checkbox"]~i.fa.fa-check-square-o {
	display: none;
}

.ossn-polls-form-questions label input[type="checkbox"]:checked~i.fa.fa-square-o {
	display: none;
}

.ossn-polls-form-questions label input[type="checkbox"]:checked~i.fa.fa-check-square-o {
	color: #0b769c;
	display: inline;
}

.ossn-polls-form-questions label:hover input[type="checkbox"]~i.fa {
	color: #0b769c;
}

.ossn-polls-form-questions div[data-toggle="buttons"] label.active {
	color: #0b769c;
}

.ossn-polls-form-questions div[data-toggle="buttons"] label {
	display: inline-block;
	padding: 6px 12px;
	margin-bottom: 0;
	font-size: 14px;
	font-weight: normal;
	line-height: 2em;
	text-align: left;
	white-space: nowrap;
	vertical-align: top;
	cursor: pointer;
	background-color: none;
	border: 0px solid #c8c8c8;
	border-radius: 3px;
	color: #c8c8c8;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	-o-user-select: none;
	user-select: none;
}

.ossn-polls-form-questions div[data-toggle="buttons"] label:hover {
	color: #0b769c;
}

.ossn-polls-form-questions div[data-toggle="buttons"] label:active,
div[data-toggle="buttons"] label.active {
	-webkit-box-shadow: none;
	box-shadow: none;
}

.ossn-polls-item {
	background: #fbfbfb;
	border-top: 1px solid #eee;
	padding-top: 5px;
	padding: 5px 10px 10px;
}

.btn-group-vertical {
	margin-top: 10px;
}

.ossn-poll-main {
	background: #fff;
	padding: 15px;
}

.menu-section-polls i:before {
	font-family: 'Font Awesome 5 Free';
	content: "\f080" !important;
}

.menu-section-item-polls-my:before,
.menu-section-item-polls-all:before {
	font-family: 'Font Awesome 5 Free';
	content: "\f080" !important;
}


.poll-container {
	margin-bottom: 20px;
	background-color: #fff;
	border: 1px solid transparent;
	border-radius: 4px;
	-webkit-box-shadow: 0 1px 1px rgb(0 0 0 / 5%);
	box-shadow: 0 1px 1px rgb(0 0 0 / 5%);
	border-color: #ddd;
}

.poll-body {}

.poll-title {
	color: #333;
	background-color: #f5f5f5;
	border-color: #ddd;
	font-size: 16px;
	font-weight: bold;
	padding: 10px 15px;
	border-bottom: 1px solid transparent;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
}

.poll-footer {
	padding: 10px 15px;
	background-color: #f5f5f5;
	border-top: 1px solid #ddd;
	border-bottom-right-radius: 3px;
	border-bottom-left-radius: 3px;
}
.poll-votes-total {
    font-size: 13px;
    margin-left: 5px;
    float: right;
}

.poll-label {
    color: black;
    z-index: 1;
    position: relative;
    margin-left: 40px;
    max-width: 75%;
    padding-top: 10px;
    padding-bottom: 10px;  
}
.poll-percent {
    color: black;
    z-index: 1;
    position: absolute;
    right: 10px;
}
.poll-container .progress {
    min-height: 32px;
    position: relative !important;
    margin-top: 15px;
    height: auto;
}
.poll-container  .progress-bar {
	width: 5%;
    background-color: rgb(191 191 191 / 58%);
	text-align: left;
    white-space: break-spaces;    
}
.poll-progress-highlight {
	background-color: rgba(29, 155, 240, 0.58) !important;
}
.progress-bar-no-votes {
	background:#ffffff00 !important;
}
.polls-progress-bar-absol {
    position: absolute;
    height: 100%;	
}
.poll-check {
    z-index: 1;
    position: absolute;
    margin-left: 10px;
    margin-top: -4px;
    white-space: nowrap;
}
.poll-check input[type="checkbox"]{
    display: inline-block;
    width: 20px;
    height: 20px;
    cursor: pointer;
}
.poll-check input[type="checkbox"]:not(:checked){
	background:#fff;
}
.polls-show-voters {
	cursor:pointer;
}	