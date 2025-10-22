 <!-- Page content -->
 <main class="flex-1 p-6">
     <!-- Page title and breadcrumb -->
     <div class="mb-6">
         <h1 class="text-3xl font-bold text-gray-800">User | Dashboard</h1>
         <nav class="text-gray-500 text-sm mt-1">
             <ol class="list-reset flex">
                 <li><span>User</span></li>
                 <li><span class="mx-2">/</span></li>
                 <li class="text-blue-600 font-semibold">Dashboard</li>
             </ol>
         </nav>
     </div>

     <!-- Dashboard cards -->
     <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
         <!-- My Profile -->
         <div class="bg-white rounded-xl shadow p-6 text-center hover:shadow-lg transition">
             <div class="text-blue-600 mb-4">
                 <span class="fa-stack fa-2x">
                     <i class="fa fa-square fa-stack-2x"></i>
                     <i class="fa fa-smile fa-stack-1x fa-inverse"></i>
                 </span>
             </div>
             <h2 class="text-xl font-semibold mb-2">My Profile</h2>
             <a href="edit-profile.php" class="text-blue-600 hover:underline">Update Profile</a>
         </div>

         <!-- My Appointments -->
         <div class="bg-white rounded-xl shadow p-6 text-center hover:shadow-lg transition">
             <div class="text-blue-600 mb-4">
                 <span class="fa-stack fa-2x">
                     <i class="fa fa-square fa-stack-2x"></i>
                     <i class="fa fa-paperclip fa-stack-1x fa-inverse"></i>
                 </span>
             </div>
             <h2 class="text-xl font-semibold mb-2">My Appointments</h2>
             <a href="appointment-history.php" class="text-blue-600 hover:underline">View Appointment History</a>
         </div>

         <!-- Book Appointment -->
         <div class="bg-white rounded-xl shadow p-6 text-center hover:shadow-lg transition">
             <div class="text-blue-600 mb-4">
                 <span class="fa-stack fa-2x">
                     <i class="fa fa-square fa-stack-2x"></i>
                     <i class="fa fa-calendar-check fa-stack-1x fa-inverse"></i>
                 </span>
             </div>
             <h2 class="text-xl font-semibold mb-2">Book My Appointment</h2>
             <a href="book-appointment.php" class="text-blue-600 hover:underline">Book Appointment</a>
         </div>
     </div>
 </main>

 <!-- Header -->
 <!-- <?php include('include/header.php'); ?> -->



 <!-- Footer -->
 <!-- <?php include('include/footer.php'); ?> -->