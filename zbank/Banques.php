<?php
@include "DataBase.php";


// Handle Delete action
if (isset($_POST['Deletes']) && isset($_POST['bankid'])) {
    $id = $_POST['bankid'];

    // Delete associated records in the 'agency' table
    $deleteAgencies = "DELETE FROM agency WHERE bankId = $id";
    $conn->query($deleteAgencies);

    $deleteATM = "DELETE FROM atm WHERE bankid = $id";
    $conn->query($deleteATM);

    // Delete the record from the 'bank' table
    $deleteBank = "DELETE FROM bank WHERE bankid = $id";
    $conn->query($deleteBank);
}


?>














<!DOCTYPE html>
<html lang="en">

<head>
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
            <h1 class="text-[50px] h-[10%]  text-center text-black">BANKS</h1>
            <button  type="button" class="w-1/4 flex justify-around  text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 ml-[300px] mt-10"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
    <path d="M6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Zm11-3h-2V5a1 1 0 0 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 0 0 2 0V9h2a1 1 0 1 0 0-2Z"/>
    </svg><a href="addbank.php">Ajouter un distributeur </a></button>
        </div>



        <table class="leading-9 w-[100%] text-black text-center">
            <thead class="text-black">
                <tr>
                    <th class="border-[2px] border-black border-solid w-[12%]">Logo</th>
                    <th class="border-[2px] border-black border-solid w-[12%]">Bank</th>
                    <th class="border-[2px] border-black border-solid w-[12%]">ID</th>
                    <th class="border-[2px] border-black border-solid w-[12%]">Edit</th>
                    <th class="border-[2px] border-black border-solid w-[12%]">Delete</th>
                    <th class="border-[2px] border-black border-solid w-[12%]">Agences</th>
                    <th class="border-[2px] border-black border-solid w-[12%]">ATM</th>
                </tr>
            </thead>
            <tbody>
                <?php



                $sql = "SELECT logo, name, bankid FROM bank";
                $result = $conn->query($sql);


                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        $id = $row["bankid"];
                        echo "<tr>
                    <td class='border-[2px] border-black border-solid '><img class='h-[60px] w-[240px]' src='" . $row["logo"] . "' alt=''></td>
                    <td class='border-[2px] border-black border-solid '>" . $row["name"] . "</td>
                    <td class='border-[2px] border-black border-solid '>" . $row["bankid"] . "</td>
                    <td class='border-[2px] border-black border-solid '>
                   
                    <form action='addbank.php' method='post' class = 'h-[5vh]  cursor-pointer width-[150px] hover:bg-black bg-white hover:text-white text-black'>
                    <input type='hidden' name='operation' value='" . $row["bankid"] . "'>
                    <input type='hidden' name='bankid' value='" . $row["bankid"] . "'>
                    <input type='submit'  value='Edit'>
                    
                    </form>
                

                
                </td>
                
                    <td class='border-[2px] border-black border-solid '>
                    <form action='banques.php' method='post' class = 'h-[5vh]  cursor-pointer width-[150px] hover:bg-black bg-white hover:text-white text-black'>
                        <input type='hidden' name='bankid' value='" . $row["bankid"] . "'>
                        <input type='submit'  name='Deletes' value='Delete'>
                    </form>
                </td>
                
                    <td class='border-[2px] border-black border-solid '>
                        <form action='agences.php' method='post' class = 'h-[5vh]  cursor-pointer width-[150px] hover:bg-black bg-white hover:text-white text-black'>
                            <input type='hidden' name='bankid' value='" . $row["bankid"] . "'>
                            <input type='submit'  name='submit' value='Agences'>
                        </form>
                    </td>
                    <td class='border-[2px] border-black border-solid '>
                    <form action='ATM.php' method='post' class = 'h-[5vh]  cursor-pointer width-[150px] hover:bg-black bg-white hover:text-white text-black'>
                        <input type='hidden' name='bankid' value='" . $row["bankid"] . "'>
                        <input type='submit'  name='submit' value='ATM'>
                    </form>
                </td>
                </tr><br>";
                    }
                } else {
                    // Handle case when there are no rows in the table
                }
                $conn->close();
                ?>
            </tbody>
        </table>




    </section>
    
    <script src="main.js">

    </script>

</body>

</html>