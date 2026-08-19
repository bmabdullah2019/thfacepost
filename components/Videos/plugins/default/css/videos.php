.ossn-video-player {
	max-width:100%;
}
.ossn-video-controls {
	margin-top:20px;
}
.ossn-video-description {
	margin-top:10px;
    margin-bottom:10px;
}
.ossn-videos-list {

}
.ossn-videos-list .video-image {
	height: 200px;
    width: 200px;
}
.ossn-video-item .video-meta-data,
.ossn-video-item .video-image {
	display:inline-block;
}
.ossn-video-item .video-meta-data {
    vertical-align: top;
    padding: 10px;
    word-break: normal;
    width: 60%;
    text-align: justify;
}
.ossn-video-item .video-meta-data .video-title {
    font-size: 18px;
    font-weight: bold;
}
.ossn-video-item {
	margin-bottom:20px;
}
@media (max-width: 480px) {
 	.ossn-video-item .video-meta-data {
    	padding:0px;
        width: 230px;
    }
}
#video-add .progress {
    position: relative;
    margin-top:20px;
}

#video-add .progress span {
    position: absolute;
    display: block;
    width: 100%;
    color: #000;
    font-weight: bold;
 }
#video-add .progress-bar {
    background-color: #33B762;
}
#video-add .conversion,
#video-add .video-upload {
	display:none;
}
.menu-section-videos i:before {
    font-family: 'Font Awesome 5 Free';
    content: "\f03d";
}
.menu-section-item-videos-my:before,
.menu-section-item-videos-all:before {
    font-family: 'Font Awesome 5 Free';
    content: "\f0cb" !important;
} 
.menu-section-item-videos-add:before {
    font-family: 'Font Awesome 5 Free';
    content: "\f067" !important;
} 
.menu-section-item-videos-pending:before {
	content: "\f1ce" !important;
}
.mejs-poster img {
	width:initial;
    max-width:initial;
    border: 0px !important;
}
.video-conversion-title {
	font-size: 20px;
    font-weight: bold;
    color: #6b6b6b;
}
.video-pending {
    background: #f1f1f1;
    padding-top: 0.9rem;
    padding-bottom: 0.9rem;
}
.video-delete-btn {
    color: #ff1919 !important;
}
.video-delete-btn:before {
    content: "\f2ed" !important;
}