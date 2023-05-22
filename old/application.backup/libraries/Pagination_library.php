<?php

/**
 * Codeigniter Pagination Library using sub-query strategy
 * - Support Datatables Serverside
 * - Support Select2 Ajax
 * @author AjowSentry
 */

class Pagination_library {

    protected $config = [
        'max_limit'        => 100,
        'default_limit'    => 20,
        'per_page'         => 20,
        'columns'          => NULL,
        'searchable'       => 'all',
        'orderable'        => 'all',
        'method'           => 'get_post',
        'searchable_param' => 'searchable',
        'term_param'       => 'term',
        'sort_param'       => 'order',
        'limit_param'      => 'length',
        'offset_param'     => 'start',
        'page_param'       => 'page',
    ];

    protected $CI;

    private $forward_calls = [
        'select', 'from', 'join',
        'where', 'or_where',
        'where_in', 'or_where_in', 'where_not_in',
        'like', 'or_like', 'not_like', 'or_not_like',
        'group_start', 'or_group_start', 'not_group_start', 'group_end',
        'group_by', 'having', 'or_having'
    ];

    private $calls_record = [];
    private $query;

    public function __construct($params = [])
    {
        $this->CI = &get_instance();
        $this->initialize($params);
    }

    public function __call($name, $arguments = [])
    {
        if(in_array($name, $this->forward_calls)) {
            $this->calls_record[] = [$name, $arguments];
            $this->query = NULL;
            return $this;
        }
        else {
            throw new Exception('Method doesn\'t exists');
        }
    }

    public function initialize($params = [])
    {
        foreach($params as $key => $value) {
            $this->set_config_value($key, $value);
        }

        $this->calls_record = [];
        $this->query = '';
    }

    public function get_data()
    {
        $limit = $this->get_input_param($this->config['limit_param']);
        $limit = intval($limit) ? intval($limit) : $this->config['default_limit'];

        $offset = $this->get_input_param($this->config['offset_param']);
        $offset = intval($offset) ? intval($offset) : 0;

        if($this->config['max_limit'] && $limit > $this->config['max_limit']) {
            $limit = $this->config['max_limit'];
        }

        $sql = "SELECT * FROM (" . $this->get_query() . ") AS _sub_ "
            . $this->get_search_part_query() . ' '
            . $this->get_sort_part_query()
            . ' LIMIT ' . $offset . ', '. $limit;

        return $this->CI->db->query($sql)->result();
    }

    public function get_count_filtered()
    {
        $sql = "SELECT COUNT(0) AS count FROM (" . $this->get_query() . ") AS _sub_ "
            . $this->get_search_part_query();

        return $this->CI->db->query($sql)->row('count');
    }

    public function get_count_all()
    {
        $sql = "SELECT COUNT(0) AS count FROM (" . $this->get_query() . ") AS _sub_";

        return $this->CI->db->query($sql)->row('count');
    }

    public function get_query()
    {
        if(empty($this->query))
        {
            foreach($this->calls_record as $record) {
                [$method, $arguments] = $record;
                call_user_func_array([$this->CI->db, $method], $arguments);
            }
            
            $this->query = $this->CI->db->get_compiled_select();
            $this->CI->db->reset_query();
        }

        return $this->query;
    }

    public function get_search_part_query($searchable = NULL)
    {
        $term = trim($this->get_input_param($this->config['term_param']));

        if(is_null($searchable)) {
            $searchable = [];
            foreach((array) $this->config['columns'] as $column => $definition) {
                if(in_array('searchable', (array) $definition)) {
                    $searchable[] = $column;
                }
            }
        }

        if($term && !empty($searchable)) {
            $escaped_term = $this->CI->db->escape_like_str($term);
            $where = array_map(function($column) use ($escaped_term) {
                return $column . " LIKE '%" . $escaped_term . "%' ESCAPE '!'";
            }, $searchable);

            if(!empty($where)) {
                return "WHERE " . join(' OR ', $where);
            }
        }
    }

    public function get_sort_part_query()
    {
        $sort = $this->get_input_param($this->config['sort_param']);

        $orderable = [];
        foreach((array)$this->config['columns'] as $column => $definition) {
            if(in_array('orderable', (array) $definition)) {
                $orderable[] = $column;
            }
        }

        if(is_array($sort) && !empty($sort) && !empty($orderable)) {
            $order_by = [];
            foreach($orderable as $column) {
                if(isset($sort[$column]) && in_array(strtolower($sort[$column]), ['asc', 'desc'])) {
                    $order_by[] = $column . ' ' . strtoupper($sort[$column]);
                }
            }

            if(!empty($order_by)) {
                return 'ORDER BY ' . join(', ', $order_by);
            }
        }
    }

    public function generate_select2($config = [])
    {
        $searchable = $config['searchable'] ?? NULL;
        $per_page = $config['per_page'] ?? $this->config['per_page'];
        $id_column = $config['id_column'] ?? NULL;
        $text_column = $config['text_column'] ?? NULL;
        $order_by = $config['order_by'] ?? NULL;

        $page = ((int) $this->get_input_param($this->config['page_param'])) ?: 1;
        $term = $this->get_input_param($this->config['term_param']);
        $term = trim($term);

        $offset = ($page - 1) * $per_page;
        $select = ['*'];
        if(!empty($text_column)) {
            $select[] = $text_column . ' AS text';
        }
        if(!empty($id_column)) {
            $select[] = $id_column . ' AS id';
        }
        
        $query = 'SELECT ' . join(', ', $select) . ' FROM (' . $this->get_query() . ') AS __sub__ '
            . $this->get_search_part_query($searchable)
            . ($order_by ? ' ORDER BY ' . $order_by : NULL)
            . ' LIMIT ' . $offset . ', ' . ($per_page + 1);
        
        $result = $this->CI->db->query($query)->result();
        
        $has_more = count($result) == ($per_page + 1);
        if($has_more) {
            array_pop($result);
        }

        $this->reset_query();

        return ['results' => $result, 'pagination' => ['more' => $has_more]];
    }

    public function generate_datatable()
    {
        $draw = $this->get_input_param('draw');
        $result = $this->get_data();
        $has_search = !!trim($this->get_input_param($this->config['term_param']));
        $count_all = $this->get_count_all();
        $count_filtered = $has_search ? $this->get_count_filtered() : $count_all;

        return [
            'draw' => $draw,
            'recordsTotal' => $count_all,
            'recordsFiltered' => $count_filtered,
            'data' => $result,
        ];
    }

    public function reset_query()
    {
        $this->CI->db->reset_query();
        $this->query = NULL;
        $this->calls_record = [];
    }

    private function get_input_param($param)
    {
        return call_user_func_array([$this->CI->input, $this->config['method']], [$param]);
    }

    private function set_config_value($key, $value) {
        if(array_key_exists($key, $this->config)) {
            $this->config[$key] = $value;
        }
    }

    private function get_searchable_columns() {
        $columns = $this->config['searchable'];
    }
}