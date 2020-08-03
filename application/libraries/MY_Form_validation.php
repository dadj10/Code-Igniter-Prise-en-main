<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MY_Form_validation extends CI_Form_validation {
    public function __construct()
    {
        parent::__construct();
        $this->_error_prefix    = '<span  class="help-inline text-danger">';
        $this->_error_suffix    = '</span>';
    }
}
/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */