<?php


@include 'database.php';
if (isset($_POST["bankid"])) {
    $id = $_POST["bankid"];
    if (!empty($id)) {
        $bnkinfo = "SELECT * FROM bank where bankid = $id";
        $stk_bnk_info = $conn->query($bnkinfo);

        if ($stk_bnk_info) {
            $rows = mysqli_fetch_assoc($stk_bnk_info);
            $logo = $rows["logo"];
            $name = $rows["name"];
        } else {
            echo "Error fetching bank information: " . $conn->error;
        }
    } else {
        echo "Bank ID is empty.";
    }
}


if (isset($_POST['submit'])) {
    // Sanitize user inputs
    $logo = mysqli_real_escape_string($conn, $_POST['logo']);
    $name = mysqli_real_escape_string($conn, $_POST['bankname']);

    // Insert new bank into the 'bank' table
    $insertQuery = "INSERT INTO bank (logo, name) VALUES ('$logo', '$name')";

    $conn->query($insertQuery);

    header('location: banques.php');
}
if (isset($_POST["operation"]) && $_POST["operation"] === "edit") {
    // Retrieve bank details for editing
    $id = $_POST["bankid"];
    $bnkinfo = "SELECT * FROM bank WHERE bankid = $id";
    $stk_bnk_info = $conn->query($bnkinfo);
    $rows = mysqli_fetch_assoc($stk_bnk_info);

    // Populate variables with retrieved data
    $logo = $rows["logo"];
    $name = $rows["name"];
}

if (isset($_POST['edited'])) {

    $logo = mysqli_real_escape_string($conn, $_POST['logo']);
    $name = mysqli_real_escape_string($conn, $_POST['bankname']);
    $id = $_POST['bankid'];
    $updateQuery = "UPDATE bank SET logo='$logo', name='$name' WHERE bankid=$id";
    $conn->query($updateQuery);
    header('location: banques.php');
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
  

    <title>ADD BANK</title>
</head>

<body>

<section class=" bg-blue-300 p-[60px]">
        
        <div class="min-h-[85vh] w-[90%] m-auto gap-[15px] flex flex-col md:flex-row md:justify-evenly items-center  ">

            <form action="addbank.php" method="post" class="flex flex-col gap-[19px] h-[70%] md:h-[80%] w-[80%] md:w-[30%] mb-[15px] p-[10px] bg-gray-300/20 items-center justify-center rounded-[20px]">

                <h1 class="md:text-[45px] text-[35px] text-gray-900 font-bold">Banks</h1>

                <input type="text" name="logo" placeholder="Add Logo" value="<?php echo isset($logo) ? $logo : ''; ?>" class="outline-none bg-gray-200 border border-black/50 border-solid md:h-[3rem] h-[2rem] p-[10px] w-[85%] rounded">

                <input type="text" name="bankname" placeholder="Bank Name" value="<?php echo isset($name) ? $name : ''; ?>" class="outline-none bg-gray-200 border border-black/50 border-solid md:h-[3rem] h-[2rem] w-[85%] p-[10px] rounded">

                <!-- Hidden input for bankid -->
                <input type="hidden" name="bankid" value="<?php echo isset($id) ? $id : ''; ?>">

                <?php
                if (isset($_POST['bankid'])) {
                    echo '<input type="submit" name="edited" value="Edit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 w-[85%] rounded cursor-pointer">';
                } else {
                    echo '<input type="submit" name="submit" value="Add Bank" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 w-[85%] rounded cursor-pointer">';
                };
                ?>
          <a href="banques.php" class="bg-blue-500 w-[85%] text-center hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">Banks details</a>

            </form>

        </div>

    </section>
  


</body>

</html>