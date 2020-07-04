<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User profile -->
        <div class="user-profile">
            <!-- User profile image -->
            <div class="profile-img"> <img src="../<?php echo $user_img;?>" alt="user" /> </div>
            <!-- User profile text-->
            <div class="profile-text"> <a href="#" class="dropdown-toggle link u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><?php echo $user_surname;?><span class="caret"></span></a>
                <div class="dropdown-menu animated flipInY">
                    <a href="#" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                    <a href="#" class="dropdown-item"><i class="ti-wallet"></i> My Balance</a>
                    <a href="#" class="dropdown-item"><i class="ti-email"></i> Inbox</a>
                    <div class="dropdown-divider"></div> <a href="#" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a>
                    <div class="dropdown-divider"></div> <a href="../logout.php?logout=1" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
                </div>
            </div>
        </div>
        <!-- End User profile text-->
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- <li class="nav-small-cap">PERSONAL</li> -->
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-group"></i><span class="hide-menu">Manage Users</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="all_users.php">All Users</a></li>
                        <li><a href="all_researchers.php">All Researchers</a></li>
                        <li><a href="all_coordinators.php">All Coordinators</a></li>
                        <li><a href="add_user.php">Add User</a></li>
                    </ul>
                </li>

                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-book"></i><span class="hide-menu">All Researches</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="all_researches.php">All Researches</a></li>
                    </ul>
                </li>

                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-briefcase"></i><span class="hide-menu">All Reports</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="all_research_reports.php">All Research Reports</a></li>
                        <li><a href="all_user_reports.php">All User Reports</a></li>

                    </ul>
                </li>

                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-id-card"></i><span class="hide-menu">Manage Admins</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="all_admins.php">All Admins</a></li>
                        <li><a href="add_admin.php">Add Admin</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
    <!-- Bottom points-->
    <div class="sidebar-footer">
        <!-- item-->
        <a href="#" class="link" data-toggle="tooltip" title="Settings"><i class="ti-settings"></i></a>
        <!-- item-->
        <a href="#" class="link" data-toggle="tooltip" title="Email"><i class="mdi mdi-gmail"></i></a>
        <!-- item-->
        <a href="#" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a>
    </div>
    <!-- End Bottom points-->
</aside>