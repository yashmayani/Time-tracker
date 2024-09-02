<style>
.modal-content {
    border-radius: 15px !important;
    
}

.modal-header {
    background-color: #e3ebff;
    border-radius: 10px 10px 0 0 !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;

}

.modal-title {
    font-size: 20px;

}

input,
select {
    box-shadow: 0 0 10px rgba(179, 165, 165, 0.3);
    border: none !important;
}

.delete-modal {
    text-align: center;
       
}

/* Default container styles */
.container {
    width: 100% !important;
    max-width: 1200px !important;
    /* Maximum width for larger screens */


}

/* Responsive adjustments for medium screens (tablets) */
@media (max-width: 768px) {
    .container {
        padding: 0 10px !important;
        /* Reduced padding for smaller screens */
    }
}

/* Responsive adjustments for small screens (mobile) */
@media (max-width: 576px) {
    .container {
        padding: 0 5px !important;
        /* Further reduced padding for very small screens */
    }
}

.logouts {
    color: white;
    background-color: #524FFF;
    font-size: 20px !important;
    font-weight: 100px !important;

}

.sss {
    margin-bottom: 20px;
    background-color: black;
    color: white;
    height: 45px;
    border-radius: 10px 0 0px 10px;
    display: flex;
    align-items: center;
     cursor: pointer;


}


</style>
<div class="sidebar">
    <div class="item-wrapper">
        <img class="login-logo" src="./site_img/podev logo.png" width='200px' alt="Screenshot"
            style="margin-bottom:52px;">
        <a href="dashboard.php" class="sidebar-a <?php if ($current_page == 'dashboard.php') echo 'active'; ?>">
            <div class="sidebar-item <?php if ($current_page == 'dashboard.php') echo 'active'; ?>">
                Update
            </div>
        </a>
        <a href="addproject.php" class="sidebar-a <?php if ($current_page == 'addproject.php'||$current_page == 'view_project.php' ) echo 'active'; ?>">
            <div class="sidebar-item <?php if ($current_page == 'addproject.php' ||$current_page == 'view_project.php') echo 'active'; ?>">
                Project
            </div>
        </a>
        <?php if ($_SESSION['role']==1) {?>
        <a href="employee.php" class="sidebar-a <?php if ($current_page == 'employee.php'||$current_page == 'view_emp.php') echo 'active'; ?>">
            <div class="sidebar-item <?php if ($current_page == 'employee.php'||$current_page == 'view_emp.php') echo 'active'; ?>">
                Employee
            </div>
        </a>
        <?php }?> 
    </div>

    <div class="">
        <div class="sss btn-sm px-2" data-bs-toggle="modal" data-bs-target="#logoutModal"> <svg
                xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="white">

                <path
                    d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" />
            </svg>
            Logout
        </div>
    </div>   
</div>
</div>
<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" >
        <div class="modal-content delete-modal2">
            <div class="modal">
                <h5 class="modal-title" id="logoutModalLabel">Logout Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body delete-modal">
                <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="red"
                    style="background-color:#ebecef; border-radius: 10px !important;  width: 50px; height: 40px; margin-top:19px;">
                    <path
                        d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" />
                </svg>
                <br />
                <p style=color:red;><b>Logout</b></p>

                <p>Are you want to sure log out this account?</p>
            </div>
            <div class="yash" style="display: flex; gap: 14px !important;">
                <button type="button" data-bs-dismiss="modal"
                    style="background-color:#ebecef; color: black; border-radius: 5px !important; width: 90px; height: 38px; border:none;">
                    Cancel</button>
                <a href="logout.php" class="btn btn-danger"><b>Logout</b></a>
            </div>
        </div>
    </div>
</div>