.hashtag-trending-container {
    margin-bottom: 10px;
    border: 1px solid var(--main-border-color);
    border-radius: 20px;
    display: inline-block;
    padding: 2px 8px 5px 5px;
}
.hashtag-trending-title {
    font-weight: bold;
}
.hashtag-trending-count {
    font-size: 12px;
    margin-top: -3px;
}
.hashtag-trending-container .icon-container { 
    float: left;
    margin-right: 6px;
    margin-top: 6px;
}
.hashtag-trending-container .icon {
    transition-duration: 2s;
    transition-property: transform;
    
	width: 30px;
    height: 30px;
    background: var(--bg-sidebar-icons);
    border-radius: 100%;
    text-align: center;
    padding: 3px;
}
.hashtag-trending-link {
    color: var(--text-color);
}	
.hashtag-trending-link:hover .icon {
    transform: rotate(360deg);
    -webkit-transform: rotate(360deg);
}
.white-darkmode .hashtag-trending-container .icon svg {
  -webkit-filter: invert(100%); 
          filter: invert(100%);	
}