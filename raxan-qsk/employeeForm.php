<?php

require_once 'raxan/pdi/autostart.php';

// system configuration
Raxan::loadConfig('config.php'); // load external config file

Raxan::config('site.timezone','America/Toronto'); // set timezone

class EmployeePage extends RaxanWebPage {

    protected $db;
    protected $updateList;
    protected $pgNumber, $pageSize;

    protected function _config() {

        $this->masterTemplate = 'views/master.php'; // set master template
        $this->autoAppendView = 'employeeForm.view.php';  // set page view file name

        $this->pageSize = 10;
        // enable or diable debugging
        $this->Raxan->config('debug',false);
    }

    protected function _init() {

        try {
            $this->db = $this->Raxan->connect('raxandb'); // connect to db
        }
        catch (Exception $e) {
            $this->db = null;
            $msg = $this->getView('connection-failed.html');
            $this->flashmsg($msg,'bounce','rax-box error');
        }
    }

    protected function saveContact($e) {
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
            $this->cmdsave->val('Save Employee')->addClass('process');

            // update web form
            $this->employee->updateClient();
        }
        catch(Exception $e) {
            $msg = 'Error while ediing record';
            $this->flashmsg($msg,'fade','rax-box error');
            $this->Raxan->debug($msg.' '.$e);
        }
    }

    protected function removeEmplyee($e) {
        try {
            $id = $e->intval();
            $this->db->tableDelete('employee','id=?',$id);
            $this->updateList = true;
            $this->flashmsg('Record successfully removed','fade','rax-box success');
        } catch(Exception $e) {
            $msg = 'Error while deleting records';
            $this->flashmsg($msg,'fade','rax-box error');
            $this->Raxan->debug($msg.' '.$e);
            return;
        }
    }

    protected function _prerender() {
        if ($this->db) $this->loadEmployees();
        else  $this->panel1->remove(); // remove table content
    }

    // -- Event handlers

    protected function changePage($e){
        $this->pgNumber = $e->intVal();
        if (!$this->pgNumber) $this->pgNumber = 1;
    }

    protected function rowClick($e){
        $id = $e->intVal() | 0;   // sanitize: convert to number
        if (!$e->ctrlKey && !$e->metaKey) $this->data('emp-selected-ids',$id);
        else {  // multiple selection
            $oldId = & $this->data('emp-selected-ids');
            if (!is_array($oldId)) $oldId = array($oldId);
            $oldId[] = $id;
        }
    }

    protected function cancelEdit($e) {
        $this->resetForm(true);
    }


    protected function showEmplyees() {        
        try {
            if (!$this->db) $this->list1->remove(); // remove list1
            else {
                $rs = $this->db->query('SELECT * FROM employees ORDER BY id desc');
                $this->list1->bind($rs); // bind result to #list
                $this->list1->updateClient(); // manually update list on client
            }
        } catch(Exception $e) {
            $msg = 'Error while fetching records';
            $this->flashmsg($this->icon.$msg,'fade','rax-box error');
            $this->Raxan->debug($msg.' '.$e);
        }
    }

    protected function loadEmployees() {

         // count # of rows in database
        $rowCount = & $this->data('emp-row-count');
        if (!$rowCount) {
            $rows = $this->db->query('select count(*) as total from employees');
            $rowCount = $rows->fetchColumn(0);
        }

        // load employees for current page
        $lower = (($this->pgNumber-1) * $this->pageSize)+1;
        $offset = $this->pageSize;
        $sql = "select *,concat(first_name,' ',last_name) as 'name' from employees order by emp_no";
        $rows = $this->db->query($sql);
        
        $this->emplistBody->bind($rows,array(
            'altClass' => 'even',
            'selectClass' => 'rax-selected-pal rax-metalic',
            'key'=>'emp_no',
            'selected' => $this->data('emp-selected-ids'),
            'initRowCount' => $lower,
            'format' => array(
                'name'=>'capitalize',
                'birth_date'=>'date:d M, Y'
             )
        ));

        // add hover effect to table rows
        c('#emplist tbody tr')->hoverClass('hover');
    
        // setup pager
        $maxpage = ceil($rowCount/$this->pageSize);
        $pages = $this->Raxan->paginate($maxpage,$this->pgNumber,array(
            'tpl' => '<a href="#{VALUE}" title="Page {VALUE}" class="{ROWCLASS}">{VALUE}</a>',
            'itemClass' => 'rax-active-pal',
            'selectClass' => 'rax-selected-pal rax-metalic border1',
            'delimiter'=>'',
        ));
        if ($maxpage > 1) {
            $pages.='<a href="#'.($this->pgNumber+1).'" title="Next Page" class="rax-active-pal">'.
            '<span class="ui-icon ui-icon-triangle-1-e"></span></a>';
        }
        if ($this->pgNumber> 1 && $this->pgNumber < $maxpage ) {
            $pages = '<a href="#'.($this->pgNumber-1).'" title="Prvious Page" class="rax-active-pal">'.
            '<span class="ui-icon ui-icon-triangle-1-w"></span></a>'.$pages;
        }
    }

}

?>