<?php defined('RAXANPDI')||exit(); ?>
<hr class="space" />
    <div class="container c30 prepend-top">
        <div class="rax-backdrop">
            <div class="rax-content-pal">
                <h2 class="title rax-header-pal round-top rax-metalic" ><img src="views/images/users.png" width="36" height="36" class="align-middle rtm"  />Add Employee</h2>
                <div class="flashmsg"></div>
                <br/>
                <div id="panel1" class="container">
                    <form id="contact" method="post">
                        <input type="hidden" name="rowid" id="rowid" value="" />
                        <div class="ctrl-group">
                            <label class="column c4">First Name:</label><input class="textbox" id="first_name" name="first_name" size="65" />
                        </div>
                        <div class="ctrl-group">
                            <label class="column c4">Last Name:</label><input class="textbox" id="last_name" name="last_name" size="65" />
                        </div>
                        <div class="ctrl-group">
                            <label class="column c4">Employee No:</label><input class="textbox" id="emp_no" name="emp_no" size="65" />
                        </div>
                        <div class="ctrl-group">
                            <label class="column c4">Birth Date:</label>
                            <input type="date" class="textbox" id="birth_date" name="birth_date" size="65" /><br />
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
                        <div id="btnbar" class="container" xt-preservestate>
                            <input id="cmdsave" type="submit" class="right button rtm " value="Add Contact" xt-bind="#click,saveContact,,true" />
                            <input id="cmdcancel" type="submit" class="lef #clickt button cancel hide" value="Cancel" xt-bind="#click,cancelEdit" /><br />
                        </div>
                    </form>
                    <br />
                    <h2 class="title rax-header-pal rax-metalic" ><img src="views/images/people.png" width="36" height="36" class="align-middle rtm"  />Available Records</h2>
               
                    <table id="emplist" class="rax-table mouse-cursor" cellpadding="0" cellspacing="0">
                        <tbody id="emplistBody">
                            <tr class="{ROWCLASS}" data-event-value="{emp_no}">
                                <h3 class="bottom"><img src="views/images/user.png" width="16" height="16" />{first_name} {last_name}</h3>
                                Employee No: {emp_no}
                                <br/>
                                D.O.B: {birth_date}
                                <br/>
                                Gender: {gender}
                            </tr>
                            <hr />
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- <hr /> -->
    </div>