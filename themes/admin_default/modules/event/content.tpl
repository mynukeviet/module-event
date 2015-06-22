<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />

<!-- BEGIN: error -->
<div class="alert alert-warning">
	{ERROR}
</div>
<!-- END: error -->

<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<div class="row">
		<div class="col-md-18">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.title}</strong> <span class="red">(*)</span></label>
						<div class="col-sm-20">
							<input class="form-control" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.alias}</strong></label>
						<div class="col-sm-19">
							<input class="form-control" type="text" name="alias" value="{ROW.alias}" id="id_alias" />
						</div>
						<div class="col-sm-1">
							<i class="fa fa-refresh fa-lg icon-pointer" onclick="nv_get_alias('id_alias');">&nbsp;</i>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.catid}</strong> <span class="red">(*)</span></label>
						<div class="col-sm-20">
							<select class="form-control" name="catid">
								<option value=""> ---{LANG.catid_c}--- </option>
								<!-- BEGIN: select_catid -->
								<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
								<!-- END: select_catid -->
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.image}</strong></label>
						<div class="col-sm-16">
							<input class="form-control" type="text" name="image" value="{ROW.image}" id="id_image" />
						</div>
						<div class="col-sm-4">
							<button type="button" class="btn btn-info" id="img_image">
								<i class="fa fa-folder-open-o">&nbsp;</i> {LANG.image_c}
							</button>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.address}</strong> <span class="red">(*)</span></label>
						<div class="col-sm-20">
							<input class="form-control" type="text" name="address" value="{ROW.address}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.quantity}</strong></label>
						<div class="col-sm-20">
							<input class="form-control" type="text" name="quantity" value="{ROW.quantity}" pattern="^[0-9]*$"  oninvalid="setCustomValidity( nv_digits )" oninput="setCustomValidity('')" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.hometext}</strong> <span class="red">(*)</span></label>
						<div class="col-sm-20">
							<textarea class="form-control" style="height:100px;" cols="75" rows="5" name="hometext">{ROW.hometext}</textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.bodytext}</strong> <span class="red">(*)</span></label>
						<div class="col-sm-20">
							{ROW.bodytext}
						</div>
					</div>
					<div class="form-group" style="text-align: center">
						<input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">{LANG.time}</div>
				<div class="panel-body">
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" name="start_time" id="start_time" value="{ROW.start_time}" readonly="readonly" placeholder="{LANG.start_time}">
							<span class="input-group-btn">
								<button class="btn btn-default" type="button" id="start_time-btn">
									<em class="fa fa-calendar fa-fix">&nbsp;</em>
								</button> </span>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" name="end_time" id="end_time" value="{ROW.end_time}" readonly="readonly" placeholder="{LANG.end_time}">
							<span class="input-group-btn">
								<button class="btn btn-default" type="button" id="end_time-btn">
									<em class="fa fa-calendar fa-fix">&nbsp;</em>
								</button> </span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
	//<![CDATA[
	function nv_get_alias(id) {
		var title = strip_tags($("[name='title']").val());
		if (title != '') {
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content&nocache=' + new Date().getTime(), 'get_alias_title=' + encodeURIComponent(title), function(res) {
				$("#" + id).val(strip_tags(res));
			});
		}
		return false;
	}

	$(function() {
		$("#start_time, #end_time").datepicker({
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			showOn : 'focus'
		});
		$('#start_time-btn').click(function(){
			$("#start_time").datepicker('show');
		});
		$('#end_time-btn').click(function(){
			$("#end_time").datepicker('show');
		});
	});

	$("#img_image").click(function() {
		var area = "id_image";
		var path = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
		var currentpath = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});

	//]]>
</script>

<!-- BEGIN: auto_get_alias -->
<script type="text/javascript">
	//<![CDATA[
	$("[name='title']").change(function() {
		nv_get_alias('id_alias');
	});
	//]]>
</script>
<!-- END: auto_get_alias -->
<!-- END: main -->