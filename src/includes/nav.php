<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><i class="fa-solid fa-fire"></i></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01"
            aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav me-auto">
                <!-- add navlink items based on users role -->
                <?php 
                    if(isset($_SESSION['role'])) {
                ?>
                <?php if($_SESSION['role'] == 'Admin') { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/views/admin.php">Admin Panel
                    </a>
                </li>
                <?php } ?>
                <?php if ($_SESSION['role'] == 'Preparer' || $_SESSION['role'] == 'Server') { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/views/schedule.php">My Schedule
                    </a>
                </li>
                <?php } ?>
                <?php } ?>
            </ul>
            <div class="d-flex align-items-center">
                <?php 
                    if(!isset($_SESSION['username'])) {
                ?>
                <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#loginModal">log
                    in</button>
                <?php } else { ?>
                <div class="d-flex align-items-center gap-2">
                    <p class="mb-0 text-black"><i class="fa-regular fa-user"></i> <?php echo $_SESSION['username'];  ?>
                    </p>
                    <button class="btn btn-outline-warning" id="submit-logout-btn">log
                        out</button>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>

<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="loginModalLabel">Log In</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="username" name="username"
                            placeholder="name@example.com">
                        <label for="username">Email address</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                            autocomplete="off">
                        <label for="password">Password</label>
                    </div>
                    <span class="text-danger mt-3 d-none" id="login-error"><i
                            class="fa-solid fa-circle-exclamation"></i>
                        Incorrect username/password, please try again.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" id="submit-login-btn">log in</button>
            </div>
        </div>
    </div>
</div>