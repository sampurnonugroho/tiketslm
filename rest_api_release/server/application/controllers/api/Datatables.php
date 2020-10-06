<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Datatables extends REST_Controller {
	
	function __construct($config = 'rest') {
        parent::__construct($config);
		$this->load->model("model_app");
        $this->load->database();
		
		$this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
    
    private function _get_datatables_query()
    {
        $param = json_decode($this->input->get('data')['param'], true); 
        $post = $this->input->get('data')['post']; 
        
        $this->db->from($param['table']);
 
        $i = 0;
     
        foreach ($param['column_search'] as $item) // looping awal
        {
            if($post['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                 
                if($i===0) // looping awal
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $post['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $post['search']['value']);
                }
 
                if(count($param['column_search']) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }
         
        if(isset($post['order'])) 
        {
            $this->db->order_by($param['column_order'][$post['order']['0']['column']], $post['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $param['order'];
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
	
	// static function filter() {
		
	// }
	
	static function order_custom() {
		$param = json_decode($this->input->get('data')['param'], true); 
        $post = $this->input->get('data')['post']; 
		
		$order = '';
		if(isset($param['order']))
        {
			foreach($param['order'] as $k => $r) {
				$orderBy[] = ''.key($r).' '.$r[key($r)];
			}
        }
		
		$order = 'ORDER BY '.implode(', ', $orderBy);
		
		return $order;
	}
    
    function get_datatables()
    {
        $post = $this->input->get('data')['post']; 
        $this->_get_datatables_query();
        if($post['length'] != -1)
        $this->db->limit($post['length'], $post['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $param = json_decode($this->input->get('data')['param'], true); 
        
		if(isset($param['table'])) {
			$this->db->from($param['table']);
			return $this->db->count_all_results();
		} else {
			$res = $this->db->query($param['query']);
			return $res->num_rows();
		}
    }
	
	function index_get() {
		$request = $this->input->get('data')['post'];
		$columns = $this->input->get('data')['post']['columns'];
		$param = json_decode($this->input->get('data')['param'], true); 
		
		$limit = self::limit( $request, $columns );
		$order = self::order( $request, $columns, $param);
		$where = self::filter( $request, $columns, $param);
		
		if(array_key_exists("status", $request) AND empty($where)) {
			if($request['status']=="") {
				$tambahan = "WHERE status='".$request['status']."'";
			}
		} else {
			$tambahan = "AND status='".$request['status']."'";
		}
		$query = $param['query']." $where $order $limit";
		$data = $this->db->query($query)->result();
		$recordsTotal = $this->db->query($param['query']." $where")->num_rows();
		$recordsFiltered = $this->db->query($param['query']." $where")->num_rows();
		
		$output = array(
            "draw" => (isset($post['draw']) ? $post['draw'] : 0),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}
	
	
	function show_get() {
		// $this->o($_REQUEST);
		$request = $this->input->get('data')['post'];
		$columns = $this->input->get('data')['post']['columns'];
		$param = json_decode($this->input->get('data')['param'], true); 
		
		$limit = self::limit( $request, $columns );
		$order = self::order( $request, $columns, $param);
		$where = self::filter( $request, $columns, $param);
		
		if(array_key_exists("status", $request) AND empty($where)) {
			if($request['status']=="") {
				$tambahan = "WHERE status='".$request['status']."'";
			}
		} else {
			$tambahan = "AND status='".$request['status']."'";
		}
		$query = $param['query']." $where $order $limit";
		
		echo $query;
		// $data = $this->db->query($query)->result();
		// $recordsTotal = $this->db->query($param['query']." $where")->num_rows();
		// $recordsFiltered = $this->db->query($param['query']." $where")->num_rows();
		
		// $output = array(
            // "draw" => (isset($post['draw']) ? $post['draw'] : 0),
            // "recordsTotal" => $recordsTotal,
            // "recordsFiltered" => $recordsFiltered,
            // "data" => $data,
        // );
        // //output dalam format JSON
        // echo json_encode($output);
	}
	
	static function limit ( $request, $columns )
	{
		$limit = '';

		if ( isset($request['start']) && $request['length'] != -1 ) {
			$limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
		}

		return $limit;
	}
	
	static function order ( $request, $columns, $param )
	{
		$order = '';
		
		
		if(isset($param['group']))
        {
			$order .= 'GROUP BY '.implode(', ', $param['group']);
		}
		
		if(isset($param['order']))
        {
			foreach($param['order'] as $k => $r) {
				$orderBy[] = ''.key($r).' '.$r[key($r)];
			}
        } 
		
		if ( isset($request['order']) && count($request['order']) ) {
			$orderBy = array();

			for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
				
				// print_r($request['order'][$i]['column']);
				// Convert the column index into the column data property
				$columnIdx = intval($request['order'][$i]['column']);
				$requestColumn = $request['columns'][$columnIdx];

				if ( $requestColumn['orderable'] == 'true' ) {
					$dir = $request['order'][$i]['dir'] === 'asc' ?
						'ASC' :
						'DESC';

					$orderBy[] = ''.$requestColumn['data'].' '.$dir;
				}
			}
		}
		
		$order .= ' ORDER BY '.implode(', ', $orderBy);
		

		return $order;
	}
	
	static function filter ( $request, $columns, $param )
	{
		$i = 0;
		
		$globalSearch = array();
		$columnSearch = array();
		$columnWhere = array();

		if ( isset($request['search']) && $request['search']['value'] != '' ) {
			$str = $request['search']['value'];

			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				if ( $requestColumn['searchable'] == 'true' ) {
					$binding = "'%".$str."%'";
					$globalSearch[] = "".$requestColumn['data']." LIKE ".$binding;
				}
			}
		}
		
		if(isset($param['where'])) {
			for ($i=0, $ien=count($param['where']); $i<$ien; $i++ ) {
				$requestColumn = $param['where'][$i];
				
				if(strpos(key($requestColumn), '[') !== false){
					$fullstring = key($requestColumn);
					$parsed = self::get_string_between($fullstring, '[', ']');	
					$parsed2 = self::get_string_between2($fullstring, '[', ']');	
					$str = "'".$requestColumn[key($requestColumn)]."'";
					
					if($requestColumn[key($requestColumn)]==null) {
						$columnWhere[] = "".$parsed2." IS NOT NULL";
					} else {
						$columnWhere[] = "".$parsed2." ".$parsed."= ".$str;						
					}
					// echo $parsed;
					// $binding = "'%".$str."%'";
				} else {
					$parsed2 = key($requestColumn);
					$str = "'".$requestColumn[key($requestColumn)]."'";
					$columnWhere[] = "".$parsed2." = ".$str;
				}
				// echo "\n";
				// print_r(key($requestColumn));
				
			}
			// print_r($columnWhere);
		}
		
		if ( isset( $param['column_search'] ) ) {
			for ( $i=0, $ien=count($param['column_search']) ; $i<$ien ; $i++ ) {
				$requestColumn = $param['column_search'][$i];
				$str = $request['search']['value'];
				
				if ($str != '' ) {
					$binding = "'%".$str."%'";
					$columnSearch[] = "".$requestColumn." LIKE ".$binding;
				}
			}
		}

		// Combine the filters into a single string
		$where = '';
	
		if ( count( $globalSearch ) ) {
			$where = '('.implode(' OR ', $globalSearch).')';
		}

		if ( count( $columnSearch ) ) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) :
				$where .' AND '. implode(' AND ', $columnSearch);
		}
		
		if ( count( $columnWhere ) ) {
			$where = $where === '' ?
				implode(' AND ', $columnWhere) :
				$where .' AND '. implode(' AND ', $columnWhere);
		}

		if ( $where !== '' ) {
			$where = 'WHERE '.$where;
		}

		return $where;
	}
	
	static function simple ( $request, $conn, $table, $primaryKey, $columns )
	{
		$bindings = array();
		$db = self::db( $conn );

		// Build the SQL query string from the request
		$limit = self::limit( $request, $columns );
		$order = self::order( $request, $columns );
		$where = self::filter( $request, $columns, $bindings );

		// Main query to actually get the data
		$data = self::sql_exec( $db, $bindings,
			"SELECT ".implode(", ", self::pluck($columns, 'db'))."
			 FROM $table
			 $where
			 $order
			 $limit"
		);

		// Data set length after filtering
		$resFilterLength = self::sql_exec( $db, $bindings,
			"SELECT COUNT({$primaryKey})
			 FROM   $table
			 $where"
		);
		$recordsFiltered = $resFilterLength[0][0];

		// Total data set length
		$resTotalLength = self::sql_exec( $db,
			"SELECT COUNT({$primaryKey})
			 FROM   $table"
		);
		$recordsTotal = $resTotalLength[0][0];

		/*
		 * Output
		 */
		return array(
			"draw"            => isset ( $request['draw'] ) ?
				intval( $request['draw'] ) :
				0,
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => self::data_output( $columns, $data )
		);
	}
	
	static function get_string_between($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		// echo $string." ".$end." ".$ini;
		return substr($string, $ini, $len);
	}
	
	static function get_string_between2($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $start);
		// echo $string." ".$len;
		// echo substr($string, $len);
		$newstr = str_replace(substr($string, $len), "", $string);
		return $newstr;
	}
	
	function o($print) {
		echo "<pre>";
		print_r($print);
	}
}