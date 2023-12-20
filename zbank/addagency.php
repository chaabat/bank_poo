        <?php


        @include 'database.php';
        if (isset($_POST["agencyId"])) {
            $id = $_POST["agencyId"];
            $bnkinfo = "SELECT * FROM agency where agencyId = $id";
            $stk_bnk_info = $conn->query("$bnkinfo");
            $rows = mysqli_fetch_assoc($stk_bnk_info);
            $agencyname = $rows["agencyname"];
            $longtitud = $rows["Longitude"];
            $latitude = $rows["Latitude"];
        }


        if (isset($_POST['submit'])) {
            // Sanitize user inputs

            $agencyname = mysqli_real_escape_string($conn, $_POST['agencyname']);
            $longtitud = mysqli_real_escape_string($conn, $_POST['Longitude']);
            $latitude = mysqli_real_escape_string($conn, $_POST['Latitude']);
            $bankid = $_POST['bankid'];;


            // Insert new bank into the 'bank' table
            $insertQuery = "INSERT INTO agency (agencyname, Longitude, Latitude ,bankid)
             VALUES 
             ('$agencyname', '$longtitud', '$latitude', '$bankid')";

            $conn->query($insertQuery);

            header('location: agences.php');
        }
        if (isset($_POST['agencyid']) && $_POST['editing'] === 'Edit') {
            // Retrieve agency details for editing
            $id = $_POST["agencyid"];
            $agencyinfo = "SELECT * FROM agency WHERE agencyid = $id";
            $stk_agt_info = $conn->query($agencyinfo);
            $rows = mysqli_fetch_assoc($stk_agt_info);

            // Populate variables with retrieved data
            $agencyname = $rows["agencyname"];
            $longtitud = $rows["longitude"];
            $latitude = $rows["latitude"];
          
        }


        if (isset($_POST['edited'])) {

            $agencyname = mysqli_real_escape_string($conn, $_POST['agencyname']);
            $longtitud = mysqli_real_escape_string($conn, $_POST['Longitude']);
            $latitude = mysqli_real_escape_string($conn, $_POST['Latitude']);
            $id = $_POST['agencyid'];
            $updateQuery = "UPDATE agency SET agencyname='$agencyname', Longitude='$longtitud', Latitude='$latitude' WHERE agencyid=$id";
            $conn->query($updateQuery);
            header('location: agences.php');
        }



        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="https://cdn.tailwindcss.com"></script>
          

            <title>ADD AGENCY</title>
        </head>

        <body>

        <section class=" bg-blue-300 p-[60px]">

                <div class="min-h-[85vh] w-[90%] m-auto gap-[15px] flex flex-col md:flex-row md:justify-evenly items-center  ">

                    <form action="addagency.php" method="post" class="flex flex-col gap-[19px] h-[70%] md:h-[80%] w-[80%] md:w-[30%] mb-[15px] p-[10px] bg-gray-300/20 items-center justify-center rounded-[20px]">

                        <h1 class="md:text-[45px] text-[35px] text-gray-900 font-bold">Agencies</h1>

                        <input type="text" name="agencyname" placeholder="Agency name" value="<?php echo isset($agencyname) ? $agencyname : ''; ?>" class="outline-none bg-gray-200 border border-black/50 border-solid md:h-[3rem] h-[2rem] p-[10px] w-[85%] rounded">

                        <input type="text" name="Longitude" placeholder="Longitude exp : F5RRT" value="<?php echo isset($longtitud) ? $longtitud : ''; ?>" class="outline-none bg-gray-200 border border-black/50 border-solid md:h-[3rem] h-[2rem] w-[85%] p-[10px] rounded">

                        <input type="text" name="Latitude" placeholder="Latitude exp : 55OG" value="<?php echo isset($latitude) ? $latitude : ''; ?>" class="outline-none bg-gray-200 border border-black/50 border-solid md:h-[3rem] h-[2rem] p-[10px] w-[85%] rounded">

                        <!-- Hidden input for agencyid -->
                        <input type="hidden" name="agencyid" value="<?php echo isset($id) ? $id : ''; ?>">

                        <!-- Dropdown for selecting bank -->
                        <select name="bankid" class="outline-none h-[40px] p-[5px] w-[50%] rounded">
                            <?php
                            // Query to get all banks
                            $bankQuery = "SELECT bankid, name FROM bank";
                            $bankResult = mysqli_query($conn, $bankQuery);

                            // Check if there are banks
                            if (mysqli_num_rows($bankResult) > 0) {
                                while ($bankRow = mysqli_fetch_assoc($bankResult)) {
                                    // Set the selected attribute if the bank is associated with the agency
                                    $selected = ($bankRow['bankid'] == $selectedBankId) ? 'selected' : '';
                                    echo '<option value="' . $bankRow['bankid'] . '" ' . $selected . '>' . $bankRow['name'] . '</option>';
                                }
                            } else {
                                echo '<option value="" disabled>No banks found</option>';
                            }
                            ?>
                        </select>

                        <!-- Edit or Add Agency button -->
                        <?php
                        if (isset($_POST['agencyid'])) {
                            echo '<input type="submit" name="edited" value="Edit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 w-[85%] rounded cursor-pointer">';
                        } else {
                            echo '<input type="submit" name="submit" value="Add Agency" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 w-[85%] rounded cursor-pointer">';
                        };
                        ?>


                        <a href="agences.php" class="bg-blue-500 w-[85%] text-center hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">Agency Details</a>

                    </form>

                </div>

            </section>
           


        </body>

        </html>