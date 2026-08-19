.ossn-search {
   display:inline-block;
   width:60%;
   margin:11px;
   position: relative;
}
.ossn-search input[type='text']{
    width: 100%;
    margin: 0 auto;
    color: var(--text-color);
    border-radius: 30px;
    background: var(--bg-input-bg);
    border: 0;
    padding: 9px 9px 9px 30px;
}
.ossn-search:before { 
	font-family: 'Font Awesome 5 Free';
    content: "\f002";
    font-weight: bold;
    color: var(--text-color);
    position: absolute;
    margin: 10px;
    left: 0;
}
.ossn-search input[type='text']:focus::placeholder {
  color: transparent;
}
.ossn-search:focus-within {
	width:65%;
}
.search-topbar-sm .ossn-search {
	width:70%;
}
.search-topbar-sm .ossn-search:focus-within {
	width:90%;
}