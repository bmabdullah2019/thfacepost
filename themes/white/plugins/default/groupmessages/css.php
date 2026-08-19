.group-messages-add-friends .token-input-input-token {

}
.group-messages-add-friends #token-input-ossn-wall-friend-input {
    padding: 7px !important;
    margin-bottom: 5px;
    background: #fff;
    border: 0 !important;
}

.group-messages-add-friends ul.token-input-list {
    border: none;
    border: 1px solid #eee;  
}
.group-messages-add-friends .token-input-input-token input {
    padding: 10px !important;
    border: 1px solid #eee !important;
}
.group-messages-append .user-icon {
    border-radius: 100%;
    width: 40px;
    margin-top: 10px;
}
.group-messages-append .time-created {
    display: inline-block;
}
.group-messages-append .message-box-recieved,
.group-messages-append .message-box-sent {
	    padding: 10px 0px;
}
.group-messages-append .message-box-sent {
		padding-right:5px;
        padding-left: 18px;
}
.group-messages-append .message-box-recieved {
     padding-right: 20px;
     padding-left: 18px;
 }
.group-messages-append .message-box-recieved::before,
.group-messages-append .message-box-sent::before {
    display:none;
}
.group-messages-append .ossn-pagination {
	 margin:0;
}
.group-messages-append .container-table-pagination {
    visibility: hidden;
}
.group-messages-append {
    max-height: 400px;
    padding-right: 20px;
    overflow-y: auto;
    overflow-x: hidden; 
 }
 .group-chat-members li {
 	width:50px;
    height:50px;
    display:inline-block; 
    margin-right:5px;
 }
 .group-chat-members img {
 	border-radius:100%;
 }
 .group-chat-controls {
 	float:right;
 }
  .group-chat-controls a i {
  	margin-right:0;
  }
 .group-chat-controls a {
 	margin-right:5px;
 }
.group-chat-member-remove {
	float: left;
    margin-left: 11px;
    margin-top: -44px;
    color: #ff5454;
    font-size: 25px;
    z-index: 1;
    position: relative;
 }
.menu-section-item-groupmessage-membership:before,
.menu-section-item-groupmessage-list:before {
    content: "\f086" !important;
}
.menu-section-item-groupmessage-add:before {
    content: "\f067" !important;
}
.menu-section-groupmessagesroom i:before {
    content: "\f086" !important;
}
.ossn-group-room-message-delete {
    margin-left: 10px;
    color: #c77878 !important;
    visibility: hidden;
}
.group-messages-append  .message-box-sent:hover .ossn-group-room-message-delete {
	 visibility:visible;
}
.message-deleted-grouproom {
    color: #d88585;
    padding-right: 10px;
    font-style: italic;
}
.ossn-gmessage-attach-photo, 
.ossn-gmessage-icon-attachment {
    float: right;
    width: 30px;
    height: 30px;
    cursor: pointer;
    border-radius: 50%;
    text-align: center;
}
.ossn-gmessage-attach-photo .fa-smile {
    float: right;
    position: relative;
    margin-right: 5px;
    margin-top: 5px;
    width: 25px;
    height: 25px;
    padding: 5px;
    cursor: pointer;
    font-weight: 400;
}
.ossn-gmessage-attach-photo .fa-smile {
    font-style: normal;
    font-size: 20px;
    color: var(--text-color);
    margin-top: 0px !important;
}
.ossn-gmessage-attach-photo:hover, 
.ossn-gmessage-icon-attachment:hover {
    background: var(--main-titles-bg-color);
}
.ossn-gmessage-icon-attachment:before {
    content: "\f0c6";
    font-family: 'Font Awesome 5 Free';
    font-style: normal;
    font-weight: bold;
    font-size: 20px;
    color: var(--text-color);
}
.ossn-gomessage-attachment-remove {
    color: red;
    float: right;
    cursor: pointer;
}
.ossn-gmessage-attachment-details {
    background: #eee;
    border: 1px dashed #ccc;
    border-radius: 5px;
    padding: 5px;
    height: 32px;
    margin-bottom: 10px;
    display:none;
}
.ossn-gmessage-show-image-attachment {
    max-width: 200px;
    margin-right: 10px;
    margin-top: 5px;
}	
.ossn-gmessage-attachment {
	margin-right: 10px;
}