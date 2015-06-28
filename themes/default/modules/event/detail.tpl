<!-- BEGIN: main -->
<div id="event_detail">
	<div class="row">
		<div class="col-xs-24 col-sm-8">
			<img src="{DATA.homeimgfile}" class="img-thumbnail" />
		</div>
		<div class="col-xs-24 col-sm-16">
			<ul>
				<li><h2><a href="{DATA.url_event}" title="{DATA.title}">{DATA.title}</a></h2></li>
				<li><em class="fa fa-archive">&nbsp;</em><a href="{DATA.url_cat}" title="{DATA.title_cat}">{DATA.title_cat}</a></li>
				<!-- BEGIN: day -->
				<li><em class="fa fa-clock-o">&nbsp;</em>{LANG.from} {DATA.start_time} {LANG.to} {DATA.end_time} {LANG.day} {DATA.start_date}</li>
				<!-- END: day -->
				<!-- BEGIN: longday -->
				<li><em class="fa fa-clock-o">&nbsp;</em>{LANG.from} {DATA.start_time} {LANG.day} {DATA.start_date} {LANG.to} {DATA.end_time} {LANG.day} {DATA.end_date}</li>
				<!-- END: longday -->
				<li><em class="fa fa-map-marker">&nbsp;</em>{DATA.address}</li>
				<li><em class="fa fa-recycle">&nbsp;</em><span class="label label-info">{DATA.status_str}</span></li>
			</ul>
		</div>
	</div>
	<hr />
	<p><blockquote>{DATA.hometext}</blockquote></p>
	<p>{DATA.bodytext}</p>
</div>
<!-- END: main -->