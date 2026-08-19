.fbstories-outside-container {
	text-align:center;
}

.fbstories-items {
  height: 210px;
    overflow: hidden;
    overflow-x: scroll;
    cursor: pointer;
    white-space: nowrap;
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */    
      
}
.fbstories-items::-webkit-scrollbar {
  display: none;
}

.fbstories-items .fbstories-item-add,
.fbstories-items .fbstories-item {
  width: 115px;
  height: 200px;
  display: inline-block;
  border-radius: 10px;
  border: 1px solid #dddfee;
  background: #333;
  cursor: pointer;
  margin-right: 10px;
  position:relative;
}

.fbstories-items .fbstories-item {
  width: 115px;
  height: 200px;
  display: inline-block;
  border-radius: 10px;
  cursor: pointer;
  border:0px;
  background:#D9D9DFFF;
  margin-right: 10px;
}

.fbstories-items .fbstories-item .user-image {
  position: absolute;
  width: 40px;
  height: 40px;
  border: 4px solid #3578e5;
  border-radius: 50%;
  float: left;
  margin-left: 10px;
  margin-top: 10px;
  z-index:9;
}
.fbstories-items .fbstories-item .user-name {
    position: absolute;
    float: left;
    margin-left: 10px;
    margin-top: 10px;
    color: #fff;
    font-weight: bold;
    text-overflow: ellipsis;
    width: 98px;
    flex-wrap: wrap;
    overflow: hidden;
    word-wrap: normal;
    white-space: nowrap;
    margin-top: 170px;
}
.fbstories-items .fbstories-item-add .image0th,
.fbstories-items .fbstories-item .image0th {
  float: left;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  position:relative;
}

.fbstories-items .fbstories-item .image0th img {
	max-width: 100%;
    position: absolute;
    width: 100%;
    height: 100%;
    user-select: none;
    border:1px solid #D9D9DFFF;
    pointer-events: none;
    border-radius: 10px;
    top: 0;
    right: 0;
    bottom: 0;
    object-fit: cover;
    left: 0;    
}
.fbstorie-item-delete {
    font-size: 15px;
    color: #fff;
    float: right;
    z-index: 3;
    position: relative;
    cursor: pointer;
    opacity: 1;
	margin-top: 5px;
    margin-right: 5px;    
}
.fbstories-items .fbstories-item .user-image img {
  border-radius: 50%;
}
.users-information {
  position: absolute;
  background: rgba(51, 51, 51, 0.29);
  width: 100%;
  left: 0;
  padding-top: 10px;
  z-index: 2;
  padding-bottom: 10px
}

.users-information-inner {
  padding-left: 10px;
  padding-right: 10px;
}

.users-information .user-image {
  border-radius: 100%;
  border: 1px solid #fff;
  display: inline-block;
  float: left;
}

.users-information .user-image img {
  border-radius: 100%;
}

.users-information .user-name-time {
  display: inline-block;
  color: #fff;
  margin-left: 8px;
  margin-top: 1px;
}

.users-information .user-name-time .name {
  display: block;
}

.users-information .user-name-time .time {
  font-size: 12px;
}

.fbstories-container {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 10000;
  background-color: rgba(43, 43, 43, 0.75);
  cursor: auto;
  height: 100%;
}

.fbstories-container-inner {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  margin-left: auto;
  margin-right: auto;
  z-index: 60000;
}

.status-bar {
  background: #ccc !important;
  height: 5px !important;
  border-radius: 0 !important;
  border: 0 !important;
  z-index: 10 !important;
  position: relative;
  margin-left: -10px;
  margin-right: -10px;
  opacity:0.9;
  border-top-left-radius: 5px !important;
  border-top-right-radius: 5px !important;
}

.status-bar .ui-progressbar-value {
	border:0px !important;
    margin:0 !important;
        height: 6px !important;
        
}
.status-bar  .ui-progressbar .ui-progressbar-value {
	margin:0 !important;
}
.fbstories-container .slideshow-container {
  width: 300px;
  height: 500px;
  position: relative;
  margin: auto;
  background: #333;
  border-radius: 5px;
  margin-top: 10px;
  padding: 10px;
  padding-top: 0px;
}

.fbstories-container .slideshow-container .close {
  font-size: 15px;
  color: #fff;
  float: right;
  z-index: 3;
  position: relative;
  cursor: pointer;
  opacity: 1;
   margin-top: 7px;
}

.fbstories-container .slideshow-container .fbstories-slides img {
  max-width: 100%;
  max-height: 100%;
  position: absolute;
  top: 0;
  bottom: 0;
  margin: auto;
  right: 0;
  left: 0;
}

.fbstories-slides {
  display: none;
}

.fbstories-container .prev {
  left: 0;
}

.fbstories-container .next {
  right: 0;
}

.fbstories-container .prev,
.fbstories-container .next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  margin-top: -22px;
  padding: 16px;
  color: white;
  font-weight: bold;
  font-size: 18px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
}

.fbstories-container .next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

.fbstories-container .prev:hover,
.fbstories-container .next:hover {
  background-color: rgba(0, 0, 0, 0.8);
}

.fbstories-container .text {
  color: #f2f2f2;
  font-size: 15px;
  position: absolute;
  bottom: 0px;
  width: 100%;
  text-align: center;
  background: rgba(51, 51, 51, 0.29);
  padding: 5px;
  padding-right: 0;
  padding-left: 0;
  left: 0;
  right: 0;
}

.fbstories-container .total-images {
  color: #f2f2f2;
  font-size: 12px;
  top: 0;
  z-index: 1;
  margin-top: 20px;
  float: right;
}

.fbstories-container .fadefbstories {
  -webkit-animation-name: fadefbstories;
  -webkit-animation-duration: 1.5s;
  animation-name: fadefbstories;
  animation-duration: 1.5s;
}

@-webkit-keyframes fadefbstories {
  from {
    opacity: .4
  }
  to {
    opacity: 1
  }
}

@keyframes fadefbstories {
  from {
    opacity: .4
  }
  to {
    opacity: 1
  }
}
.fbstories-paginate-item .story-viewed .user-image {
	border: 4px solid #d2d2d2;
}
@media only screen and (max-device-width: 480px) {
	
	.fbstories-items .fbstories-item-add,
	.fbstories-items .fbstories-item {
    	width: 100px;
    	height: 150px;
        margin-right:0px;
	}
	.fbstories-items {
    	height: 160px;
	}	
    .fbstories-items .fbstories-item .user-name {
        margin-top: 120px;
        width: 80px;
    }
}

.fbstories-add-story {
	color:#fff;
    font-size:20px;
}
.stories-container {
    margin-bottom: 10px;
}
.stories-container .title {
    font-weight: bold;
    margin-bottom: 5px;
}
.stories-view-all {
    float: right;
    padding-right: 15px;
}