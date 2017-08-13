<?php namespace MAILCLIENT_PLUGIN_NAME;
/*  
    Copyright 2009-2015 ABS-Hosting.nl (email: cees@abs-hosting.nl)

    This file is part of oMailCient, a plugin for WordPress.

    MailCient is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    MailCient is distributed in the hope that it is useful,
    but WITHOUT ANY WARRANTY; Without even the implied WARRANTY of
    MERCHANTABILITY, ERRORS or FITNESS FOR A PARTICULAR PURPOS.  
    See the GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    Or look at   License URI: http://www.gnu.org/licenses/gpl-2.0.html

*/
class mc_div_table {
    /**
     * ABS_div_table::__construct()
     * some defaults are set
     */
    public $apath;
    public $delete = false;
    public $center = true;
    public $border = 'border:1px solid gray;';
    public $hr = false;
    public $padding = 'padding:5px 5px 5px 5px;';
    public $class = '';
    public $column = false;
    public $bgcolumn = false;
    public $widthColumn = false;
    public $heading = true;
    public $headbg = '#E3E3E3';
    public $headcolor = 'black';
    public $width = '98%';
    public $min_heigth = null;
    public $data;
    public function __construct(){
        /**
         * //   Test table
         * $this->data[] 	= array('First', 'Second', 'Thirdt');
         * $this->data[] 	= array('one', 'two', 'three');
         * $this->data[] 	= array('four', 'five', 'six');
         * $this->data[] 	= array('seven', 'eight', 'nine');
         * //   End test table
         */
        /**
         * /
         * <style>   //css can be anywhere you want
         * .float_left {
         * float:left;
         * }
         * .clear {
         * clear:both;
         * }
         * .odd {
         * background-color: #e3eaea;
         * }
         * .even {
         * background-color: #FFFFF0;
         * }
         * </style>
         * /*/
    }

