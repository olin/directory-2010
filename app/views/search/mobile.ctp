<?php
//Scripts to include in the page
$javascript->link('lib/jquery-1.4.3.min.js', false);
//useful library functions
$javascript->link('lib/utils.js', false);
$javascript->link('ui/search_mobile.js', false);
?>

<meta name="apple-mobile-web-app-capable" content="yes" />

<link rel="stylesheet" href="<?php print $this->base;?>/css/Mobile.css" type="text/css" />

<div id="SearchBar">
<img src="<?php print $this->base;?>/img/search/inactive.png" style="display:none;" />
<img src="<?php print $this->base;?>/img/search/active.png" style="display:none;" />
<img src="<?php print $this->base;?>/img/search/working.gif" style="display:none;" />
<table cellspacing="0" cellpadding="0" width="100%"><tr>
	<td><form><input type="search" autocapitalize="off" autocomplete="off" placeholder="enter query here..." id="searchBox" name="searchBox" /></form></td>
	<td width="4em"><div class="SearchButton"></div></td>
</tr></table>
</div>

<div id="searchResults">
	<div class="ContactCard Person Template" id="searchCardTemplate">
		<div class="Name"><span class="FullName"><span class="FirstName">FirstName</span><span class="NickName"> "Nickname"</span> <span class="LastName">LastName</span></span><span class="Class">20xx</span></div>
		<div class="ContactInfo" style="display:none;">
			<a class="Phone UserAction" href="tel:7815550000"><img src="<?php print $this->base;?>/img/actions/phone.png" border="0" width="32" height="32" /><span class="text">(781)-555-1234</span></a>
			<a class="Email UserAction" href="mailto:user@example.com"><img src="<?php print $this->base;?>/img/actions/mail.png" border="0" width="32" height="32" /></a>
			<div class="Dorm UserAction"><img src="<?php print $this->base;?>/img/actions/dorm.png" border="0" width="32" height="32" />&nbsp;<span class="text">EH<br />000G</span></div>
		</div>
	</div>
</div>
<div class="ContactCard Message" id="searchMessage">
	<div class="Name"><span class="FullName">Message goes here</span></div>
</div>
