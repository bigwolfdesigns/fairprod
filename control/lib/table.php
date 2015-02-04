<?php

//ini_set('display_errors', 1);

/**
 * EXTEND THIS TABLE DON'T EDIT IT use the getters and setters if there isn't one create it
 */
class table {
	public $action;
	public $debug				 = false;
	public $download_api		 = '/control/includes/download.php';
//    public $ajax_api          = 'http://fairprodmarketinggroup.com/master-control/bin/ajax.php';
	public $tpl_path			 = "/home/fairprod/public_html/control/tpl/";
	protected $table;
	protected $display_table_name;
	protected $id;
	protected $row_count;
	protected $files;
	protected $order_by;
	protected $is_ajax;
	protected $content			 = "";
	protected $ctrl_page		 = "";
	protected $page_rows		 = 50;
	protected $etc_path			 = "/home/fairprod/public_html/control/etc/";
	protected $ctrl_path		 = "/home/fairprod/public_html/control/";
	protected $master_lib_path	 = "/home/fairprod/public_html/control/lib/";
	protected $ajax_api			 = "";
	protected $ctrl_url			 = "";
	protected $base_path		 = "/home/fairprod/public_html/";
	protected $ext				 = '.php';
//    protected $session_name   = 'YourVisitID';
	protected $it_email			 = 'billy@bigwolfdesigns.com';
	protected $filters			 = array();
	protected $error			 = array();
	protected $assigned			 = array();
	protected $config			 = array();
	protected $related_tables	 = array();
	protected $cp_key			 = '';
	protected $execute			 = false;
	public $db_host				 = "localhost";
	public $db_user				 = "fairprod_produsr";
	public $db_pass				 = "qv3Vpv7mKpK1";
	public $db_name				 = "fairprod_prods";
	private $db;
	private $last_query			 = '';
	private $primary_id_cols	 = array(//because some one decided to use a difficult naming system on the database instead of just using id.... 'name'=>'`primary_column`' ex.:'users'=>'`user_id`'
		'users'				 => 'user_id',
		'categories'		 => 'cat_id',
		'categories_test'	 => 'cat_id'
	);
	/**
	 *
	 * @param type $table
	 * If table is passed use it else get it from the url
	 * Action can be 'list','edit','add'
	 * ID if editing ID must be present, it is the ID of the record
	 */
	public function __construct($table = NULL, $db = NULL){
//        var_dump($_FILES);
//        die();
//        $this->files = new files();
		if(!is_null($db)){
			$this->db_name = $db;
		}
		$this->_connect();
		if(is_null($table)){
			$table = isset($_GET['table'])?$_GET['table']:false;
			if($table === false){
				$table = isset($_POST['table'])?$_POST['table']:false;
			}
			if(!$this->_is_valid_table($table)){
				$this->show_error('invalid_table');
			}
			$this->table = $table;
		}else{
			if(!$this->_is_valid_table($table)){
				$this->show_error('invalid_table');
			}
			$this->table = $table;
		}
		$this->action	 = isset($_GET['action'])?$_GET['action']:'list';
		$this->id		 = isset($_GET['id'])?$_GET['id']:0;
		$this->sub_id	 = isset($_GET['sub_id'])?$_GET['sub_id']:0;
		if(($this->action == 'edit' || $this->action == 'delete') && $this->id <= 0){
			$this->show_error('invalid_request');
		}
//		ge the config variables for this table if their are none present an error for now.
		$this->_setup();
	}
	private function _connect(){
        $this->db = mysql_connect($this->db_host, $this->db_user, $this->db_pass) or die('I cannot connect to the database because: '.mysql_error());
        mysql_select_db($this->db_name);
	}
	public function set_cp_key($cp_key){
		$this->cp_key = $cp_key;
	}
	public function get_cp_key(){
		return $this->cp_key;
	}
	private function _is_valid_table($table_name){
		$query	 = "
            SELECT 1
		FROM `$table_name`
            LIMIT 1";
		$result	 = $this->db_do($query);
		if($result){
			return true;
		}
		return false;
	}
	public function show($which = 'all'){
        if($which == 'all' || $which == 'header'){
            $this->view('header');
        }
		if($which == 'all' || $which == 'action'){
			if($this->action == 'list'){
				$this->show_list();
			}else{
				$this->show_form();
			}
		}
        if($which == 'all' || $which == 'footer'){

            $this->view('footer');
        }
	}
	public function set_filters($filters){
		$this->filters = $filters;
		return $this;
	}
	public function get_filters(){
		return $this->filters;
	}
	public function show_error($error = 'generic'){
		switch($error){
			case'invalid_table':
				die('Invalid Table');
				break;
			case'invalid_request':
				die('Invalid Request');
				break;
			case 'generic'://not need but oh well
			default:
				die('Generic Error Code');
				break;
		}
	}
	/**
	 * show tpl file
	 */
	public function get_page_num(){
		$page	 = isset($_GET['page_num'])?$_GET['page_num']:0;
		$last	 = ceil($this->get_row_count() / $this->get_page_rows());
		if($page < 1){
			$page = 1;
		}elseif($page > $last){
			$page = $last;
		}
		return $page;
	}
	public function get_page_rows(){
		return $this->page_rows;
	}
	public function get_row_count(){
		return $this->row_count;
	}
	public function set_row_count($count){
		$this->row_count = $count;
	}
	public function get_limit(){
		$page		 = $this->get_page_num();
		$page_rows	 = $this->get_page_rows();
		$limit		 = ' LIMIT '.($page - 1) * $page_rows.','.$page_rows;
		return $limit;
	}
	public function parse_query_string_for_pagi(){
		$query_string	 = "";
		$output			 = array();
		parse_str($_SERVER['QUERY_STRING'], $output);
		if(is_array($output) && count($output) > 0){
			$query_string .="&";
			$query_string_params = array();
			foreach($output as $key => $value){
				if(($value != '' && isset($this->config['etc'][$this->table][$key])) || $key == 'filter_submit'){
					$query_string_params[] = "$key=$value";
				}
			}
			$query_string.=implode('&', $query_string_params);
		}
		return $query_string;
	}
	public function pagination(){
		$last			 = ceil($this->get_row_count() / $this->get_page_rows());
		$pagination		 = '';
		$query_string	 = $this->parse_query_string_for_pagi();
//        $_SERVER['QUERY_STRING'];
		if($last > 0){
			$page_num	 = $this->get_page_num();
			$pagination	 = "<ul class='pagination' style='float:right'>";
			if($page_num == 1){
				$pagination.="<li class='disabled'><span>&lt;</span></li>";
			}else{
				$pagination.="<li><a href='".$this->get_ctrl_url()."&page_num=1$query_string'>&lt;</a></li>";
				$pagination.="<li><a href='".$this->get_ctrl_url()."&page_num=".($page_num - 1)."$query_string'>Prev</a></li>";
			}
			$first_num	 = ($page_num - 2);
			$last_num	 = ($page_num + 2);
			$pagis		 = range($first_num, $last_num);
//		if(($page_num + 4) > $last){
			$add		 = 0;
			$sub		 = 0;
			foreach($pagis as $page){
				if($page < 1){
					$add++;
				}
				if($page > $last){
					$sub++;
				}
			}
			$pagis = range(($first_num + $add - $sub), ($last_num + $add - $sub));
			foreach($pagis as $page){
				if($page > 0 && $page <= $last){
					$class = '';
					if($page_num == $page){
						$class = "class='active'";
					}
					$pagination.="<li $class><a href='".$this->get_ctrl_url()."&page_num=$page$query_string'>$page</a></li>";
				}
			}
			if($page_num == $last){
				$pagination.="<li class='disabled'><span>&gt;</span></li>";
			}else{
				$pagination.="<li><a href='".$this->get_ctrl_url()."&page_num=".($page_num + 1)."$query_string'>Next</a></li>";
				$pagination.="<li><a href='".$this->get_ctrl_url()."&page_num=".$last."$query_string'>&gt;</a></li>";
			}
		}
		return $pagination;
	}
	public function show_list(){
//		probably need pagination but that can be later
		$fields			 = $this->get_fields();
		$display_fields	 = $fields;
		$db_fields		 = array_keys($fields);
		$this->set_fields($db_fields);
		$this->set_filters($this->get_filters_filter());
		$count			 = $this->get_count($this->get_filters());
		$this->set_row_count($count);
		$rows			 = $this->get_all($this->get_filters(), true);
		$this->un_set_fields();
		if($count == 1){
			$id				 = $rows[0][$this->get_primary_id_col()];
			$params			 = array();
			$params['id']	 = $id;
			if($this->needs_sub_table()){
				$params['sub_id'] = $rows[0][$this->get_sub_id_col()];
			}
			$params['action'] = 'edit';
			$this->redirect('', $params);
		}
		$col_count	 = count($fields);
		$filter_url	 = $this->get_ctrl_url();
		$this
				->assign('rows', $rows)
				->assign('fields', $display_fields)
				->assign('filter_url', $filter_url)
				->assign('col_count', $col_count)
				->view('list');
	}
	public function get_filters_filter(){
		$filters = array();
		if(is_array($_GET)){// && isset($_GET['filter_submit'])){
			foreach($_GET as $k => $v){
				$key = $k;
				if(isset($this->config['etc'][$this->table][substr($k, 0, -2)]) && $this->config['etc'][$this->table][substr($k, 0, -2)]['form']['type'] == 'date'){
					$key = substr($k, 0, -2);
				}
				if(isset($this->config['etc'][$this->table][$key]) && $_GET[$k] != ''){
					if($this->config['etc'][$this->table][$key]['form']['type'] != 'date'){
						$filters[] = array('field' => $key, 'operator' => $this->get_filter_operator($k), 'value' => $v);
					}else{
						$greater_or_less = substr($k, -2);
						if($greater_or_less == 'lt'){
							$filters[] = array('field' => $key, 'operator' => '<=', 'value' => date('Y-m-d H:m:i', strtotime($v)));
						}else{
							$filters[] = array('field' => $key, 'operator' => '>=', 'value' => date('Y-m-d H:m:i', strtotime($v)));
						}
					}
				}
			}
		}
		return $filters;
	}
	public function show_form(){
		switch($this->action){
			//should probably only submit either the main table or related tables not both easier that way
			case'delete':
				if($this->delete()){
					$params['action'] = 'list';
					$this->redirect('', $params);
				}else{
					$this->error[] = "An error occurred while trying to delete the record from the database...<br />".mysql_error();
				}
				break;
			case'edit':
//              its edit
//              get values for this record and fill in the blanks
//              if post then do check against it to make sure it's all good and update the db
				$filters	 = array();
//              get primary identiy column
				$id_col		 = $this->get_primary_id_col();
				$filters[]	 = array('field' => $id_col, 'operator' => '=', 'value' => $this->id);
				if($this->needs_sub_table()){
					$sub_id_col	 = $this->get_sub_id_col();
					$filters[]	 = array('field' => $sub_id_col, 'operator' => '=', 'value' => $this->sub_id);
				}
				$values			 = $this->get_all($filters);
				$related		 = $this->get_related_tables();
				$filters		 = array();
				$related_values	 = array();
				if(count($related) > 0){
					$this->set_order_by('id');
					foreach($related as $id_col => $table){
						//get all the values for each table that correspon to this tables unique identifier
						$filters['related']		 = array('field' => $id_col, 'operator' => '=', 'value' => $this->id);
						$related_values[$table]	 = $this->get_all($filters, false, $table);
						$related_fields[$table]	 = $this->get_fields($table);
					}
					unset($this->order_by);
				}
				$this->assign('related', $related);
				$this->assign('related_values', $related_values);
				$this->assign('related_fields', $related_fields);
				$this->assign('values', array_shift($values));
				if($this->_validate()){
					if($this->edit()){
						$params				 = array();
						$params['action']	 = "edit";
						$params['id']		 = $this->id;
						$this->redirect('', $params);
					}else{
						$this->error[] = "An error occurred while trying to edit the record in the database...<br />".mysql_error();
					}
				}
				break;
			case'add':
			default:
//		check for post and put-em in the values..
//              if everything checks out insert into the db
				if($this->_validate()){
					if($id = $this->add()){
						$params['id']		 = $id;
						$params['action']	 = 'edit';
						$this->redirect('', $params);
					}else{
						$this->error[] = "An error occurred while trying to add the record to the database...<br />".mysql_error();
					}
				}
				break;
		}

		$form_url = $this->get_ctrl_url()."&action=".$this->action.'&id='.$this->id;
		$this
				->assign('form_url', $form_url)
				->assign('fields', $this->get_fields())
				->view('form');
		switch($this->action){
			case'edit':
				$this->load_optional_views();
				break;
			default:
				break;
		}
	}
	public function load_optional_views(){
		$key	 = $this->get_cp_key();
		$cp_dir	 = ($key == "")?"":($key."/");
		if(is_dir($this->tpl_path.$cp_dir.$this->table)){
			$dir	 = $this->tpl_path.$cp_dir.$this->table;
			$files	 = scandir($dir);
			if(is_array($files)){
				foreach($files as $file){
					if(!is_dir($dir.'/'.$file)){
						$path_info	 = pathinfo($file);
						$file_name	 = $path_info['filename'];
						$this->view($this->table.'/'.$file_name);
					}
				}
			}
		}elseif(is_dir($this->tpl_path.$this->table)){
			$files = scandir($this->tpl_path.$this->table);
			if(is_array($files)){
				foreach($files as $file){
					if(!is_dir($dir.'/'.$file)){
						$path_info	 = pathinfo($file);
						$file_name	 = $path_info['filename'];
						$this->view($this->table.'/'.$file_name);
					}
				}
			}
		}
	}
	public function set_order_by($val){
		$this->order_by = $val;
	}
	public function assign($var, $value = ''){
		if(is_array($var)){
//			build functionality to allow an array of a key=value array to be passed
		}else{
			$this->assigned[$var] = $value;
		}
		return $this;
	}
	public function get_assigned_vars(){
		return $this->assigned;
	}
	public function view($file, $extras = array()){
		$file_name		 = $file;
		$is_require		 = (isset($extras['require']) && $extras['require'] == true);
		$require_once	 = (isset($extras['require_once']) && $extras['require_once'] == true);
		$path			 = isset($extras['path'])?$extras['path']:'';
		$return			 = isset($extras['return'])?$extras['return']:false;
		$key			 = $this->get_cp_key();
		$cp_dir			 = ($key == "")?"":($key."/");
		ob_start();
		if($path != ''){
			$file = $path.$file.$this->ext;
		}else{
			$tmp_file = $this->tpl_path.$cp_dir.$file.$this->ext;

			if(file_exists($tmp_file)){
				$file = $tmp_file;
			}else{
				$file = $this->tpl_path.$file.$this->ext;
			}
		}
		if(file_exists($file)){
//			get variables needed for the include
			$vars = $this->get_assigned_vars();
			extract($vars);
//			include tpl file.... not the best way to do it but without creating a bunch of other classes this will have to do...
			if($is_require){
				if($require_once){
					require_once($file);
				}else{
					require($file);
				}
			}else{
				include($file);
			}
		}else{
			echo "Could not find TPL file: $file_name";
//            exit();
		}
		$content = ob_get_contents();
		ob_end_clean();
		if($return){
			return $content;
		}
		echo $content;
		return $this;
//        $this->content.=$content;
//        echo $content;
	}
	public function set_fields($fields){
		$this->fields = $fields;
	}
	public function un_set_fields(){
		$this->fields = array();
	}
	/**
	 *
	 * @param type $filters
	 * get all values from table in list format
	 * TODO: implement limit, field list
	 */
	public function get_all($filters = array(), $pagination = false, $table = ''){
//		memcache??
		$filter_string = '';
		if(is_array($filters) && count($filters) > 0){
			$filter_string = "WHERE ";
			$filter_string .= $this->_process_filters($filters);
		}elseif(is_array($this->filters) && count($this->filters) > 0){
			$filter_string = "WHERE ";
			$filter_string .= $this->_process_filters($this->filters);
		}
		$qry = "SELECT *";
		if(isset($this->fields) && is_array($this->fields) && count($this->fields) > 0){
			$qry = "SELECT ".implode(',', $this->fields);
		}

		$table_name = $this->table;
		if($table != ''){
			$table_name = $table;
		}
		$qry.=" FROM `$table_name`
				$filter_string ";
		if(isset($this->order_by) && $this->order_by != ''){
			$qry.= "ORDER BY ".$this->order_by;
		}
		if($pagination){
			$qry .= $this->get_limit();
		}
		return $this->get_assoc($qry);
	}
	public function get_count($filters = array()){
//		memcache??
		$filter_string = '';
		if(is_array($filters) && count($filters) > 0){
			$filter_string = "WHERE ";
			$filter_string .= $this->_process_filters($filters);
		}elseif(is_array($this->filters) && count($this->filters) > 0){
			$filter_string = "WHERE ";
			$filter_string .= $this->_process_filters($this->filters);
		}

		$qry	 = "SELECT `".$this->get_primary_id_col()."`";
		$qry.=" FROM `".$this->table."`
				$filter_string ";
		$result	 = $this->db_do($qry);
		return mysql_num_rows($result);
	}
	public function get_one($filters, $table = ''){
		if(!is_array($filters) && intval($filters) > 0){
			$id			 = intval($filters);
			$filters	 = array();
			$filters[]	 = array('field' => $this->get_primary_id_col(), 'operator' => '=', 'value' => $id);
		}
		$ret = array_shift($this->get_all($filters, false, $table));
		return $ret;
	}
	public function get_fields($table = ''){
		$ret = array();
		if($table == ''){
			$table = $this->table;
		}
		if(is_array($this->config['etc'][$table])){
			foreach($this->config['etc'][$table] as $k => $res){
				if(isset($res['show'][$this->action]) && $res['show'][$this->action]){
					$ret [$k] = $res['display'];
				}
			}
		}
		return $ret;
	}
	public function get_assoc($query){
		$result = $this->db_do($query);
		if($result){
			$ret = array();
			while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
				foreach($row as $k => $r){
					$row[$k] = stripslashes($r);
				}
				$ret[] = $row;
			}
			return $ret;
		}
		return false;
	}
	public function get_primary_id_col($table = NULL){
		if(is_null($table)){
			$table = $this->table;
		}
		if(isset($this->primary_id_cols[$table])){
			return $this->primary_id_cols[$table];
		}
		return 'id';
	}
	public function get_sub_id_col(){
		$return = false;
		if(is_array($this->sub_table)){
			$return = array_shift(array_values($this->sub_table));
		}
		return $return;
	}
	public function set_display_table_name($name){
		$this->display_table_name = $name;
	}
	public function get_top_links(){
		$return			 = array();
		$top_links		 = isset($this->config['etc']['top_links'][$this->table])?$this->config['etc']['top_links'][$this->table]:array();
		$top_link_tags	 = isset($this->config['etc']['top_link_tags'])?$this->config['etc']['top_link_tags']:array();
		if(is_array($top_links)){
			foreach($top_links as $key => $links){
				foreach($links as $top_link){
					switch($key){
						case'buttons':
							$link		 = "<a href = '".$this->ctrl_url.$top_link['link']."'>";
							$link .= "<img src='".$top_link['image']."'/>";
							$link .= "</a >";
							$return[$key] .= $link;
							break;
						case'charts':
							$tmps		 = $this->get_assoc($top_link['qry']);
							$field_1	 = $top_link['field_1'];
							$field_2	 = $top_link['field_2'];
							$chart_piece = array();
							foreach($tmps as $tmp){
								$chart_piece[] = array($tmp[$field_1], intval($tmp[$field_2]));
							}
							$return[$key] .= $this
									->assign('chart_piece', $chart_piece)
									->view('top_chart', array('return' => true));
							break;
					}
				}
			}
//            var_dump($return);
			$ret = ''; //;implode('', $chart_piece);
			foreach($top_links as $key => $links){
				$ret .= isset($top_link_tags[$key]['beg'])?$top_link_tags[$key]['beg']:'<div>';
				$ret .= $return[$key];
				$ret .= isset($top_link_tags[$key]['end'])?$top_link_tags[$key]['end']:'</div>';
			}
		}
		return $ret;
	}
	public function get_conf(){
		$key	 = $this->get_cp_key();
		$cp_dir	 = ($key == "")?"":($key."/");

		$this->config['etc'] = array();
		$table_conf_file	 = "table.conf".$this->ext;
		if(file_exists($this->etc_path.$cp_dir.$table_conf_file)){
			$conf				 = array();
			require_once($this->etc_path.$cp_dir.$table_conf_file);
			$this->config['etc'] = array_merge($this->config['etc'], $conf);
		}elseif(file_exists($this->etc_path.$table_conf_file)){
			$conf				 = array();
			require_once($this->etc_path.$table_conf_file);
			$this->config['etc'] = array_merge($this->config['etc'], $conf);
		}
		$conf_file = $this->table.".conf".$this->ext;
		if(file_exists($this->etc_path.$cp_dir.$conf_file)){
			$conf						 = array();
			$display_table_name			 = '';
			$sub_table					 = false;
			require_once($this->etc_path.$cp_dir.$conf_file);
			$this->sub_table			 = is_array($sub_table)?$sub_table:false;
			$this->config['etc']		 = array_merge($this->config['etc'], $conf);
			$this->display_table_name	 = ($display_table_name != '')?$display_table_name:ucwords(str_replace('_', ' ', $this->table));
		}elseif(file_exists($this->etc_path.$conf_file)){
			$conf						 = array();
			$display_table_name			 = '';
			$sub_table					 = false;
			require_once($this->etc_path.$conf_file);
			$this->sub_table			 = is_array($sub_table)?$sub_table:false;
			$this->config['etc']		 = array_merge($this->config['etc'], $conf);
			$this->display_table_name	 = ($display_table_name != '')?$display_table_name:ucwords(str_replace('_', ' ', $this->table));
		}
		$related = $this->get_related_tables();
		if(count($related) > 0){
			foreach($related as $table){
				$conf_file = $table.".conf".$this->ext;
				if(file_exists($this->etc_path.$cp_dir.$conf_file)){
					$conf				 = array();
					require_once($this->etc_path.$cp_dir.$conf_file);
					$this->config['etc'] = array_merge($this->config['etc'], $conf);
				}elseif(file_exists($this->etc_path.$conf_file)){
					$conf				 = array();
					require_once($this->etc_path.$conf_file);
					$this->config['etc'] = array_merge($this->config['etc'], $conf);
				}
			}
		}
	}
	public function needs_sub_table(){
		return is_array($this->sub_table);
	}
	public function get_conf_for_table($table){
		$conf_file = $this->etc_path.$table.".conf".$this->ext;
		if(file_exists($conf_file)){
			$conf = array();
			require_once($conf_file);
			return $conf;
		}
		return false;
	}
	private function _setup(){
		$this->_connect();
		$this->get_conf();
		if($this->needs_sub_table() && $this->sub_id <= 0){
			$this->show_error('invalid_request');
		}
//        $this->_start_session($this->session_name);
		$this->is_ajax = $this->is_ajax();
		// this needs to be done better
		$this->assign('warning', '');
		$this->assign('page', $this->table);
		$this->assign('companyname', '');
	}
	private function _start_session($session_name){
		session_cache_limiter('nocache');
		session_name($session_name);
		session_start(); // Start the session.
//      If no session value is present, redirect the user.
		if(!isset($_SESSION['agent']) || ( $_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']))){
			$this->redirect('/index.php', array('do_not_add_query' => true));
		}
	}
	public function redirect($file = '', $params = array()){
		$url = $this->get_ctrl_url();
		// Check for a trailing slash.
		if((substr($url, -1) == '/') || ( substr($url, -1) == '\\')){
			$url = substr($url, 0, -1); // Chop off the slash.
		}
		if($file == ''){
			$file = ''; // ltrim($_SERVER['SCRIPT_NAME'], '/');
		}else{
			$file = ''; //dirname($_SERVER['PHP_SELF']).$file;
		}
		if(is_array($params) && count($params) > 0 && !isset($params['do_not_add_query'])){
			$file .="&";
			$query_string = array();
			foreach($params as $k => $v){
				$query_string [] = "$k=$v";
			}
			$file .= implode('&', $query_string);
		}elseif($_SERVER['QUERY_STRING'] != '' && !isset($params['do_not_add_query'])){
//            $file .="&".$_SERVER['QUERY_STRING'];
		}
		if(strpos($file, '/') !== 0){
			$url .= ''.$file; // Add the page.
		}else{
			$url .=$file;
		}
		if(headers_sent()){

			$string = '<script type="text/javascript">';
			$string .= 'window.location = "'.$url.'"';
			$string .= '</script>';

			echo $string;
		}else{
			header("Location: $url");
		}
		exit();
	}
	public function add($data = NULL){
		$qry		 = "INSERT INTO `$this->table` SET ";
		$query_set	 = array();
		if(is_null($data)){
			foreach($_POST as $k => $v){
				if(isset($this->config['etc'][$this->table][$k]) && $this->config['etc'][$this->table][$k]['show']['add']){
					$query_set[] = " `$k` = ".$this->_escape_field($v)." ";
				}
			}
		}elseif(is_array($data)){
			foreach($data as $k => $v){
				if(isset($this->config['etc'][$this->table][$k])){
					$query_set[] = " `$k` = ".$this->_escape_field($v)." ";
				}
			}
		}
		$qry .=implode(',', $query_set);
		$result = $this->db_do($qry);
		if($result){
			return mysql_insert_id();
		}
		return false;
	}
	public function edit($data = NULL){
		$qry = "UPDATE `$this->table` SET ";

		$query_set = array();
		if(is_null($data)){
			foreach($_POST as $k => $v){
				if(isset($this->config['etc'][$this->table][$k]) && $this->config['etc'][$this->table][$k]['show']['edit']){
					$query_set[] = " `$k` = ".$this->_escape_field($v)." ";
				}
			}
		}elseif(is_array($data)){

			if(isset($data[$this->get_primary_id_col()])){
				$this->id = $data[$this->get_primary_id_col()];
				unset($data[$this->get_primary_id_col()]);
			}elseif(!$this->is_ajax){
				return false; //causing issues with ajax editing
			}
			foreach($data as $k => $v){
				if(isset($this->config['etc'][$this->table][$k])){
					$query_set[] = " `$k` = ".$this->_escape_field($v)." ";
				}
			}
		}
		$qry .=implode(',', $query_set);
		$qry .=" WHERE `".$this->get_primary_id_col()."` = ".$this->id;
		if($this->is_ajax && $this->is_valid_ajax()){
			$qry .=" AND ".$this->get_link('field')." = ".$this->get_link('id');
		}
		$result = $this->execute($qry);
		return $result;
	}
	public function delete(){
		$qry = "DELETE FROM `$this->table` WHERE `".$this->get_primary_id_col()."` = $this->id";
		if($this->is_ajax && $this->is_valid_ajax()){
			$qry .=" AND ".$this->get_link('field')." = ".$this->get_link('id');
		}
		$result = $this->execute($qry);

		return $result;
	}
	public function execute($qry){
		$result = false;
		if(!$this->is_ajax || $this->is_valid_ajax() || $this->execute){
			$result			 = $this->db_do($qry);
			$this->execute	 = false;
		}
		if($result && mysql_affected_rows() >= 0){
			return true;
		}
		return $result;
	}
	public function set_execute(){
		$this->execute = true;
	}
	public function bulk($action, $rows){
		//I think it's better to bulk change them one by one so you can get a response whether it was added or not per each record
		switch($action){
			case'add':
				foreach($rows as $row){
					$id = $this->add($row);
					if($id){
						echo"Added record: $id\n";
					}else{
						echo"Could not add record...\n";
					}
				}
				break;
			case'edit':
				foreach($rows as $row){
					$ret = $this->edit($row);
					if($ret){
						echo"Edited record: ".$row[$this->get_primary_id_col()]."\n";
					}else{
						echo"Could not edit record...".$row[$this->get_primary_id_col()]."\n";
					}
				}
				break;
			default:
				return false;
		}
	}
	public function get_link($link){
		$related_tables = $this->get_related_tables();
		foreach($related_tables as $link_field => $link_table){
			switch($link_table){
				case $this->table:
					if($link == 'field'){
						return $link_field;
					}elseif($link == 'id'){
						if(isset($_POST[$link_field])){
							return $this->_escape_field(intval($_POST[$link_field]));
						}
					}
					break;
			}
		}
		return '';
	}
	public function is_valid_ajax(){
		$related_tables = $this->get_related_tables();
		foreach($related_tables as $related){
			switch($related){
				case $this->table:
					return true;
			}
		}
		return false;
	}
	protected function _validate(){
		if(!$_POST){
			return false;
		}
		$number_types = array('decimal' => true, 'int' => true, 'float' => true);
		if(isset($this->config['etc'][$this->table])){
			foreach($this->config['etc'][$this->table] as $k => $v){
				$type = isset($v['form']['type'])?$v['form']['type']:'text';
				if((!isset($_POST[$k]) || $_POST[$k] == '' || (intval($_POST[$k]) == 0 && isset($number_types[$type]) ) ) && (isset($v['required']) && $v['required'])){
//					bad set erro message
					$this->error[] = 'The '.$v['display'].' field is not set and is required.';
					return false;
				}elseif(1){
//					check if a validation method is set check it and return false if bad and set error message
				}
			}
		}else{
			$this->error[] = 'That table is not set up properly yet...';
			return false;
		}

		return true;
	}
	public function get_form_value($key, $default = '', $related = false, $table = ''){
		$ret = '';
		if($table == ''){
			$table = $this->table;
		}
		//dont fill in the related forms with data from this table
		if(isset($_POST[$key]) && !$related){
			$ret = $_POST[$key];
		}elseif(isset($_GET[$key]) && !$related){
			$ret = $_GET[$key];
		}elseif($default != ''){
			$ret = $default;
		}elseif(isset($this->config['etc'][$table][$key]['form']['default'])){
			$ret = $this->config['etc'][$table][$key]['form']['default'];
		}
		return $ret;
	}
	public function make_form_field($key, $value = '', $table = '', $extras = ''){
		if($table == ''){
			$table = $this->table;
		}
		$related_tables	 = $this->get_related_tables();
		$disabled		 = '';
		if(isset($related_tables[$key]) && $related_tables[$key] == $table){
			$disabled = ' disabled';
		}
		$extras .= $disabled;
		$length			 = isset($this->config['etc'][$table][$key]['form']['length'])?intval($this->config['etc'][$table][$key]['form']['length']):8;
		$required		 = isset($this->config['etc'][$table][$key]['required']) && $this->config['etc'][$table][$key]['required'];
		$size_num		 = ceil($length / 10) * 10;
		$size			 = ($size_num >= 40)?40:$size_num;
		$length_display	 = $length > 0?"maxlength='$length'":"";
		$class			 = "class='".($required?"required_field ":'')."table_$table"."_field_$key'";
		$ret			 = "<input $extras type='text' $class value='$value' name='$key' $length_display size='$size'/>";
		if(isset($this->config['etc'][$table][$key]['form']['type'])){
			switch($this->config['etc'][$table][$key]['form']['type']){
				case'select':
					$ret = $this->create_select($key, $value, $table, $extras);
					break;
				case'id':
					$ret = "<span class='table_$table"."_field_$key'>$value</span>";
					break;
				case'textarea':
					$ret = "<textarea $extras $class name='$key' $length_display style='max-width:500px;max-height:100px; height:50px;width:215px'>$value</textarea";
					break;
				case'image':
					$ret = "<input class='need_file_path' $extras type='text' $class value='$value' name='$key' $length_display size='$size'/>";
					break;
				case 'date':
				default:
					break;
			}
		}

		return $ret;
	}
	public function make_filter_field($key, $value = '', $table = ''){
		if($table == ''){
			$table = $this->table;
		}
		$length	 = isset($this->config['etc'][$table][$key]['form']['length'])?$this->config['etc'][$table][$key]['form']['length']:8;
		$ret	 = "<input type='text' value='$value' name='$key' maxlength='$length' size='16'/>";
		if(isset($this->config['etc'][$table][$key]['form']['type'])){
			switch($this->config['etc'][$table][$key]['form']['type']){
				case'select':
					$ret	 = $this->create_select($key, $value, $table);
					break;
				case 'date':
					$lt_val	 = isset($_GET[$key.'lt'])?$_GET[$key.'lt']:'';
					$gt_val	 = isset($_GET[$key.'gt'])?$_GET[$key.'gt']:'';
					$ret	 = "<input type='text' value='$gt_val' name='$key"."gt' maxlength='$length' size='16'/>";
					$ret .= "<input type='text' value='$lt_val' name='$key"."lt' maxlength='$length' size='16'/>";
				default:
					break;
			}
		}

		return $ret;
	}
	public function make_list_field($key, $value = '', $table = ''){
		if($table == ''){
			$table = $this->table;
		}
		$return = $value;
		if(isset($this->config['etc'][$table][$key]['form']['type'])){
			switch($this->config['etc'][$table][$key]['form']['type']){
				case'select':
					if(isset($this->config['etc'][$table][$key]['form']['table'])){
						$query_table = $this->config['etc'][$table][$key]['form']['table'];
						$select_show = isset($this->config['etc'][$table][$key]['form']['select_show'])?$this->config['etc'][$table][$key]['form']['select_show']:'id';
						$prim_id_col = $this->get_primary_id_col($query_table);
						$qry		 = "SELECT $select_show FROM `$query_table` WHERE $prim_id_col = '$value'";
//						var_dump($qry);
						$res		 = $this->db_do($qry);
						$ret		 = mysql_fetch_assoc($res);
						$return		 = $ret[$select_show];
					}elseif(isset($this->config['etc'][$table][$key]['form']['transform'])){
						//transform
						$transform	 = $this->config['etc'][$table][$key]['form']['transform'];
						$return		 = $transform[$value];
					}
					break;
			}
		}

		return $return;
	}
	public function get_filter_operator($field){
		switch($this->config['etc'][$this->table][$field]['form']['type']){
			case'textarea':
				return "LIKE";
			case'date':
				return ">=";
			default:
				return "=";
		}
	}
	public function create_select($key, $value, $table, $extras = ''){

		$required	 = isset($this->config['etc'][$table][$key]['required']) && $this->config['etc'][$table][$key]['required'];
		$ret		 = "<select $extras".($required?" class='required_field'":'')." id='id_$key' name='$key'>";
		$ret .= "<option value='' ".(($value == '')?'SELECTED':'').">Please Select</option>";
		$table_name	 = isset($this->config['etc'][$table][$key]['form']['table'])?$this->config['etc'][$table][$key]['form']['table']:false;
		if($table_name){
			if($this->_is_valid_table($table_name)){
				//select all fields from db id and name in config file
				$id_col			 = $this->get_primary_id_col($table_name);
				$name_col		 = $this->config['etc'][$table][$key]['form']['select_show'];
				$extra_fields	 = array($id_col, $name_col);
				$this->set_fields($extra_fields);
				$this->set_filters(array());
				$values			 = $this->get_all(array(), false, $table_name);
				$this->un_set_fields();
				if(is_array($values)){
					foreach($values as $val){
						$ret .= "<option value='$val[$id_col]'".(($value == $val[$id_col])?'SELECTED':'').">$val[$name_col]</option>";
					}
				}
			}
		}else{
			$transform = isset($this->config['etc'][$table][$key]['form']['transform'])?$this->config['etc'][$table][$key]['form']['transform']:false;
			if(is_array($transform)){
				foreach($transform as $k => $v){
					$ret .= "<option value='$k'".(($value == $k)?'SELECTED':'').">$v</option>";
				}
			}
		}
		$ret .= "</select>";
		return $ret;
	}
	public function create_manual_select($params){
		$name		 = isset($params['name'])?$params['name']:'';
		$value		 = isset($params['value'])?$params['value']:'';
		$caption	 = isset($params['caption'])?$params['caption']:'Please Select';
		$table		 = isset($params['table'])?$params['table']:'';
		$field_name	 = isset($params['field_name'])?$params['field_name']:'';
		$options	 = isset($params['options'])?$params['options']:'';
		$ret		 = "<select name ='$name' >";
		$ret .= "<option value='$value' ".(($value == '')?'SELECTED':'').">$caption</option>";
		if($table != ''){
			$id_col			 = $this->get_primary_id_col($table);
			$extra_fields	 = array($id_col, $field_name);
			$this->set_fields($extra_fields);
			$values			 = $this->get_all(array(), false, $table);
			foreach($values as $val){
				$ret .= "<option value='$val[$id_col]'".(($value == $val[$id_col])?'SELECTED':'').">$val[$field_name]</option>";
			}
		}elseif(is_array($options)){
			foreach($options as $k => $v){
				$ret .= "<option value='$v'".(($value == $k)?'SELECTED':'').">$k</option>";
			}
		}
		$ret .= "</select>";
		return $ret;
	}
	public function get_table_templates($tpl_dir = ''){
		if($tpl_dir == ''){
			$tpl_dir = $this->table;
		}
		if(is_dir($this->tpl_path.$tpl_dir)){
			if($handle = opendir($this->tpl_path.$tpl_dir)){
				while(false !== ($entry = readdir($handle))){
					if($entry != "." && $entry != ".."){
						$path_info = pathinfo($this->tpl_path.$tpl_dir.'/'.$entry);
						switch($path_info['extension']){
							case'php':
							case'html':
								$this->view($tpl_dir.'/'.$path_info['filename']);
								break;
							case'js':
							case'css':
							default:
								die("You havent built this part yet dummy");
								break;
						}

//                        echo "$entry\n";
					}
				}
				closedir($handle);
			}
		}
	}
	/**
	 *
	 * @param type $filters
	 * @return string
	 *
	 * EXAMPLE:
	 * 	$filter		 = [];
	 * 	$filter[]	 = ['field'=>'field_value', 'operator'=>'operator_value', 'value'=>'values value']; // this if for an AND condition
	 * 	$filter[][]	 = ['field'=>'field_value', 'operator'=>'operator_value', 'value'=>'values value']; // this if for an OR condition
	 *
	 */
	private function _process_filters($filters){
		static $recur	 = 0;
		$nl				 = "\n";
		$tab			 = "\t";
		if(is_numeric($filters)){
			$filters = array(array('field' => 'id', 'operator' => '=', 'value' => $filters));
		}elseif(count($filters) == 1 && isset($filters[0]) && is_numeric($filters[0])){
			$filters = array(array('field' => 'id', 'operator' => '=', 'value' => $filters[0]));
		}elseif(!is_array($filters)){
			return 1;
		}
		$recur++;
		$appends = array();
		foreach($filters as $fil){
			if(!isset($fil['field']) && !isset($fil['name'])){
				$appends[] = $nl.str_repeat($tab, $recur).$this->_process_filters($fil);
			}else{
				$appends[] = $nl.str_repeat($tab, $recur).$this->_process_single_filter($fil);
			}
		}
		$append = $nl.str_repeat($tab, $recur - 1).'('.implode($recur == 1?' AND ':' OR ', $appends).$nl.str_repeat($tab, $recur - 1).')';
		$recur--;
		return $append;
	}
	public function db_do($query){
		$this->last_query	 = $query;
		$ret				 = mysql_query($query);
		$error				 = mysql_error();
		if($error != ''){
			ob_start();
			var_dump(debug_backtrace());
			$contents = ob_get_contents();
			ob_end_clean();
			mail($this->it_email, "MYSQL Error Occured", "Query: $query\n\n".$error."\n\n".$contents);
		}
		if($this->debug && $_SESSION['user_id'] == 57){
			ob_start();
			echo "<pre>$query</pre>";
			ob_get_contents();
		}
//        $this->un_set_fields();
//        $this->filters  = array();
		$this->order_by = '';
		return $ret;
	}
	public function get_last_query(){
		return $this->last_query;
	}
	private function _process_single_filter($filter){
		if((!isset($filter['field'])) || !isset($filter['operator'])){
			return '';
		}
		if(!isset($filter['value'])){
			$filter['value'] = '';
		}
		$field		 = $filter['field'];
		$operator	 = strtoupper($filter['operator']);
		$value		 = $filter['value'];
		$extra		 = isset($filter['extra'])?$filter['extra']:'';
		$escape		 = isset($filter['escape'])?$filter['escape']:'auto'; //this can be : auto (filter/value chooses if escape or not), true (always escapes), false (never escapes)
		$return		 = '';
//		let's create some backwards compatibility
		switch($operator){
			case 'F=':
			case 'F>':
			case 'F<':
			case 'F!=':
			case 'F>=':
			case 'F<=':
//			field base
				$operator	 = substr($operator, 1);
				$escape		 = false;
				break;
		}
//		fixing some non standard operators
		switch($operator){
			case '!=':
				$operator = '<>';
				break;
		}
//		let's prepare the $filter['value']
		switch($operator){
			case 'AGAINST':
				$value = '(';
				if(is_array($filter['value'])){
					$value .= $this->_escape_field($filter['value'][0], $escape);
					$value .= ' '.$filter['value'][1];
				}else{
					$value = $this->_escape_field($filter['value'], $escape);
				}
				$value .= ')';
				break;
			case 'BETWEEN':
				if(!is_array($filter['value'])){
					$filter['value'] = array(0, 0);
				}else{
					if(!isset($filter['value'][0])) $filter['value'][0]	 = 0;
					if(!isset($filter['value'][1])) $filter['value'][1]	 = 0;
				}
				$value	 = $this->_escape_field($filter['value'][0], $escape).' AND '.$this->_escape_field($filter['value'][1], $escape);
				break;
			case 'NOT IN':
			case 'IN':
				$value	 = '(';
				if(is_array($filter['value'])){
					foreach($filter['value'] as $k => $v){
						$filter['value'][$k] = $this->_escape_field($filter['value'][$k], $escape);
					}
					$value .= implode(',', $filter['value']);
				}else{
					$value .= $this->_escape_field($filter['value'], $escape);
				}
				$value .= ')';
				break;
			case 'IS':
			case 'IS NOT':
//			exception to _escape_field because the "NULL" will not need to be escaped
//			so the default behavior is to NOT escape
				if($escape === false || $escape === 'auto'){
					$value = $filter['value'];
				}else{
					$value = '"'.mysql_real_escape_string($filter['value']).'"';
				}
				break;
			case 'FIND_IN_SET':
			case 'NOT FIND_IN_SET':
				$value	 = $this->_escape_field($filter['value'], $escape);
				$return	 = $operator.'('.$value.','.$field.')';
				break;
			default:
				$value	 = $this->_escape_field($filter['value'], $escape);
				break;
		}
		if($return == ''){
			$return = $field.' '.$operator.' '.$value.' '.$extra;
		}
		return $return;
	}
	private function _escape_field($value, $escape = 'auto'){
		if($escape === false || ($escape === 'auto' && is_numeric($value) && !is_string($value))){
//			nothing to do
		}else{
			$value = '"'.mysql_real_escape_string($value).'"';
		}
		return $value;
	}
	public function get_related_tables(){
		return $this->related_tables;
	}
	public function send_email($to, $from, $subject, $body, $attachment = ''){

		require_once("PHPMailerAutoload.php");

		$mail = new PHPMailer();

		$mail->From = $from;
//        $mail->FromName = "Mailer";
//        $mail->AddAddress("josh@example.net", "Josh Adams");
		$mail->AddAddress($to);   // name is optional
//        $mail->AddReplyTo("info@example.com", "Information");
//        $mail->WordWrap = 50;                                 // set word wrap to 50 characters
		if($attachment !== ''){
			$mail->AddAttachment($attachment);   // add attachments
		}
		$mail->IsHTML(true); // set email format to HTML

		$mail->Subject	 = $subject;
		$mail->Body		 = $body."<br />";
		$mail->AltBody	 = $body."\n";

		if($mail->Send()){
			return true;
		}else{
			return false;
			echo "Message could not be sent. <p>";
			echo "Mailer Error: ".$mail->ErrorInfo;
		}
	}
	public function set_ctrl_url($url){
		$this->ctrl_url = $url;
	}
	public function get_ajax_hmac_key(){
		return date("Ymd").'someSalt'.$_SERVER['REMOTE_ADDR'];
	}
	public function ajax_key($optional_salt){
		return hash_hmac('sha256', session_id().$optional_salt, $this->get_ajax_hmac_key());
	}
	public function is_ajax(){
		$ajax_code	 = isset($_POST['ajax_code'])?$_POST['ajax_code']:false;
		$ajax_key	 = $this->ajax_key('MY_AJAX_KEY');
		if($ajax_code === $ajax_key){
			return true;
		}
		return false;
	}
	public function get_ajax_api(){
		$url = $this->get_ctrl_url(true);
		$url .= "?page=ajax_api&ajax=";
		return $url;
	}
	public function get_ctrl_url($no_page = false){
		$ret = $this->ctrl_url;
		if($no_page === false){
			$page = (isset($this->ctrl_page) && $this->ctrl_page !== '')?$this->ctrl_page:$this->table;
//            var_dump($page);
//            die();
			$ret .= "?page=".$page;
		}
		return $ret;
	}
	public function set_ctrl_page($page){
		$this->ctrl_page = $page;
		$this->assign('page', $page);
	}
	public function set_client_email($email){
		$this->client_email = $email;
		$this->assign('client_email', $email);
	}
	public function get_info($id, $table = ''){
		$return = $this->get_one($id, $table);
		return $return;
	}
//	public function get_css($file_name){
//		$key		 = $this->get_cp_key();
//		$cp_dir		 = ($key == "")?"":($key."/");
//		$cp_css_dir	 = $this->tpl_path.$cp_dir.'css/';
//		$css_dir	 = $this->tpl_path.'css/';
//		if(file_exists($cp_css_dir.$file_name.'.css')){
//			$file = $cp_css_dir.$file_name.'.css';
//		}elseif(file_exists($css_dir.$file_name.'.css')){
//			$file = $css_dir.$file_name.'.css';
//		}
//	}
	public function singular($input){
		return str_replace('ies', 'y', $input);
	}
}
