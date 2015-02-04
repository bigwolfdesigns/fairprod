<style>
    .required_field{
        border-color: red;
    }

	#overlay {
		position: fixed; 
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: #000;
		opacity: 0.5;
		filter: alpha(opacity=50);
	}
	#modal {
		position:absolute;
		background:url(tint20.png) 0 0 repeat;
		background:rgba(0,0,0,0.2);
		border-radius:14px;
		padding:8px;
	}

	#content {
		border-radius:8px;
		background:#fff;
		padding:20px;
	}
	#close {
		position:absolute;
		background:url(images/delete.gif) 0 0 no-repeat;
		width:24px;
		height:27px;
		display:block;
		text-indent:-9999px;
		top:-7px;
		right:-7px;
	}

</style>
<?php echo$this->get_top_links() ?>
<span class="headingsblue"><?php echo ucwords($this->action)." ".$this->display_table_name ?></span>
<?php
$delete_gif	 = 'images/delete.gif';
$edit_gif	 = 'images/edit.gif';
if(is_array($this->error) && count($this->error) > 0){
	?>
	<div class="error">
		<?php
		foreach($this->error as $message){
			?>
			<div style="margin:20px;padding:10px;font-size:18px;color:#990000;background-color:#effdbd;text-align:center;border:2px solid #990000;"><?php echo $message; ?></div>
			<?php
		}
		?>
	</div>
<?php }
?>
<div style='padding-top: 20px'>
	<?php
	if($this->action == 'edit'){
		?>
		<a style='color:red' href="<?php echo $this->get_ctrl_url()."&action=delete&id=".$this->id ?>" onclick = "return confirm_delete('Are you sure you want to delete this record?')">Delete this Record</a>
	<?php } ?>
    <form action="<?php echo $form_url ?>" method="POST" id='edit_form'>
        <!--<h2><?php echo ucwords($this->action).' '.$this->display_table_name ?></h2>-->
        <table width="800" border="0" cellspacing="8" id='edit_table'>
			<?php foreach($fields as $k => $v){ ?>
				<tr>
					<td>
						<div align="right"><?php echo$v; ?></div>
					</td>
					<td>
						<?php echo $this->make_form_field($k, $this->get_form_value($k, isset($values[$k])?$values[$k]:'')); ?>
					</td>
				</tr>
			<?php } ?>
            <tr>
                <td>
                    <div align="right"></div>
                </td>
                <td>
                    <input type="submit" name="submitted" value="<?php echo ucwords($this->action)." Record" ?>"/>

                </td>
            </tr>
        </table>
    </form>
