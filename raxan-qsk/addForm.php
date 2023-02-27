<?php
/**
 * Contact List Demo
 * A degradable Ajax Web form example
 */

require_once 'raxan/pdi/autostart.php';

Raxan::loadConfig('config.php'); // load external config file
Raxan::config('site.timezone','America/Toronto'); // set timezone

class ContactPage extends RaxanWebPage {

    protected $db;
    protected $updateList;
   
    protected function _config() {
        $this->degradable = true;
        $this->preserveFormContent = true;

        $this->masterTemplate = 'views/master.php'; // set master template
        $this->autoAppendView = 'directory.view.php';  // set page view file name

        // enable or disable debugging
        $this->Raxan->config('debug', false);
     }

    protected function _init() {        
        $this->loadCSS('master');   // load css framework and default theme
        $this->loadTheme('default'); // load default/theme.css
        $this->connectToDB();   // connect to db
    }
    
    protected function _prerender() {
        // show Employee
        if (!$this->isCallback||$this->updateList) $this->showEmployee(); 
    }

    // Events Handlers -------------------

    protected function cancelEdit($e) {
        $this->resetForm(true);
    }

    // add or update contact
    protected function saveEmployee($e) {
        // sanitize input
        $data = $this->post->filterValues('first_name,last_name,birth_date,gender,emp_no');
       
        // validate
        $msg = '';
        if ($data['first_name']=='') $msg = "* Missing First Name<br />";
        if ($data['last_name']=='') $msg.= "* Missing Last Name<br />";
        if ($data['emp_no']=='') $msg.= "* Missing Employee No.<br />"; 
        if ($data['birth_date']=='') $msg.= "* Missing D.O.B<br />";
        if ($data['gender']=='') $msg.= "* Missing Gender<br />"; 
    
        if ($msg) {
            // flash validation message to screen
            $this->flashmsg($msg,'fade','rax-box error');
        }
        else {
            try {
                // insert/update record
                $id = $this->rowid->intVal(); // get row id
                if ($id) $this->db->tableUpdate('employees',$data,'id=?',$id);
                else $this->db->tableInsert('employees',$data);
                $this->flashmsg('Record successfully '.($id ? 'modified':'created'),'fade','rax-box success');
            }
            catch(Exception $e) {
                $msg = 'Error while saving record';
                $this->flashmsg($msg,'fade','rax-box error');
                $this->Raxan->debug($msg.' '.$e);
                return;
            }
            $this->updateList = true;
            $this->resetForm($id ? true : false); // reset form fields
        }
    }

    protected function editEmployee($e) {
        try {
            $id = $e->intVal() | 0;
            $row = $this->db->table('employees','id=?',$id);
            if (!$row) throw new Exception('Invalid redord');
            
            // populate form field
            $this->first_name->val($row[0]['first_name']);
            $this->last_name->val($row[0]['last_name']);
            $this->birth_date->val($row[0]['birth_date']);
            $this->emp_no->val($row[0]['emp_no']);
            $this->gender->val($row[0]['gender']);

            // set value to be returned by event when form is submitted
            $this->rowid->val($id); // set event value using form class

            // setup buttons
            $this->cmdcancel->show();
            $this->cmdsave->val('Save Contact')->addClass('process');

            // update web form
            $this->contact->updateClient();
        }
        catch(Exception $e) {
            $msg = 'Error while ediing record';
            $this->flashmsg($msg,'fade','rax-box error');
            $this->Raxan->debug($msg.' '.$e);
        }
    }

    protected function removeEmployee($e) {
        try {
            $id = $e->intval();
            $this->db->tableDelete('employees','id=?',$id);
            $this->updateList = true;
            $this->flashmsg('Record successfully removed','fade','rax-box success');
        } catch(Exception $e) {
            $msg = 'Error while deleting records';
            $this->flashmsg($$msg,'fade','rax-box error');
            $this->Raxan->debug($msg.' '.$e);
            return;
        }
    }

    protected function resetForm($isEdit) {
        $selector = '#contact input.textbox, #rowid';
        $this[$selector]->val(''); // clear form text fields
        if ($isEdit) {
            $this->cmdcancel->hide();
            $this->cmdsave->val('Add Employee')
                 ->removeClass('process');
        }
        $this->contact->updateClient(); // update web form
    }

    protected function showEmployee() {        
        try {
            if (!$this->db) $this->list1->remove(); // remove list1
            else {
                $rs = $this->db->query('SELECT * FROM employees ORDER BY id desc');
                $this->list1->bind($rs); // bind result to #list
                $this->list1->updateClient(); // manually update list on client
            }
        } catch(Exception $e) {
            $msg = 'Error while fetching records';
            $this->flashmsg($msg,'fade','rax-box error');
            $this->Raxan->debug($msg.' '.$e);
        }
    }

    protected function connectToDB() {
        try {
            $this->db = $this->Raxan->connect('raxandb'); // connect to db
       } catch(Exception $e) {
            $msg ='Error while connecting to database.';
            $this->Raxan->debug($msg.' '.$e);
            $this->flashmsg($msg,'fade','rax-box error');
        }
    }

}

?>