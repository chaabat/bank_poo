        <?php
        @include 'DataBase.php';

        $error = array();
       
        
        if (isset($_POST['add_user'])) {
            $fname = mysqli_real_escape_string($conn, $_POST['firstname']);
            $lname = mysqli_real_escape_string($conn, $_POST['lastname']);
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = $_POST['password'];
            $cpassword = $_POST['cpassword'];
        
            // Check if password and confirmation password match
            if ($password !== $cpassword) {
                $error[] = 'Password and confirmation password do not match!';
            } else {
                $pass = password_hash($password, PASSWORD_DEFAULT);
        
                // Check if the role exists
                $roleSelect = "SELECT rolename FROM roles WHERE rolename = ?";
                $stmtRole = mysqli_prepare($conn, $roleSelect);
                mysqli_stmt_bind_param($stmtRole, "s", $_POST['user-type']);
                mysqli_stmt_execute($stmtRole);
                mysqli_stmt_store_result($stmtRole);
        
                if (mysqli_stmt_num_rows($stmtRole) > 0) {
                    // Role exists, proceed with user insertion
                    $insert = "INSERT INTO users (firstName, familyName, username, pw)
                            VALUES (?, ?, ?, ?)";
                    $stmtUser = mysqli_prepare($conn, $insert);
                    mysqli_stmt_bind_param($stmtUser, "ssss", $fname, $lname, $username, $pass);
                    mysqli_stmt_execute($stmtUser);
        
                    // Get the inserted user's ID
                    $userId = mysqli_insert_id($conn);
        
                    // Insert role of the user into the roleofuser table
                    $roleOfUserInsert = "INSERT INTO roleofuser (userId, rolename)
                                        VALUES (?, ?)";
                    $stmtRoleOfUser = mysqli_prepare($conn, $roleOfUserInsert);
                    mysqli_stmt_bind_param($stmtRoleOfUser, "is", $userId, $_POST['user-type']);
                    mysqli_stmt_execute($stmtRoleOfUser);
        
                    // Close the database connection
                    mysqli_close($conn);
        
                    // Redirect after successful registration
                    header('location: adress.php');
                    exit; // Exit to ensure the script doesn't continue executing
                } else {
                    // Invalid user type
                    $error[] = 'Invalid user type!';
                }
            }
        }
        
        // ... Rest of your code
        
        
        if (isset($_POST['operation']) && $_POST['editing'] === 'Edit') {
            // Retrieve agency details for editing
            $id = $_POST["userid"];
            $userinfo = "SELECT * FROM users WHERE userid = $id";
            $stk_user_info = $conn->query($userinfo);
            $rows = mysqli_fetch_assoc($stk_user_info);

            // Populate variables with retrieved data
            $firstname = $rows["firstName"];
            $lastname = $rows["familyName"];
            $username = $rows["username"];
            $password = $rows["pw"];
            $cpassword = $rows["pw"];
            
        }


        if (isset($_POST['edited'])) {
            // Retrieve data from the form
            $id = $_POST["userid"];
            $newFirstName = mysqli_real_escape_string($conn, $_POST['firstname']);
            $newFamilyName = mysqli_real_escape_string($conn, $_POST['lastname']);
            $newUsername = mysqli_real_escape_string($conn, $_POST['username']);
            $newPassword = $_POST['password'];

            // Add password hashing logic if needed
            $newPass = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update user records based on user ID
            $updateSql = "UPDATE users SET firstName = '$newFirstName', familyName = '$newFamilyName', username = '$newUsername', pw = '$newPass' WHERE userId = $id";
            $conn->query($updateSql);

            // Update role in the roles table
            $roleSelect = "UPDATE roles SET rolename = ? WHERE rolename = ?";
            $stmtRole = mysqli_prepare($conn, $roleSelect);
            mysqli_stmt_bind_param($stmtRole, "ss", $_POST['user-type'], $_POST['original_user_type']);
            mysqli_stmt_execute($stmtRole);
            mysqli_stmt_store_result($stmtRole);

            // Update role in the roleofuser table
            $roleOfUserUpdate = "UPDATE roleofuser SET rolename = ? WHERE userId = ?";
            $stmtRoleOfUser = mysqli_prepare($conn, $roleOfUserUpdate);
            mysqli_stmt_bind_param($stmtRoleOfUser, "si", $_POST['user-type'], $id);
            mysqli_stmt_execute($stmtRoleOfUser);

            header('location: adress.php');
            exit;
        }

        $catchid = "SELECT userId from users";
        $idResult = $conn->query($catchid);
        $userIds = [];
        
        // Fetch all user IDs into an array
        while ($row = $idResult->fetch_assoc()) {
            $userIds[] = $row['userId'];
        }

        ?>







        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="https://cdn.tailwindcss.com"></script>
           



            <title>CIH_BANQUE LOGIN</title>
        </head>

        <body>


            <section class=" bg-blue-300 p-[50px]">
                <div class="min-h-[85vh] w-[90%] m-auto gap-[15px] flex flex-col md:flex-row md:justify-evenly items-center  ">
                    <div class="md:w-[50%] w-[85%] flex flex-col gap-[25px] mt-[15px]">
                        <h1 class="text-gray-900 text-[45px] md:text-[60px]">CIH BANQUE</h1>
                        <h3 class="text-gray-900 text-[25px] md:text-[30px]"> Your Gateway to Financial Harmony</h3>
                        <p class="text-gray-900 text-[15px] md:text-[18px]">
                            CIH Banque is your gateway to financial success, offering personalized solutions <br>
                            expert guidance to navigate your unique financial journey. With a commitment <br>
                            to trust and innovation, we stand as a reliable partner, empowering you to achieve <br>
                            your financial goals seamlessly. Join us for a transformative experience, where your<br>
                            prosperity is our priority.
                        </p>
                    </div>

                    <form action="Registre.php" method="post" class="flex flex-col gap-[19px] h-[70%] md:h-[80%] w-[80%] md:w-[30%] mb-[15px] p-[10px] bg-gray-300/20 items-center justify-center rounded-[20px]">
                        <h3 class="text-3xl mb-2.5 uppercase font-medium text-gray-900">ADD USER</h3>
        <?php
        if (!empty($error)) {
            echo '<div style="color: red;">';
            foreach ($error as $err) {
                echo $err . '<br>';
            }
            echo '</div>';
        }
        ?>
                        <input type="text" name="firstname" required placeholder="Enter Your FirstName" value="<?php echo isset($firstname) ? $firstname : ''; ?>" class="outline-none     h-[3rem] p-[5px] w-[85%] rounded">
                        <input type="text" name="lastname" required placeholder="Enter Your LastName" value="<?php echo isset($lastname) ? $lastname : ''; ?>" class="outline-none     h-[3rem] p-[5px] w-[85%] rounded">
                        <input type="text" name="username" required placeholder="Enter Your username" value="<?php echo isset($username) ? $username : ''; ?>" class="outline-none     h-[3rem] p-[5px] w-[85%] rounded">

                        <input type="password" name="password" required placeholder="Enter Your password" value="<?php echo isset($password) ? $password : ''; ?>" class="outline-none      h-[3rem] w-[85%] p-[5px] rounded">
                        <input type="password" name="cpassword" required placeholder="confirme Your password" value="<?php echo isset($password) ? $password : ''; ?>" class="outline-none     h-[3rem] w-[85%] p-[5px] rounded">
                        <div class="w-[85%]">
                            <select name="user-type"  id="" class="outline-none      h-[40px] p-[5px] w-[50%] rounded">
                                <option value="client">client</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
            <!-- Other input fields -->

        <!-- Add a hidden input to indicate editing mode -->


        <?php
        if (isset($_POST['userid']) && $_POST['editing'] === 'Edit') {
            echo '<input type="hidden" name="user_ids" value="' . implode(',', $userIds) . '">';
            echo '<input type="hidden" name="userid" value="' . (isset($id) ? $id : "") . '">';
            echo '<input type="submit" name="edited" value="Edit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 w-[85%] rounded cursor-pointer">';
        } else {
            echo '<input type="submit" name="add_user" value="Add User" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 w-[85%] rounded cursor-pointer">';
        }
        ?>




                        <a href="adress.php"  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 w-[85%] rounded cursor-pointer text-center">AJOUTER UNE ADRESS</a>
                        <p>already have an account?<a class="text-blue-700" href="index.php">login now</a></p>



                    </form>
                </div>
            </section>
           


        </body>

        </html>