</div>
<?php
// only show related tables on the edit function
if($this->action == 'edit'){
	$sub_id = $this->id;
	if(isset($related) && count($related) > 0){
		foreach($related as $linking_id => $table){
			?>
			<div class='table_identification' id='<?php echo $table ?>' data-sub_id_field='<?php echo $linking_id ?>'>
				<div class="blank_slate" style="display: none">
					<table>
						<tr id="blank_<?php echo $table ?>" class="table_form_serialize" >
								<!--<td align="left"><span class="<?php echo "table_".$table."_field_id" ?>"></span></td>-->
							<?php
							foreach($related_fields[$table] as $k => $field){
								$value = ($linking_id == $k)?$this->id:'';
								?>
								<td align="left"><?php echo $this->make_form_field($k, $this->get_form_value($k, $value, true), $table); ?></td>
							<?php } ?>
							<td colspan= '2' align="center" class='remove_me_after_adding'><a href="javascript:void(0)" class="submit_add">Submit</a></td>
						</tr>
					</table>
				</div>
				<?php
				$col_count = 2; // for edit and delete
				?>
				<h4><?php echo ucwords(str_replace('_', ' ', $table)) ?></h4>
				<hr>
				<div width='100%' style='overflow:scroll'>
					<table align="center" cellspacing="0" cellpadding="5" width="100%" id='table_<?php echo $table ?>'>
						<thead>
							<tr>
									<!--<th align="left"><b>ID</b></th>-->
								<?php
								foreach($related_fields[$table] as $field){
									$col_count ++;
									?>
									<th align="left"><b><?php echo $field ?></b></th>
								<?php }
								?>
								<th align="center">Edit</th>
								<th align="center">Delete</th>
							</tr>

						</thead>
						<tbody>
							<?php
							if(count($related_values[$table]) > 0){
								foreach($related_values[$table] as $k => $row){
									$id					 = $row[$this->get_primary_id_col($table)];
									$background_color	 = ($k % 2 == 0)?"#EEEEEE":"#FFFFFF";
									?>
									<tr style="background-color: <?php echo $background_color ?>" class="table_form_serialize" id="table_<?php echo $table ?>_id_<?php echo $id ?>">
								<input name='ajax_code' type='hidden' value='<?php echo $this->ajax_key('MY_AJAX_KEY'); ?>'/>
								<?php
								foreach($row as $key => $info){
									if(isset($related_fields[$table][$key])){
										$extras = '';
										if($key == $this->get_primary_id_col($table)){
											$extras = 'disabled';
										}
										?>
										<td align="left"><?php echo $this->make_form_field($key, $this->get_form_value($key, $info, true), $table, $extras); ?></td>
									<?php }
								}
								?>
								<td align="center"><a href="javascript:void(0)" class="edit_row" id='edit_<?php echo $id ?>'><img src="<?php echo $edit_gif ?>"/></a></td>
								<td align="center"><a href="javascript:void(0)" class="delete_row"  id='delete_<?php echo $id ?>'><img src="<?php echo $delete_gif ?>"/></a></td>
								</tr>
								<?php
							}
						}
						?>
						<tr id="<?php echo $table ?>_add_before_me"><td colspan="<?php echo $col_count ?>"><a href="javascript:void(0)" class="add_row" >Add a row on this table</a></td></tr>
						</tbody>
					</table>
				</div>
			</div>
			<?php
		}
	}
}
?>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.1.min.js">
</script>
<script src="tpl/js/filetree/jquery.easing.js" type="text/javascript"></script>
<script src="tpl/js/filetree/jqueryFileTree.js" type="text/javascript"></script>
<link href="tpl/css/filetree/jqueryFileTree.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript">
		var ajax_code='<?php echo $this->ajax_key('MY_AJAX_KEY'); ?>';
		delete_gif_uri='<?php echo $delete_gif ?>';
		edit_gif_uri='<?php echo $edit_gif ?>';
		ajax_path='<?php echo $this->get_ajax_api(); ?>';
		link_id='<?php echo $sub_id ?>';
		$(function(){
			$('.table_identification').on('click', '.edit_row', function(){
				var id=$(this).attr('id').substring('edit_'.length, $(this).attr('id').length);
				url=ajax_path+'&table=';
				url+=$(this).closest('.table_identification').attr('id');
				url+='&action=edit&id=';
				url+=id;
				selector_id='table_'+$(this).closest('.table_identification').attr('id')+'_id_'+id;
				var disabled=$('#'+selector_id+' :input:disabled').removeAttr('disabled');
				// serialize the form
				var serialized=$('#'+selector_id+' :input').serialize();

				// re-disabled the set of inputs that you previously enabled
				disabled.attr('disabled', 'disabled');
				$.post(url, serialized, function(data){
					if(!data.error){
						alert("The update has been successful.");
					}else{
						error_str='';
						for(var k in data){
							error_str+=data[k]+'\n';
						}
						alert(error_str);
					}
				}, 'json');
			});
			$('.add_row').click(function(){
				table_name=$(this).closest('.table_identification').attr('id');
				add_inputs=$('#blank_'+table_name).clone(true);
				var random_id=makeid();
				add_inputs.attr('id', 'table_'+table_name+'_id_'+random_id);
				add_inputs.find('.submit_add').attr('id', random_id);
				$('#'+table_name+'_add_before_me').before(add_inputs);
			});
			$('.submit_add').click(function(){
				table_name=$(this).closest('.table_identification').attr('id');
				url=ajax_path+'&table=';
				url+=$(this).closest('.table_identification').attr('id');
				url+='&action=add';
				selector_id='table_'+table_name+'_id_'+$(this).attr('id');
				var disabled=$('#'+selector_id+' :input:disabled').removeAttr('disabled');
				// serialize the form
				var serialized=$('#'+selector_id+' :input').serialize();

				// re-disabled the set of inputs that you previously enabled
				disabled.attr('disabled', 'disabled');
				$.post(url, serialized, function(data){
					if(!data['error']){
						new_selector_id='table_'+table_name+'_id_'+data.id;
						$('#'+selector_id).attr('id', new_selector_id);
						$('#'+new_selector_id+' .remove_me_after_adding').remove();
						var edit_a=$('<a />')
								.addClass('edit_row')
								.attr('href', 'javascript:void(0);')
								.attr('id', 'edit_'+data.id)
								.html("<img src='"+edit_gif_uri+"'/>");
						var edit_td=$('<td />')
								.attr('align', 'center')
								.append(edit_a);
						var delete_a=$('<a />')
								.addClass('delete_row')
								.attr('id', 'delete_'+data.id)
								.attr('href', 'javascript:void(0);')
								.html("<img src='"+delete_gif_uri+"'/>");
						var delete_td=$('<td />')
								.attr('align', 'center')
								.append(delete_a);
						$('#'+new_selector_id)
								.append(edit_td)
								.append(delete_td);
						$('#'+new_selector_id+' .table_'+table_name+'_field_id').html(data.id);
						alert("Row added successfully.");

					}else{
						error_str='';
						for(var k in data){
							error_str+=data[k]+'\n';
						}
						alert(error_str);
					}
				}, 'json');
			});
			$('.table_identification').on('click', '.delete_row', function(){
				if(confirm_delete('Are you sure you want to delete this row?')){
					var id=$(this).attr('id').substring('delete_'.length, $(this).attr('id').length);
					url=ajax_path+'&table=';
					url+=$(this).closest('.table_identification').attr('id');
					url+='&action=delete&id=';
					url+=id;
					table_name=$(this).closest('.table_identification').attr('id');
					selector_id='table_'+table_name+'_id_'+id;
					var disabled=$('#'+selector_id+' :input:disabled').removeAttr('disabled');
					// serialize the form
					var serialized=$('#'+selector_id+' :input').serialize();

					// re-disabled the set of inputs that you previously enabled
					disabled.attr('disabled', 'disabled');
					selector_id='table_'+$(this).closest('.table_identification').attr('id')+'_id_'+id;
					$.post(url, serialized, function(data){
						if(!data.error){
							alert("Row deleted.");
							$('#'+selector_id).fadeOut();
						}else{
							error_str='';
							for(var k in data){
								error_str+=data[k]+'\n';
							}
							alert(error_str);
						}
					}, 'json');
				}
			});
		});
		function makeid(){
			var text="";
			var possible="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
			for(var i=0; i<5; i++){
				text+=possible.charAt(Math.floor(Math.random()*possible.length));
			}
			return text;
		}
		function confirm_delete(txt){
			return confirm(txt);
		}

		var lastFocus=null;
		$('#edit_form').find(':text,:radio,:checkbox,select,textarea').focus(function(){
			lastFocus=$(this);
		});

		var modal=(function(){
			var
					method={},
					$overlay,
					$modal,
					$content,
					$close;


			$overlay=$('<div id="overlay"></div>');
			$modal=$('<div id="modal"></div>');
			$content=$('<div id="content"></div>');
			$close=$('<a id="close" href="#">close</a>');

			$modal.hide();
			$overlay.hide();
			$modal.append($content, $close);

			$(document).ready(function(){
				$('body').append($overlay, $modal);
			});
			method.center=function(){
				var top, left;

				top=Math.max($(window).height()-$modal.outerHeight(), 0)/2;
				left=Math.max($(window).width()-$modal.outerWidth(), 0)/2;

				$modal.css({
					top: top+$(window).scrollTop(),
					left: left+$(window).scrollLeft()
				});
			};
			method.open=function(settings){
				$content.empty().append(settings.content);

				$modal.css({
					width: settings.width||'auto',
					height: settings.height||'auto'
				});

				method.center();

				$(window).bind('resize.modal', method.center);

				$modal.show();
				$overlay.show();
			};
			method.close=function(){
				if(lastFocus!==null){
					lastFocus.focus();
				}
				$modal.hide();
				$overlay.hide();
				$content.empty();
				$(window).unbind('resize.modal');
			};
			$close.click(function(e){
				e.preventDefault();
				method.close();
			});
			return method;
		}());

		//Show Files for images/docs etc.
		$('body').on("click", ".need_file_path", function(){
			var $input=$(this);
			modal.open({
				content: "<p>Select a file..</p><div id='file_tree'></div>"
			});
			$('#file_tree').fileTree(
					{
						script: ajax_path+"&table=users&action_callback=list_files"
					},
			function(file){
				$input.val(file);
				modal.close();
			});

		});


</script>
