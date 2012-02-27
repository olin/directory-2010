<?php
//Scripts to include in the page
$javascript->link('lib/jquery-1.4.3.min.js', false);
//useful library functions
$javascript->link('lib/utils.js', false);
$javascript->link('ui/search.js', false);
?>

<div id="SearchBar">
	<input class="LiveSearch" id="searchBox" name="searchBox" />
	<div class="ViewFormat ShortcutsBar RoundLast">
		</a><a href="#" class="selected" title="Show results as business cards" onclick="setViewMode('cards',this); return false;">
			<img class="NoHover" width="18" height="14" src="<?php print $this->base;?>/img/search/viewmode/card-inactive.png"></img>
			<img class="Hover" width="18" height="14" src="<?php print $this->base;?>/img/search/viewmode/card-active.png"></img>
		</a><a href="#"title="Show results as large photos"  onclick="setViewMode('faces',this); return false;">
			<img class="NoHover" width="18" height="14" src="<?php print $this->base;?>/img/search/viewmode/face-inactive.png"></img>
			<img class="Hover" width="18" height="14" src="<?php print $this->base;?>/img/search/viewmode/face-active.png"></img>
		</a>
	</div>
	<div class="Year ShortcutsBar" title="Click a class year to show only students in that class.">
		<a href="#q=class:2011" title="Search for the Class of 2011" rel="class:2011">2011</a><a href="#q=class:2012" title="Search for the Class of 2012" rel="class:2012">2012</a><a href="#q=class:2013" title="Search for the Class of 2013" rel="class:2013">2013</a><a href="#q=class:2014" title="Search for the Class of 2014" rel="class:2014">2014</a>
	</div>
	<div class="Dorm ShortcutsBar" title="Click on a dorm to see only students in that dorm.">
		<a href="#q=dorm:EH" title="Search for everybody in East Hall" rel="dorm:EH">EH</a><a href="#q=dorm:WH" title="Search for everybody in West Hall" rel="dorm:WH">WH</a>
	</div>
</div>

<div class="SearchStatus Reminder" id="searchStatusDefault">Start typing above to search for students</div>
<div class="SearchStatus Reminder" id="searchStatus">
	<span id="searchText"></span>
	<span id="download"> - download this search as a <a href="#" class="CSV">csv</a> or as <a href="#" class="vCard">vCards</a></span>
</div>

<div id="searchResults">
	<div class="ContactCard Template" id="searchCardTemplate" tabindex="0">
		<div class="Name"><span class="FullName"><span class="FirstName">FirstName</span><span class="NickName"> "Nickname"</span> <span class="LastName">LastName</span></span><span class="Class">20xx</span></div>
		<img alt="FirstName LastName" class="Profile" src="<?php print $this->base;?>/img/u/unknown.jpg" title="FirstName LastName" />
		<div class="ContactInfo">
			<p class="Headline"><a href="#">(555) 555-5555</a><br /><span class="Dorm">Lives in <span class="DormName">EH</span> <span class="RoomNumber">999Z</span></span></p>
			<p class="IM">
				<a class="Template" href="proto:sn" title="type: sn"><img src="<?php print $this->base;?>/img/contact/type.png" border="0" /><span class="Details">type: sn<br /></span></a>
			</p>
			<p class="Campus Address">MB <span class="MailBox">999</span>, 1000 Olin Way<br />Needham, MA 02492</p>
		</div>
	</div>
</div>
