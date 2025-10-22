<?php
include_once('include/config.php');
if (isset($_POST['submit'])) {
    $fname = $_POST['full_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $query = mysqli_query($con, "insert into users(fullname,address,city,gender,email,password) values('$fname','$address','$city','$gender','$email','$password')");
    if ($query) {
        echo "<script>alert('Successfully Registered. You can login now');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HeavenKare | Patient Registration</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    function valid() {
        if (document.registration.password.value != document.registration.password_again.value) {
            alert("Password and Confirm Password do not match!");
            document.registration.password_again.focus();
            return false;
        }
        return true;
    }

    function userAvailability() {
        $("#loaderIcon").show();
        $.ajax({
            url: "check_availability.php",
            data: 'email=' + $("#email").val(),
            type: "POST",
            success: function(data) {
                $("#user-availability-status1").html(data);
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }
    </script>

    <style>
    .input-group {
        position: relative;
        margin-top: 1.5rem;
    }

    .input-group input {
        width: 100%;
        border: none;
        border-bottom: 2px solid #ccc;
        background: transparent;
        padding: 10px 0;
        color: #111;
        outline: none;
        font-size: 1rem;
    }

    .input-group label {
        position: absolute;
        left: 0;
        top: 10px;
        color: #777;
        font-size: 1rem;
        pointer-events: none;
        transition: 0.3s ease all;
    }

    .input-group input:focus~label,
    .input-group input:not(:placeholder-shown)~label {
        top: -10px;
        font-size: 0.8rem;
        color: #2563eb;
    }

    .input-group i {
        position: absolute;
        right: 0;
        top: 10px;
        color: #aaa;
    }

    .input-group input:focus {
        border-color: #2563eb;
    }
    </style>
</head>

<body class="min-h-screen bg-gray-50 flex">

    <!-- Left Banner Fixed -->
    <div
        class="hidden lg:flex fixed left-0 top-0 w-1/2 h-screen bg-gradient-to-br from-blue-500 to-teal-400 items-center justify-center p-10">
        <div class="text-white max-w-md space-y-6 text-center">
            <h1 class="text-4xl font-extrabold">Welcome to HeavenCare</h1>
            <p class="text-lg opacity-90">Register and manage your hospital visits with ease. Efficient, safe, and
                reliable.</p>
            <i class="fa fa-hospital fa-10x opacity-20"></i>
        </div>
    </div>

    <!-- Right Form Section -->
    <div class="flex-1 w-full min-h-screen flex items-start justify-center py-10 lg:ml-[50vw]">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Patient Registration</h1>
                <p class="text-gray-500 mt-2">Create your account to manage hospital visits</p>
            </div>

            <form name="registration" id="registration" method="post" onsubmit="return valid();"
                class="bg-white shadow-xl rounded-2xl p-8 space-y-6">

                <!-- Full Name -->
                <div class="input-group">
                    <input type="text" name="full_name" placeholder=" " required>
                    <label>Full Name</label>
                    <i class="fa fa-user"></i>
                </div>

                <!-- Address -->
                <div class="input-group">
                    <input type="text" name="address" placeholder=" " required>
                    <label>Address</label>
                    <i class="fa fa-home"></i>
                </div>

                <!-- City -->
                <div class="input-group">
                    <input type="text" name="city" placeholder=" " required>
                    <label>City</label>
                    <i class="fa fa-city"></i>
                </div>

                <!-- Gender -->
                <div class="flex gap-6 items-center mt-6">
                    <span class="text-gray-700 font-medium">Gender:</span>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="gender" value="female" class="h-4 w-4 text-blue-600"> Female
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="gender" value="male" class="h-4 w-4 text-blue-600"> Male
                    </label>
                </div>

                <!-- Email -->
                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder=" " required onblur="userAvailability()">
                    <label>Email</label>
                    <i class="fa fa-envelope"></i>
                </div>
                <span id="user-availability-status1" class="text-xs text-red-500 mt-1 block"></span>

                <!-- Password -->
                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder=" " required>
                    <label>Password</label>
                    <i class="fa fa-lock"></i>
                </div>

                <!-- Confirm Password -->
                <div class="input-group">
                    <input type="password" name="password_again" id="password_again" placeholder=" " required>
                    <label>Confirm Password</label>
                    <i class="fa fa-lock"></i>
                </div>

                <!-- Agree Checkbox -->
                <label class="flex items-center gap-2 text-gray-700 mt-4">
                    <input type="checkbox" id="agree" value="agree" checked readonly class="form-checkbox h-4 w-4">
                    I agree to the terms and conditions
                </label>

                <!-- Submit Button -->
                <button type="submit" name="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl flex justify-center items-center gap-2 transition duration-200 mt-4">
                    Register <i class="fa fa-arrow-right"></i>
                </button>

                <!-- Login link -->
                <p class="text-center text-gray-500 text-sm mt-4">
                    Already have an account?
                    <a href="user-login.php" class="text-blue-600 font-medium hover:underline">Log in</a>
                </p>
            </form>
        </div>
    </div>

</body>

</html>