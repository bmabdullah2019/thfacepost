<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo ossn_site_url("administrator/settings/customfields?cpage=list");?>"><i class="fas fa-list"></i> <?php echo ossn_print('admin:customfields');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo ossn_site_url("administrator/settings/customfields?cpage=add");?>"><i class="fas fa-plus"></i> <?php echo ossn_print('customfield:add');?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo ossn_site_url("administrator/settings/customfields?cpage=about");?>"><i class="fa-solid fa-file-lines"></i> <?php echo ossn_print('customfield:aboutpage');?></a>
        </li>        
      </ul>
    </div>
</nav>
<div class="margin-top-10"></div>
<?php
$page = input('cpage', '', 'list');
if(!custom_fields_init()){
	$page = 'init';
}
switch($page){
	case 'init':
		echo ossn_plugin_view('customfields/init');	
	break;
	case 'list':
		echo ossn_plugin_view('customfields/list');
	break;
	case 'about':
		echo ossn_plugin_view('customfields/about_add');
		break;
	case 'add':
		echo ossn_plugin_view('customfields/add');
		break;
	case 'edit':
		echo ossn_plugin_view('customfields/edit');
		break;		
}	