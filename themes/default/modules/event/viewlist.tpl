<!-- BEGIN: main -->

<!-- BEGIN: data -->
<div id="event">
	<!-- BEGIN: loop -->
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-7">
					<a href="{DATA.url_event}" title="{DATA.title}"><img src="{DATA.imgsource}" alt="{DATA.homeimgalt}" class="img-thumbnail" /></a>
				</div>
				<div class="col-md-17">
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
		</div>
	</div>
	<!-- END: loop -->
</div>

<!-- BEGIN: page -->
<div class="text-center">{PAGE}</div>
<!-- END: page -->

<!-- END: data -->

<!-- END: main -->