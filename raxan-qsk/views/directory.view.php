<?php defined('RAXANPDI')||exit(); ?>
<!DOCTYPE html>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Contact List</title>
</head>

<body>
    <div class="container c25 prepend-top">
    <div class="flashmsg tpm" xt-autoupdate></div>
        <h2 class="rax-header-pal rax-glass round-top pad bottom border2"><img src="views/images/users.png" width="24" height="24" />&nbsp;Add Employee</h2>
        
        <div class="rax-box prepend1 pad" >
            <form id="contact" method="post">
                <input type="hidden" name="rowid" id="rowid" value="" />
                <div class="ctrl-group">
                    <label class="column c4">First Name:</label><input class="textbox" id="first_name" name="first_name" size="42" />
                </div>
                <div class="ctrl-group">
                    <label class="column c4">Last Name:</label><input class="textbox" id="last_name" name="last_name" size="42" />
                </div>
                <div class="ctrl-group">
                    <label class="column c4">Employee No:</label><input class="textbox" type="number" id="emp_no" name="emp_no" size="42" />
                </div>
                <div class="ctrl-group">
                    <label class="column c4">Birth Date:</label>
                    <input type="date" class="textbox" id="birth_date" name="birth_date" size="65" max="<?=date('Y-m-d',strtotime('now'));?>"/><br />
                </div>
                <div class="ctrl-group">
                    <label class="column c4">Gender:</label>
                    <select id="gender" name="gender">
                        <option value="" selected>Select Gender</option>
                        <option value="M">M</option>
                        <option value="F">F</option>
                    </select>
                    <br />
                </div>
                <hr />
                <div id="btnbar" class="container" xt-preservestate>
                    <input id="cmdsave" type="submit" class="right button rtm " value="Add Contact" xt-bind="#click,saveEmployee,,true" />
                    <input id="cmdcancel" type="submit" class="right button cancel hide" value="Cancel" xt-bind="#click,cancelEdit" /><br />
                </div>
            </form>
        </div>
        
        <br/>
        <h2 class="rax-header-pal rax-glass round-top pad bottom border2"><img src="views/images/users.png" width="24" height="24" />&nbsp;Employees List</h2>
        <div class="rax-box prepend1 pad" >
            <div id="list1" xt-delegate="a.edit #click,editEmployee; a.remove #click,removeEmployee">
                <div class="pad bmb">
                    <span class="right">
                        <a class="edit" href="#{id}" rel="nofollow">Edit</a>&nbsp;/&nbsp;
                        <a class="remove" href="#{id}" data-event-confirm="Are you sure you want to remove the selected record?" rel="nofollow">Remove</a>
                    </span>
                    <h3 class="bottom"><img src="views/images/user.png" width="16" height="16" />{first_name} {last_name}</h3>
                    Employee No.: {emp_no}
                    <br/>
                    D.O.B: {birth_date}
                    <br/>
                    Last Name: {gender}
                </div>
            </div>
        </div>
        <p>&nbsp;</p>
    </div>
</body>

</html>