    /**
     * ABS_div_table::show_div_table()
     *
     * @param mixed $config
     * @return
     */
    public function show_div_table($config = null){
        //Head and data should have equal amount of fields
        if (count($this->data[0]) !== count($this->widthColumn)) {
            return 'Fieldcount FALSE';
        }
        isset($config['center']) || $config['center'] = $this->center;
        isset($config['class']) || $config['class'] = $this->class;
        isset($config['column']) || $config['column'] = $this->column;
        isset($config['bgcolumn']) || $config['bgcolumn'] = $this->bgcolumn;
        isset($config['widthColumn']) || $config['widthColumn'] = $this->widthColumn;
        isset($config['heading']) || $config['heading'] = $this->heading;
        isset($config['headbg']) || $config['headbg'] = $this->headbg;
        isset($config['hr']) || $config['hr'] = $this->hr;
        isset($config['headcolor']) || $config['headcolor'] = $this->headcolor;
        isset($config['width']) || $config['width'] = $this->width;
        isset($config['min_heigth']) || $config['min_heigth'] = $this->min_heigth;
        $html = '<div class="clear_all"></div>';
        $html .= '<div class="clear_all"';
        if (isset($config['width'])) $html .= ' style="width:' . $config['width'] . '"';
        $html .= '>';
        //$html .= 'style="width:' . $config['width'] . ';';
        //if set, take values in html
        //(!isset($this->border))     ||$html .= $this->border;
        //(!isset($this->padding))    ||$html .= $this->padding;
        //(!isset($this->min_heigth)) ||$html .= 'min_height:' . $config['min_heigth'] . '; ';
        $heading_done = false;
        $iTel = 1;
        $cls = '';
        foreach ($this->data as $value){
            $cls = '';
            if (!$heading_done) {//set heading row color background
                $html .= '<div id="div_row" class="header">';
            //} elseif ($this->hr === true){
            //    $html .= '<div id="div_row" class="bhr">';
            } else {
                $cls = ($iTel % 2)?'maileven':'mailodd';
                $html .= '<div id="div_row" class="' . $cls . '">';
            }
            if ($config['heading'] && !$heading_done){
                if ($this->widthColumn == true) {
                    //$width = $this->widthColumn[$iTel];
                } else {
                    if ($this->delete){
                        $width = round(100 / (count($value) + 1)) . '%';
                        $value[] = '&nbsp';
                    }else{
                        $width = round(100 / count($value)) . '%';
                    }
                }
                $wTel = 1;    
                foreach ($value as $val){
                    if ($this->widthColumn == true) {
                        $width = $this->widthColumn[$wTel];
                    }
                    $html .= '<div ';
                    $align = ($config['center'])?'center':'left';
                    if ($config['heading']) {
                        $html .= 'style="font-weight:bold; display:block;' .
                                ' color:' . $config['headcolor'] . ';' .
                                'width:' . $width . ';' . 
                                //'background-color:' . $config['headbg'] . ';' .
								'text-align:' . $align . '" ';
                    }
                    $html .= 'class="float_left">';
                    $html .= $val;
                    $html .= '</div>';
                    $wTel++;
                }
                //$html .= '<div class="clear_all"></div>';
                $heading_done = true;
            }else{
                $align = ($config['column'])?'center':'left';
                $wTel = 1;    
                foreach ($value as $key => $val){
                    if ($this->widthColumn == true) {
                        $width = $this->widthColumn[$wTel];
                    }
                    if ($key !== '_id'){
                        $html .= '<div style="width:' . $width . '; ' . 'text-align:' . $align . '; ' . '"';
                        $html .= 'class="float_left ' . $config['class'] . '">';
                        $id_val = '';
                        if (isset($value['id'])) {
                            $id_val = $value['id'];
                        } elseif (isset($value['ID'])) {
                            $id_val = $value['ID'];
                        }
                        (strlen($val)>0)||$val="&nbsp;";
                        $html .= ($this->apath) ?'<a href="' . $this->apath . '/' 
                                                    . $id_val . '">' . $val . '</a>'
                                                :$val;
                        $html .= '</div>';
                    }else{
                        if ($this->delete){
                            $html .= '<div style="width:' . $width . '; ' .
                            'text-align:' . $align . '; ' . '"';
                            $html .= 'class="float_left ' . $config['class'] . ' ' . $cls . '">';
                            $html .= ($this->apath)?'<a href="' . $this->apath . '/' . $value['id'] . '/delete" class="alert"' . lang('delete') . '</a>':$val;
                            $html .= '</div>';
                        }
                    }
                    $wTel++;
                }
                //$html .= '<div class="clear_all"></div>';
            }
            $html .= '<div class="clear_all"></div>';
            $html .= '</div>';//end of div_row
            //if ($config['hr']) $html .= '<hr />';
            $iTel++;
        }
        $html .= '</div>';
        $this->data = null;
        return $html;
    }
}
/**
 * /
 * Sample with addresses
 *
 * if (count($address)>=1) {
 * //show div_table
 * $this->load->model('abs_div_table');
 * //set alignment
 * $this->abs_div_table->center = null;
 * //set deleted statement
 * $this->abs_div_table->delete = true;
 *
 * $ar = array();
 * //set headers with addressform config fields
 * foreach ($adrConf as $key => $value) {
 * $ar[] = ($f_data['fieldnames'][$key]);
 * }
 * //add header info to Model
 * $this->abs_div_table->data[] = $ar;
 * //get all values
 * foreach ($address as $value) {
 * $ar = array();
 * foreach ($adrConf as $key => $val) {
 * $ar[] = ($value[$key]);
 * }
 * $ar['id'] = $value['id'];
 * // add each value to abs_div_table Model
 * $this->abs_div_table->data[] = $ar;
 * }
 * //set action path to Model
 * $this->abs_div_table->apath = base_url() . '/userforms/editUserform/' . $user_id ;
 * //get HTML result form Model
 * $f_data['div_table'] = $this->abs_div_table->show_div_table();
 * //end div_table
 * }
 *
 * /*/