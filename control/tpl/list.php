<style>
    .pagination{
        display: inline-block;
        padding-left: 0;
        margin: 20px 0;
        border-radius: 4px;
    }
    .pagination>li{
        display:inline;
    }
    .pagination>li:first-child>a, .pagination>li:first-child>span {
        margin-left: 0;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    .pagination>.disabled>a, .pagination>.disabled>a:hover, .pagination>.disabled>a:focus {
        color: #777;
        background-color: #fff;
        border-color: #ddd;
    }
    .pagination>.disabled>span, .pagination>.disabled>span:hover, .pagination>.disabled>span:focus {
        color: #777;
        cursor: not-allowed;
        background-color: #fff;
        border-color: #ddd;
    }
    .pagination>li>a, .pagination>li>span {
        position: relative;
        float: left;
        padding: 6px 12px;
        margin-left: -1px;
        line-height: 1.42857143;
        color: #428bca;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd;
    }
    .pagination>.active>a, .pagination>.active>span, .pagination>.active>a:hover, .pagination>.active>span:hover, .pagination>.active>a:focus, .pagination>.active>span:focus {
        z-index: 2;
        color: #fff;
        cursor: default;
        background-color: #428bca;
        border-color: #428bca;
    }
    .ellipsis {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        display:block;
    }
</style>
<?php echo $this->get_top_links() ?>
<span class="headingsblue"><?php echo $this->display_table_name ?></span>
<?php
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
<?php } ?>
<!--<h2><?php echo $this->display_table_name." List" ?></h2>-->

<?php $this->view('filters'); ?>
<div style="float:left;width:44%"><?php echo $this->pagination(); ?></div>
<div style='clear:both'></div>
<div width='100%' style='overflow:scroll'>
    <table align="center" cellspacing="0" cellpadding="5" width="100%">
        <thead>
            <tr>
				<?php foreach($fields as $field){ ?>
					<th align="left"><b><?php echo $field ?></b></th>
				<?php }
				?>
                <th align="center">Edit</th>
                <th align="center">Delete</th>
            </tr>

        </thead>
        <tbody>
			<?php
			if($rows && count($rows) > 0){
				foreach($rows as $k => $row){
					$id					 = $row[$this->get_primary_id_col()];
					$background_color	 = ($k % 2 == 0)?"#EEEEEE":"#FFFFFF";
					?>
					<tr style="background-color: <?php echo $background_color ?>">
						<?php
						foreach($row as $k => $info){
							$ellipsis = false;
							if(strlen($info) > 15){
								$ellipsis = true;
							}
							if($k == $this->get_primary_id_col()){
								$info = "<a href='".$this->get_ctrl_url()."&action=edit&id=$id'>$info</a>";
							}
							?>
							<td align="left"><span style='width:150px' class='<?php echo $ellipsis?'ellipsis':'' ?>'><?php echo $this->make_list_field($k, $info); ?></span></td>
							<?php } ?>
						<td align="center"><a href="<?php echo $this->get_ctrl_url()."&action=edit&id=".$id ?>"><img src="../images/edit.gif"/></a></td>
						<td align="center"><a href="<?php echo $this->get_ctrl_url()."&action=delete&id=".$id ?>" onclick = "return confirm_delete('Are you sure you want to delete this record?')"><img src="../images/delete.gif"/></a></td>
					</tr>
					<?php
				}
			}else{
				?>
				<tr>
					<td align="left" colspan="<?php echo $col_count + 2; //for edit and delete functionality                                                                          ?>">No data is available...</td>
				</tr>
			<?php } ?>
        </tbody>
    </table>
</div>
<script type="text/javascript">
	function confirm_delete(txt){
		return confirm(txt);
	}
</script>