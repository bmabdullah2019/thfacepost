.bootstrap-datetimepicker-widget .timepicker-hour,
.bootstrap-datetimepicker-widget .timepicker-minute,
.bootstrap-datetimepicker-widget .timepicker-second {
	background: #eee;
}

.event-wall-item {
	margin-bottom: 10px;
	margin-top: 0px !important;
}

.ossn-menu-search-event a .text:before {
	font-family: 'Font Awesome 5 Free';
	content: "\f073";
	padding-right: 10px;
	vertical-align: middle;
	float: left;
}

.ossn-notification-icon-calander {
	display: inline-block;
}

.ossn-notification-icon-calander:before {
	content: "\f073";
	font-family: 'Font Awesome 5 Free';
	font-style: normal;
	font-weight: normal;
	font-size: 13px;
}


.menu-section-event i:before {
	font-family: 'Font Awesome 5 Free';
	content: "\f073" !important;
}

.menu-section-item-events-all:before,
.menu-section-item-events-my:before {
	font-family: 'Font Awesome 5 Free';
	content: "\f0cb" !important;
}

.menu-section-item-events-add:before {
	font-family: 'Font Awesome 5 Free';
	content: "\f067" !important;
}

@media only screen and (max-width: 992px) {
	.bootstrap-datetimepicker-widget {
		margin-left: initial !important;
	}
}

@media (max-width: 480px) {
	.bootstrap-datetimepicker-widget {
		margin-left: inttial !important;
	}
}

.event-footer-comments .comments-list {
	margin-left: -10px;
	margin-right: -10px;
	margin-bottom: -10px;
}

.event-footer-comments .like-share {
	margin-left: -10px;
	margin-right: -10px;
}

/******************
 List
*******************/

/* 1. Scoped Parent & Theme Variables */
.events-list-modern {
	display: flex;
	flex-direction: column;
	gap: 25px;
	margin-top: 20px;
	--ev-bg: #ffffff;
	--ev-title: #111827;
	--ev-text: #4b5563;
	--ev-border: #f0f0f0;
	--ev-shadow: rgba(0, 0, 0, 0.06);
}

/* 2. White-Darkmode Support */
html.white-darkmode .events-list-modern {
	--ev-bg: #1c1c1f;
	--ev-title: #ffffff;
	--ev-text: #a1a1aa;
	--ev-border: #333335;
	--ev-shadow: rgba(0, 0, 0, 0.5);
}

/* 3. Card Container */
.events-list-modern .event-list-card {
	display: flex;
	align-items: stretch;
	/* Crucial: makes left and right side equal height */
	background-color: var(--ev-bg);
	border: 1px solid var(--ev-border);
	border-radius: 16px;
	overflow: hidden;
	transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
	text-decoration: none !important;
	box-shadow: 0 4px 12px var(--ev-shadow);
}

.events-list-modern .event-list-card:hover {
	transform: translateY(-8px);
	box-shadow: 0 20px 40px var(--ev-shadow);
}

/* 4. Fixed Image Handling - No more bottom gaps */
.events-list-modern .event-card-image {
	width: 320px;
	min-width: 320px;
	/* Remove fixed height, use stretch */
	align-self: stretch;
	background-color: #000;
	position: relative;
	overflow: hidden;
}

.events-list-modern .event-card-image img {
	position: absolute;
	/* Absolute fill ensures it covers the stretched parent */
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	object-fit: cover;
	object-position: center;
	display: block;
}

/* Pro-Tip Overlay */
.events-list-modern .event-card-image::after {
	content: "";
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 40%, rgba(0, 0, 0, 0.5) 100%);
	pointer-events: none;
	z-index: 1;
}

/* 5. Content Area */
.events-list-modern .event-card-content {
	padding: 25px;
	flex: 1;
	display: flex;
	flex-direction: column;
	justify-content: center;
	/* Centers text if image is taller */
}

.events-list-modern .event-card-content h3 {
	font-size: 22px;
	font-weight: 800;
	margin: 0 0 8px 0;
	color: var(--ev-title) !important;
}

