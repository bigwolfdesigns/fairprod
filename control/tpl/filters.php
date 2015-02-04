<br />
<div style='width:56%;float:left;padding-top:50px'>
    <a style="float:left" href="<?php echo $this->get_ctrl_url()."&action=add" ?>">Add a <?php echo $this->singular($this->display_table_name) ?></a>
    <form action="<?php echo $filter_url ?>" method="GET" id='edit_form'>
        <input type="hidden" name="page" value="<?php echo $page ?>"/>
        <h2>Filters:</h2>
        <table style='background-color: antiquewhite;' width="100%" border="0" cellspacing="8" id='edit_table'>
            <?php foreach($fields as $k => $v){ ?>
                <tr>
		                    <td style="min-width:150px">
	                        <div align="right" style='float:right'> <?php echo $this->get_filter_operator($k); ?></div>
                        <div align="right" style='float:right; padding-right:5px'><?php echo $v; ?></div>
                    </td>
                    <td>
                        <?php echo $this->make_filter_field($k, $this->get_form_value($k, isset($values[$k])?$values[$k]:''));
                        ?>

                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td>
                    <div align="right"></div>
                </td>
                <td>
                    <input type="submit" name="filter_submit" value="Filter"/>
                </td>
            </tr>
        </table>
    </form>
</div>