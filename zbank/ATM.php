<?php
@include "DataBase.php";


// Handle Delete action
if (isset($_POST['deleteATM']) && isset($_POST['delete'])) {
    $id = $_POST['delete'];
    // Delete associated records in the 'atm' table
    $deleteATM = "DELETE FROM atm WHERE atmid = $id";
    if ($conn->query($deleteATM) !== TRUE) {
        echo "Error deleting ATM: " . $conn->error;
    }
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags and stylesheets go here -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Gestionaire Bancaire</title>
    <style>
        header {
            filter: drop-shadow(4px 4px 5px rgba(255, 255, 255));
            border: 1px white solid;
        }
    </style>
</head>

<body>
<section class="  relative overflow-x-auto shadow-md sm:rounded-lg top-10 w-[80%] ml-auto mr-5 ">
  
  <?php
include('sidenav.php');
?>


        <div class="flex justify-evenly items-center mb-[50px]">
            <h1 class="text-[50px] h-[10%]  text-center text-black">ATM</h1>
            <button  type="button" class="w-1/4 flex justify-around  text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 ml-[300px] mt-10"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
    <path d="M6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Zm11-3h-2V5a1 1 0 0 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 0 0 2 0V9h2a1 1 0 1 0 0-2Z"/>
    </svg><a href="addATM.php">Ajouter un distributeur </a></button>
            <!-- <a href="addATM.php" class="bg-blue-400 hover:bg-blue-600 text-white font-bold py-2 px-4 border border-blue-600 rounded">Add ATM</a> -->

        </div>
        <?php
        // Check if the 'submit' and 'bankid' are set, indicating that the form is submitted
        if (isset($_POST['submit']) && isset($_POST['bankid'])) {
            $bankid = $conn->real_escape_string($_POST['bankid']);

            // Fetch bank details based on the bankid
            $bank_sql = "SELECT * FROM bank WHERE bankid = '$bankid'";
            $bank_result = $conn->query($bank_sql);

            if ($bank_result->num_rows > 0) {
                $bank_row = $bank_result->fetch_assoc();
                echo "<div class ='flex w-[100%]  justify-center h-[60px] border-[2px] border-black border-solid items-center text-black'>";
                echo "<img class='border-[2px] border-black  border-solid w-[15%] h-[100%] flex items-center  justify-center' src='{$bank_row['logo']}' > ";
                echo "<p class='border-[2px] border-white border-solid w-[85%] h-[100%] flex items-center  justify-center'>Bank : {$bank_row["name"]}</p>";
                echo "</div>";
            }

            // Fetch data based on the selected bankid for 'agency'
            $sql = "SELECT * FROM atm WHERE bankid = '$bankid'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<table class="leading-9 h-[90%] w-[100%] text-center text-black">';
                echo '<thead>
                        <tr>
                            <th class="border-[2px] border-black border-solid w-[20%]">ID</th>
                            <th class="border-[2px] border-black border-solid w-[20%]">Adress</th>
                            <th class="border-[2px] border-black border-solid w-[20%]">Bank Id</th>
                            <th class="border-[2px] border-black border-solid w-[20%]">Edit</th>
                            <th class="border-[2px] border-black border-solid w-[20%]">Delete</th>
                          
                          
                        </tr>
                    </thead>';
                while ($row = $result->fetch_assoc()) {

                    echo "<tr>
                            <td class='border-[2px] border-black border-solid w-[15%]'>" . $row["atmId"] . " </td>
                            <td class='border-[2px] border-black border-solid w-[15%]'> " . $row["adress"] . "</td>
                            <td class='border-[2px] border-black border-solid w-[15%]'>" . $row["bankId"] . "</td>
                           
                            <td class='border-[2px] border-black border-solid w-[15%]'>
                            <form action='addATM.php' method='post'class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                            <input type='hidden' name='operation' value='" . $row["atmId"] . "'>
                            <input type='hidden' name='atmid' value='" . $row["atmId"] . "'>
                            <input type='submit'  name='editing' value='Edit'>
                        </form>
                        
                            </td>
                       
                          
                           <td class='border-[2px] border-black border-solid w-[15%]'>
                                <form action='ATM.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                                    <input type='hidden' name='delete' value='" . $row["atmId"] . "'>
                                    <input type='submit'  name='deleteATM' value='Delete'>
                                </form>
                            </td>
                        </tr>";
                }
                echo '</table>';
            } else {
                echo "<p class='text-center'>0 results</p>";
            }
        } else {
            // Handle the case when 'submit' and 'bankid' are not set (initial page load)
            // Fetch data for 'compts' table
            $sqlATM = "SELECT * FROM atm";
            $result2 = $conn->query($sqlATM);

            if ($result2->num_rows > 0) {
                echo '<table class="leading-9  w-[100%] text-center h-[7vh] items-start text-black">';
                echo '<thead>
                <tr>
                <th class="border-[2px] border-black border-solid w-[15%]">ID</th>
                <th class="border-[2px] border-black border-solid w-[15%]">Adress</th>
                <th class="border-[2px] border-black border-solid w-[15%]">Bank Id</th>
                <th class="border-[2px] border-black border-solid w-[15%]">Edit</th>
                <th class="border-[2px] border-black border-solid w-[15%]">Delete</th>
      
            </tr>
                    </thead>';
                while ($row = $result2->fetch_assoc()) {

                    echo "<tr>
                    <td class='border-[2px] border-black border-solid w-[15%]'>" . $row["atmId"] . " </td>
                    <td class='border-[2px] border-black border-solid w-[15%]'> " . $row["adress"] . "</td>
                    <td class='border-[2px] border-black border-solid w-[15%]'>" . $row["bankId"] . "</td>


                            
                            <td class='border-[2px] border-black border-solid w-[15%]'>
                            <form action='addATM.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black''>
                            <input type='hidden' name='operation' value='" . $row["atmId"] . "'>
                            <input type='hidden' name='atmid' value='" . $row["atmId"] . "'>
                            <input type='submit'  name='editing' value='Edit'>
                        </form>
                        
                            </td>
                            <td class='border-[2px] border-black border-solid w-[15%]'>
                            <form action='ATM.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                                <input type='hidden' name='delete' value='" . $row["atmId"] . "'>
                                <input type='submit'  name='deleteATM' value='Delete'>
                            </form>
                        </td>
                        </tr>";
                }
                echo '</table>';
            } else {
                echo "<p class='text-center'>0 results</p>";
            }
        }
        $conn->close();
        ?>
    </section>

  
    <script src="main.js">

    </script>

</body>

</html>