.events-list-modern .event-card-content p {
	font-size: 14px;
	line-height: 1.6;
	margin-bottom: 15px;
	color: var(--ev-text) !important;
}

/* 6. Info Grid */
.events-list-modern .event-card-meta {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 12px;
	margin-bottom: 10px;
}

.events-list-modern .meta-item {
	display: flex;
	align-items: center;
	gap: 8px;
	font-size: 13px;
	color: var(--ev-text) !important;
}

.events-list-modern .meta-item i {
	width: 18px;
	text-align: center;
}

/* 7. Footer & Link */
.events-list-modern .event-card-footer {
	margin-top: 15px;
	display: flex;
	justify-content: flex-end;
}

.events-list-modern .browse-link {
	font-weight: 700;
	font-size: 13px;
	color: #3b82f6 !important;
	display: flex;
	align-items: center;
	gap: 6px;
}

/* Responsive */
@media (max-width: 850px) {
	.events-list-modern .event-list-card {
		flex-direction: column;
	}

	.events-list-modern .event-card-image {
		width: 100%;
		height: 220px;
		/* Return to fixed height only on mobile */
		min-width: 100%;
	}
}

/******************
 View
*******************/
/* Main Card - Scope to prevent leaking */
.event-details-card {
	background: #ffffff;
	border-radius: 12px;
	border: 1px solid #e0e0e0;
	margin-bottom: 25px;
	overflow: hidden;
	box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
	color: #1f2937;
}

/* Dark Mode Support */
html.white-darkmode .event-details-card {
	background: #18181b !important;
	border-color: #333333 !important;
	color: #f4f4f5 !important;
}

/* Top Action Bar (Matches your preferred top) */
.event-details-card .event-action-bar {
	display: flex;
	padding: 5px 20px;
	border-top: 1px solid #f0f0f0;
	border-bottom: 1px solid #f0f0f0;
	background: #fff;
}

html.white-darkmode .event-details-card .event-action-bar {
	background: #18181b;
	border-bottom-color: #333;
}

.event-details-card .title-section {
	padding: 20px 25px 10px 25px;
}

.event-details-card .title-section h2 {
	font-size: 20px;
	font-weight: 800;
	margin: 0;
}

/* Interaction Area: Buttons and Admin */
.event-details-card .interaction-area {
	display: flex;
	display: -webkit-flex;
	justify-content: space-between;
	/* This pushes items to opposite ends */
	align-items: center;
	width: 100%;
	/* Important: Takes full width of the card */
}

.event-details-card .btns-group {
	display: flex;
	gap: 8px;
}

.event-details-card .btn {
	font-weight: 600;
	border-radius: 8px;
	padding: 7px 14px;
	font-size: 13px;
}

.event-details-card .btn-selected-active {
	background: #f1f5f9 !important;
	color: #3b82f6 !important;
	border: 1px solid #3b82f6 !important;
	cursor: default;
}

/* Admin Buttons at far right */
.event-details-card .btns-group {
	display: flex;
	gap: 8px;
}

.event-details-card .admin-controls {
	display: flex;
	gap: 8px;
	/* Optional: add a border to separate it from buttons */
	padding-left: 15px;
	border-left: 1px solid #eee;
}

/* Remove default margins on icons to keep them centered in circles */
.event-details-card .admin-controls i {
	margin: 0 !important;
}

.event-details-card .admin-controls a {
	width: 34px;
	height: 34px;
	border-radius: 50%;
	display: flex;
	align-items: center;
	justify-content: center;
	color: #fff !important;
}

/* --- NEW STACKED BODY LAYOUT --- */
.event-details-card .event-body-content {
	padding: 30px;
}

/* Upper Section: Image and Meta Panel side-by-side */
.event-details-card .event-upper-split {
	display: flex;
	gap: 30px;
	align-items: flex-start;
	margin-bottom: 30px;
}

/* Fixed 250x250 Image with Date Badge */
.event-details-card .event-image-container {
	flex: 0 0 250px;
	position: relative;
	width: 250px;
	height: 250px;
}

.event-details-card .event-fixed-image {
	width: 250px;
	height: 250px;
	border-radius: 12px;
	overflow: hidden;
}

