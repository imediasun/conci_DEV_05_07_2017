<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 * This controller contains the functions related to cms management 
 * @author Casperon
 *
 */
class Cms extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('cookie', 'date', 'form', 'url'));
        // $this->xss_clean_status();
        $this->load->library(array('encrypt', 'form_validation'));
        $this->load->model('cms_model');
        if ($this->checkPrivileges('cms', $this->privStatus) == FALSE) {
            redirect('admin');
        }
        $c_fun = $this->uri->segment(3);
        $restricted_function = array('delete_cms', 'change_cms_status_global');
        if (in_array($c_fun, $restricted_function) && $this->data['isDemo'] === TRUE) {
            $this->setErrorMessage('error', 'You are in demo version. you can\'t do this action.','admin_template_demo_version');
            redirect($_SERVER['HTTP_REFERER']);
            die;
        }
    }

//    /** Editing system/core/Input.php to toggle the status of our XSS_CLEAN * */
//    public function xss_clean_status() {
//        $path_of_file = "system/core/Input.php";
//        $get_file_contents = file_get_contents($path_of_file);
//        $valid = false; 
//        if($get_file_contents!==false){
//            if(strpos($get_file_contents,"$_enable_xss = FALSE" )!==FALSE){
//                
//            }
//        }
//            
//        
//        //$replaceWith = str_replace("$_enable_xss = FALSE", "$_enable_xss = TRUE", $get_file_contents);
//        //file_put_contents($path_of_file, $replaceWith);
//        echo $get_file_contents;
//        die;
//    }

    /**
     * 
     * This function loads the cms list page
     */
    public function index() {
        if ($this->checkLogin('A') == '') {
            redirect('admin');
        } else {
            redirect('admin/cms/display_cms');
        }
    }

    /**
     * 
     * This function loads the cms list page
     */
    public function display_cms() {

        if ($this->checkLogin('A') == '') {
            redirect('admin');
        } else {
		    if ($this->lang->line('admin_cms_static_pages') != '') 
		    $this->data['heading']= stripslashes($this->lang->line('admin_cms_static_pages')); 
		    else  $this->data['heading'] = 'Static Pages';
            $condition = array();
            $this->data['cmsList'] = $this->cms_model->get_all_details(CMS, $condition);
            $this->load->view('admin/cms/display_cms', $this->data);
        }
    }

    /**
     * 
     * This function loads the add new cms form
     */
    public function add_cms_form() {
        if ($this->checkLogin('A') == '') {
            redirect('admin');
        } else {
		    if ($this->lang->line('admin_cms_add_new_main_page') != '') 
		    $this->data['heading']= stripslashes($this->lang->line('admin_cms_add_new_main_page')); 
		    else  $this->data['heading'] = 'Add New Main Page';
            $this->load->view('admin/cms/add_cms', $this->data);
        }
    }

    /**
     * 
     * This function loads the add new subpage form
     */
    public function add_subpage_form() {
        if ($this->checkLogin('A') == '') {
            redirect('admin');
        } else {
		    if ($this->lang->line('admin_cms_add_new_sub_page') != '') 
		    $this->data['heading']= stripslashes($this->lang->line('admin_cms_add_new_sub_page')); 
		    else  $this->data['heading'] = 'Add New Sub Page';
            $condition = array('category' => 'Main');
            $this->data['cms_details'] = $this->cms_model->get_all_details(CMS, $condition);
            if ($this->data['cms_details']->num_rows() > 0) {
                $this->load->view('admin/cms/add_sub_page', $this->data);
            } else {
                $this->setErrorMessage('error', 'You must add a main page first','admin_pages_must_add_main');
                redirect('admin/cms/display_cms');
            }
        }
    }

    /**
     * 
     * This function insert and edit a cms page
     */
    public function insertEditCms() {

        if ($this->checkLogin('A') == '') {
            redirect('admin');
        } else {
            $cms_id = $this->input->post('cms_id');
            $lang_code = $this->input->post('lang_code');
            $parent_id = $this->input->post('parent');
            $css_descrip = $this->input->post('css_descrip',FALSE);
            $description = $this->input->post('description',FALSE);
            $page_name = $this->input->post($lang_code);
            if (empty($page_name))
                $page_name = $this->input->post('page_name');

            $subpage = $this->input->post('subpage');
            if ($subpage == 'subpage') {
                if ($parent_id == '') {
                    $this->setErrorMessage('error', 'Select a main page','admin_pages_select_main_page');
                    echo "<script>window.history.go(-1)</script>";
                    exit();
                }
            }
            if ($page_name == '') {
                $this->setErrorMessage('error', 'Page name required','admin_pages_name_required');
                echo "<script>window.history.go(-1)</script>";
                exit();
            }
            $parent = '0';
            $category = 'Main';
            if ($parent_id != '') {
                $parent = $parent_id;
                $category = 'Sub';
            }
            if ($cms_id == '') {
                $condition = array('page_name' => $page_name);
                $duplicate_name = $this->cms_model->get_all_details(CMS, $condition);
            } else {

                $condition = array('page_name' => $page_name);
                $duplicate_name = $this->cms_model->check_page_exist($condition, $cms_id);
            }

            if ($duplicate_name->num_rows() > 0) {
                $this->setErrorMessage('error', 'Page name already exists','admin_pages_name_already_exists');
                redirect('admin/cms/display_cms');
            }
            $excludeArr = array("cms_id", "hidden_page", "subpage", "lang_code","use_banner","old_banner_img","css_descrip");
            $datestring = "%Y-%m-%d";
            $time = time();
            
			$use_banner = $this->input->post('use_banner');
			if ($use_banner == 'on') {
				$use_banner = 'Yes';
			} else {
				$use_banner = 'No';
			}
			
			if($this->input->post('old_banner_img') != '') $banner_data = array('banner_img' => $this->input->post('old_banner_img')); else $banner_data = array();  
			if($_FILES['banner_img']['name'] != ''){
				$config['overwrite'] = FALSE;
				$config['encrypt_name'] = TRUE;
				$config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
				$config['max_size'] = 2000;
				$config['upload_path'] = './images/banner';
				$this->load->library('upload', $config);
				if ($this->upload->do_upload('banner_img')) {
					$bannerDetails = $this->upload->data();
					$ImageName = $bannerDetails['file_name'];
				} else {
					$bannerDetails = $this->upload->display_errors();
					$this->setErrorMessage('error', strip_tags($bannerDetails));
					echo '<script>window.history.go(-1);</script>';
				}
				$banner_data = array('banner_img' => $ImageName);
			}

			$dataArray = array('css_descrip' => $css_descrip,'description' => $description);
			if ($cms_id == '') {
                $hidden_page = $this->input->post('hidden_page');
                if ($hidden_page == 'on') {
                    $hidden_page = 'Yes';
                } else {
                    $hidden_page = 'No';
                }
				
				
                $seourl = url_title($page_name, '-', TRUE);
                $dataArr = array(
                    'status' => 'Publish',
                    'seourl' => $seourl,
                    'hidden_page' => $hidden_page,
					'use_banner' => $use_banner,
                    'parent' => $parent,
                    'category' => $category
                );
                
                $dataArr = array_merge($dataArray,$dataArr);
            } else {
                $dataArr = array('parent' => $parent,'use_banner' => $use_banner);
				$dataArr = array_merge($dataArray,$dataArr);
            }
            if ($cms_id != '') {
                $condition = array('_id' => new \MongoId($cms_id));
            } else {
                $condition = array();
            }
            $translated = array(); 
            /* Getting the list of all languages which has been already translated  and adding new language if not added */
            if ($lang_code != '') {
                $added_languages = $this->cms_model->get_selected_fields(CMS, $condition, array('translated_languages'))->row();

                if (isset($added_languages->translated_languages)) {
                    foreach ($added_languages->translated_languages as $added) {
                        $translated[] = $added;
                    }

                    if (!in_array($lang_code, $translated))
                        $translated[] = $lang_code;

                    $translated_langs = array('translated_languages' => $translated);
                    $dataArr = array_merge($dataArr, $translated_langs);
                }else {
                    $translated[] = $lang_code;

                    $translated_langs = array('translated_languages' => $translated);
                    $dataArr = array_merge($dataArr, $translated_langs);
                }
				
				$langdataArr[$lang_code] = $this->input->post($lang_code); 
				
				if(isset($banner_data['banner_img'])){
					$langdataArr[$lang_code]['banner_img'] = $banner_data['banner_img'];
					unset($banner_data['banner_img']);
				} 
				$langdataArr[$lang_code]['use_banner'] = $dataArr['use_banner'];
				unset($dataArr['use_banner']);
				#$langdataArr[$lang_code] = $langdataArr;
				$langdataArr = array_merge($langdataArr,$translated_langs);
				
            }
			
			
			
			$dataArr = array_merge($banner_data,$dataArr);  #echo $lang_code; echo '<pre>'; print_r($langdataArr); die;
			
            if($lang_code == ''){
				if ($cms_id == '') {
					$this->cms_model->commonInsertUpdate(CMS, 'insert', $excludeArr, $dataArr, $condition);
					$this->setErrorMessage('success', 'Page added successfully','admin_pages_name_added_success');
					if ($seourl == '') {
						$cms_id = $this->cms_model->get_last_insert_id();
						$seourl = $cms_id . '/' . str_replace(' ', '', $page_name);
						$this->cms_model->update_details(CMS, array('seourl' => $seourl), array('id' => $cms_id));
					}
				} else {
					$data = $this->cms_model->commonInsertUpdate(CMS, 'update', $excludeArr, $dataArr, $condition);
					$this->setErrorMessage('success', 'Page updated successfully','admin_pages_name_updated_success');
				}
			} else {
				$data = $this->cms_model->update_details(CMS,$langdataArr, $condition);
			}
			
            redirect('admin/cms/display_cms');
        }
    }

    /**
     * 
     * This function loads the edit cms form
     */
    public function edit_cms_form() {
        if ($this->checkLogin('A') == '') {
            redirect('admin');
        } else {
            try {
			    if ($this->lang->line('admin_cms_edit_page') != '') 
		        $this->data['heading']= stripslashes($this->lang->line('admin_cms_edit_page')); 
		        else  $this->data['heading'] = 'Edit Page';
                $cms_id = $this->uri->segment(4, 0);
                $this->data['language_code'] = $language_code = $this->uri->segment(5, 0);
                $condition = array('_id' => new \MongoId($cms_id));
                $this->data['cms_details'] = $this->cms_model->get_all_details(CMS, $condition);
                $this->data['cms_id'] = $cms_id;

                /* Sending all the languages that has been already translated */
                $added_languages = $this->cms_model->get_selected_fields(CMS, $condition, array('translated_languages'))->row();
                if (isset($added_languages->translated_languages)) {
                    $this->data['translated_languages'] = $added_languages->translated_languages;
                }
                /* Sending all ---> Ends heree..... */

                if (!empty($language_code)) {
                    $this->data['cms_details'] = $this->cms_model->get_all_details(CMS, $condition);
                }
                $this->data['langList'] = $this->cms_model->get_selected_fields(LANGUAGES, array(), array('name', 'lang_code'))->result();
                if ($this->data['cms_details']->num_rows() == 1) {
                    $condition = array('category' => 'Main');
                    $this->data['cms_main_details'] = $this->cms_model->get_all_details(CMS, $condition);
                    $this->load->view('admin/cms/edit_cms', $this->data);
                } else {
                    redirect('admin');
                }
            } catch (MongoException $me) {
                $this->setErrorMessage('Error', 'Internal Error','admin_pages_name_internal_error');
                redirect('admin/cms/display_cms');
            }
        }
    }

    /**
     * 
     * This function change the cms page status
     */
    public function change_cms_status() {
        if ($this->checkLogin('A') == '') {
            redirect('admin');
        } else {
            $mode = $this->uri->segment(4, 0);
            $cms_id = $this->uri->segment(5, 0);
            $status = ($mode == '0') ? 'Unpublish' : 'Publish';
            $newdata = array('status' => $status);
            $condition = array('_id' => new \MongoId($cms_id));
            $this->cms_model->update_details(CMS, $newdata, $condition);
            $this->setErrorMessage('success', 'Page Status Changed Successfully','admin_pages_status_change');
            redirect('admin/cms/display_cms');
        }
    }

    /**
     * 
     * This function change the cms page display mode
     */
    public function change_cms_mode() {
        if ($this->checkLogin('A') == '') {
            redirect('admin');
        } else {
            $mode = $this->uri->segment(4, 0);
            $cms_id = $this->uri->segment(5, 0);
            $newdata = array('hidden_page' => $mode);
            $condition = array('_id' => new \MongoId($cms_id));
            $this->cms_model->update_details(CMS, $newdata, $condition);
            $this->setErrorMessage('success', 'Page Hidden Mode Changed Successfully','admin_pages_hidden_mode_change');
            redirect('admin/cms/display_cms');
        }
    }

    /**
     * 
     * This function delete the cms page from db
     */
    public function delete_cms() {
        if ($this->checkLogin('A') == '') {
            redirect('admin');
        } else {
            $cms_id = $this->uri->segment(4, 0);
            $condition = array('_id' => new \MongoId($cms_id));
            $this->cms_model->commonDelete(CMS, $condition);
            $this->setErrorMessage('success', 'Page deleted successfully','admin_pages_deleted_success');
            redirect('admin/cms/display_cms');
        }
    }

    /**
     * 
     * This function change the cms pages status
     */
    public function change_cms_status_global() {
        if (count($_POST['checkbox_id']) > 0 && $_POST['statusMode'] != '') {
            $this->cms_model->activeInactiveCommon(CMS, '_id');
            if (strtolower($_POST['statusMode']) == 'delete') {
                $this->setErrorMessage('success', 'Pages deleted successfully','admin_pages_deleted_success');
            } else {
                
            }
            redirect('admin/cms/display_cms');
        }
    }
	
	
	
    /**
     * 
     * This function loads the landing page descriptions form
     */
    public function add_landing_page_form() {
        if ($this->checkLogin('A') == '') {
            redirect('admin');
        } else {
			$this->data['translated_languages']=array();
			if ($this->lang->line('admin_cms_landing_page_description') != '') 
		    $this->data['heading']= stripslashes($this->lang->line('admin_cms_landing_page_description')); 
		    else  $this->data['heading'] = 'Landing Page Description';
			$this->data['langList'] = $this->cms_model->get_selected_fields(LANGUAGES, array(), array('name', 'lang_code'))->result();
			$this->data['language_code'] = $language_code = $this->uri->segment(4, 0);
			$landgin_details=$this->data['landing_details']=$this->cms_model->get_all_details(LANDING_CONTENT);
			if($landgin_details->num_rows()>0){
				/* Sending all the languages that has been already translated */
				$added_languages = $this->cms_model->get_selected_fields(LANDING_CONTENT, array(), array('translated_languages'))->row();
				if (isset($added_languages->translated_languages)) {
					$this->data['translated_languages'] = $added_languages->translated_languages;
				}
				/* Sending all ---> Ends heree..... */
			}
			
			$this->load->view('admin/cms/landing_page', $this->data);
        }
    }
	
	 /**
     * 
     * This function loads the landing page descriptions form
     */
    public function add_edit_landing_page_content() {
	
		if ($this->checkLogin('A') == '') {
            redirect('admin');
        } else {
           $landing_details=$this->cms_model->get_all_details(LANDING_CONTENT);
		   $language_code = $this->input->post('lang_code');
		   $landing_content = $this->input->post('landing_page_content',FALSE);
		   $excludeArr=array('landing_page_content','lang_code');
		   $tlangs=$translated_langs=array();
		   if(isset($landing_details->row()->translated_languages)){
			   $tlangs=$landing_details->row()->translated_languages;
		   }
		   
				if (isset($tlangs)&& !empty($tlangs)) {
					foreach ($tlangs as $added) {
						if($added != '')
						$translated[] = $added;
					}

					if (!in_array($language_code, $translated))
						$translated[] = $language_code;
				}else {
					$translated[] = $language_code;
				}
				$translated_langs = array('translated_languages' => $translated);
			
			 if($landing_details->num_rows() <=0){
			   
			    $dataDetails=$condition=array();
				$dataDetails=array('landing_page_content'=>addslashes($landing_content));
				if($language_code !='en' && $language_code != ''){
				$dataDetails=array($language_code=>array('landing_page_content'=>addslashes($landing_content)));	
				}
				$dataArr = array_merge($dataDetails, $translated_langs);
				$this->cms_model->commonInsertUpdate(LANDING_CONTENT, 'insert', $excludeArr,$dataArr,$condition); 
				$this->setErrorMessage('success', 'Inserted successfully','admin_pages_insert_success');
				
		   }else{
			  $land_content_id=$landing_details->row()->_id;
			  $condition=array('_id'=>$land_content_id);
			  $dataDetails=array('landing_page_content'=>addslashes($landing_content));
			  if($language_code !='en' && $language_code !=''){
				  $dataDetails=array($language_code.'.landing_page_content'=>addslashes($landing_content));
			  }
			  $dataArr = array_merge($dataDetails, $translated_langs);
			  $this->cms_model->commonInsertUpdate(LANDING_CONTENT, 'update', $excludeArr, $dataArr, $condition);
			  $this->setErrorMessage('success', 'Updated successfully','admin_pages_update_success');
		   }
		  
		 }
		  redirect('admin/cms/add_landing_page_form');
    }

}

/* End of file cms.php */
/* Location: ./application/controllers/admin/cms.php */