.event-details-card .event-fixed-image img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}

/* Premium Date Badge Overlay */
.event-details-card .date-badge-fixed {
	position: absolute;
	top: 12px;
	left: 12px;
	background: #ffffff;
	border-radius: 8px;
	padding: 6px 10px;
	text-align: center;
	box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
	z-index: 5;
}

html.white-darkmode .event-details-card .date-badge-fixed {
	background: #27272a;
}

.event-details-card .date-badge-fixed .m {
	color: #ef4444;
	font-weight: 800;
	font-size: 11px;
	display: block;
	text-transform: uppercase;
	line-height: 1;
}

.event-details-card .date-badge-fixed .d {
	font-size: 20px;
	font-weight: 900;
	display: block;
	line-height: 1.1;
	color: #111;
}

html.white-darkmode .event-details-card .date-badge-fixed .d {
	color: #fff;
}

/* Condensed Meta Panel (to the right of the image) */
.event-details-card .meta-info-panel {
	flex: 1;
	background: #f8fafc;
	border: 1px solid #e2e8f0;
	border-radius: 12px;
	padding: 20px;
	height: 250px;
	/* Match image height */
	display: flex;
	flex-direction: column;
	justify-content: space-around;
}

html.white-darkmode .event-details-card .meta-info-panel {
	background: #212124;
	border-color: #3f3f46;
}

.event-details-card .info-item {
	display: flex;
	gap: 12px;
	align-items: center;
}

.event-details-card .info-item i {
	font-size: 18px;
	color: #3b82f6;
	width: 20px;
	text-align: center;
}

.event-details-card .info-label {
	display: block;
	font-size: 11px;
	font-weight: 700;
	color: #94a3b8;
	text-transform: uppercase;
}

.event-details-card .info-val {
	font-weight: 600;
	font-size: 15px;
	display: block;
}

/* --- DESCRIPTION SECTION BELOW --- */
.event-details-card .event-description-section {
	border-top: 1px solid #f1f5f9;
	padding-top: 30px;
	margin-top: 10px;
}

html.white-darkmode .event-details-card .event-description-section {
	border-top-color: #3f3f46;
}

.event-details-card .description-header {
	font-size: 14px;
	font-weight: 700;
	color: #94a3b8;
	text-transform: uppercase;
	margin-bottom: 15px;
}

.event-details-card .event-description-text {
	font-size: 16px;
	line-height: 1.8;
	color: #4b5563;
	/* No fixed height, no overflow - all text is visible */
}

html.white-darkmode .event-details-card .event-description-text {
	color: #d1d5db;
}

/* Stats Footer */
.event-details-card .event-stats-footer {
	display: flex;
	background: #f8fafc;
	border-top: 1px solid #f0f0f0;
}

html.white-darkmode .event-details-card .event-stats-footer {
	background: #141416;
	border-top-color: #333;
}

.event-details-card .stat-pill {
	flex: 1;
	padding: 15px;
	text-align: center;
	cursor: pointer;
}

.event-details-card .stat-pill:last-child {
	border: none;
}

.event-details-card .stat-num {
	display: block;
	font-size: 22px;
	font-weight: 800;
}

.event-details-card .stat-txt {
	font-size: 11px;
	font-weight: 600;
	color: #64748b;
	text-transform: uppercase;
}

@media (max-width: 850px) {
	.event-details-card .event-action-bar {
		flex-direction: column;
		align-items: flex-start;
	}

	.event-details-card .interaction-area {
		width: 100%;
		justify-content: space-between;
		margin-top: 10px;
	}

	.event-details-card .event-upper-split {
		flex-direction: column;
		align-items: center;
	}

	.event-details-card .meta-info-panel {
		width: 100%;
		height: auto;
	}
}

/****
  Calendar
**********/
.menu-section-item-events-calendar:before {
	content: "\f784" !important
}

@media (min-width: 1200px) {
	.ec-event-title {
		font-size: .85em;
		line-height: 1.5;
	}
}

.ec-day-grid .ec-body .ec-day {
	min-height: 5em !important;
}