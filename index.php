<!--
  @project     : HeavenKare HMS
  
  @author      : Rayhana Akter Sumaya
  @email       : rayhanaaktersumaya.dev@gmail.com
  @repo        : https://github.com/RayhanaAkterDev/HavenKare-HMS
  @created     : 2025-09-03
  @updated     : 2025-10-22
  @copyright   : © 2025 HeavenKare HMS
-->

<!DOCTYPE html>
<html data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Heaven Kare</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="192x192" href="./assets/favicon/android-chrome-512x512.png">

    <link rel="icon" type="image/png" sizes="192x192" href="./assets/favicon/android-chrome-192x192.png">

    <link rel="icon" type="image/png" sizes="32x32" href="./assets/favicon/favicon-32x32.png">

    <link rel="icon" type="image/png" sizes="16x16" href="./assets/favicon/favicon-16x16.png">

    <link rel="shortcut icon" href="./assets/favicon/favicon.ico" type="image/x-icon" />

    <!-- Font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <!-- Google fonts: Poppins & Open sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    <!-- style file  -->
    <link rel="stylesheet" href="./dist/css/accord.css">
    <link rel="stylesheet" href="./dist/css/glide.core.min.css">
    <link rel="stylesheet" href="./dist/css/glide.theme.min.css">

    <link href="./src/output.css" rel="stylesheet" />

</head>

<body>
    <!-- Loader Overlay -->
    <div id="loader-overlay"
        class="fixed top-0 left-0 w-screen h-screen flex items-center justify-center bg-[#fffaf0] z-[99999] overflow-hidden">
        <div class="w-12 h-12 rounded-full border-4 border-[rgba(0,0,0,0.1)] border-t-[#d4af37] animate-spin"></div>
    </div>

    <!-- Navbar -->
    <header id="header">
        <nav class="nav-bar">
            <!-- Logo -->
            <a href="index.php" class="logo">
                <h3 class="logo-title">
                    HeavenKare
                    <span class="logo-subtitle">HMS</span>
                </h3>
            </a>

            <!-- Desktop Menu -->
            <ul class="desktop-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="#about_us">About Us</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#gallery">Gallery</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="#login">Logins</a></li>
            </ul>

            <!-- Appointment Button -->
            <a href="./hms/user-login.php" target="_blank" class="appointment-btn">
                <span class="icon-[tabler--hand-click] text-lg"></span>
                <span>Book Appointment</span>
            </a>

            <!-- Mobile Menu Button -->
            <div class="lg:hidden">
                <button id="menu-btn" class="mobile-menu_btn cursor-pointer">
                    <span class="icon-[tabler--menu-2] text-3xl"></span>
                </button>
            </div>
        </nav>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menus lg:hidden">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#about_us">About Us</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#gallery">Gallery</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="#login">Logins</a></li>
                <li>
                    <a href="./hms/user-login.php" target="_blank" class="mobile-appointment_btn">
                        <span class="icon-[tabler--hand-click] text-lg"></span>
                        Book Appointment
                    </a>
                </li>
            </ul>
        </div>
    </header>
    <!-- ================================ -->

    <!-- Carousel -->
    <div class="glide !mt-12 lg:!mt-24">
        <div class="glide__track" data-glide-el="track">
            <ul class="glide__slides slider-height">

                <!-- Slide 1 -->
                <li class="glide__slide relative bg-cover bg-center h-full">
                    <img src="./assets/carousel1.jpg" alt="carousel img 1" class="w-full h-full object-cover">
                    <div class="slider">
                        <div class="slider-text slider1">
                            <h2 class="slider-text__title">
                                Welcome to <span>HeavenKare</span>
                            </h2>
                            <p class="slider-text__sub-title">
                                Modern healthcare with compassion and expertise.
                                <span class="block font-semibold">Your well-being is our priority.</span>
                            </p>
                            <a href="./hms/user-login.php" class="mt-4 btn btn-gradient">
                                <span>Book an Appointment</span>
                                <span class="icon-[tabler--plus] rtl:rotate-180 font-bold"></span>
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Slide 2 -->
                <li class="glide__slide relative bg-cover bg-center">
                    <img src="./assets/carousel2.jpeg" alt="carousel img 2" class="w-full h-full object-cover">
                    <div class="slider">
                        <div class="slider-text slider2">
                            <h2 class="slider-text__title">
                                Advanced <span>Medical Care</span>
                            </h2>
                            <p class="slider-text__sub-title">
                                Cutting-edge technology and experienced specialists.
                                <span class="block font-semibold">Every treatment tailored to you.</span>
                            </p>
                            <a href="./hms/user-login.php" class="mt-4 btn btn-gradient">
                                <span>Book an Appointment</span>
                                <span class="icon-[tabler--plus] rtl:rotate-180 font-bold"></span>
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Slide 3 -->
                <li class="glide__slide relative bg-cover bg-center">
                    <img src="./assets/carousel3.jpg" alt="carousel img 3" class="w-full h-full object-cover">
                    <div class="slider">
                        <div class="slider-text slider3">
                            <h2 class="slider-text__title">
                                Compassionate <span>Patient Care</span>
                            </h2>
                            <p class="slider-text__sub-title">
                                Personalized attention for every patient.
                                <span class="block font-semibold">We treat you like family.</span>
                            </p>
                            <a href="./hms/user-login.php" class="mt-4 btn btn-gradient">
                                <span>Book an Appointment</span>
                                <span class="icon-[tabler--plus] rtl:rotate-180 font-bold"></span>
                            </a>
                        </div>
                    </div>
                </li>

            </ul>
        </div>

        <!-- Optional bullets -->
        <div class="glide__bullets" data-glide-el="controls[nav]">
            <button class="glide__bullet" data-glide-dir="=0"></button>
            <button class="glide__bullet" data-glide-dir="=1"></button>
            <button class="glide__bullet" data-glide-dir="=2"></button>
        </div>
    </div>
    <!-- ================================ -->

    <!-- About us -->
    <section id="about_us" class="about-section">

        <!-- Decorative floating shapes (clean version) -->
        <div class="decor decor--2xl-topright"></div>
        <div class="decor decor--xl-topleft"></div>
        <div class="decor decor--xl-bottomright"></div>
        <div class="decor decor--lg-topright"></div>
        <div class="decor decor--lg-bottomleft"></div>
        <div class="decor decor--md-center"></div>
        <div class="decor decor--md-right"></div>
        <div class="decor decor--sm-topright"></div>
        <div class="decor decor--sm-bottomright"></div>
        <div class="decor decor--xs-topleft"></div>
        <div class="decor decor--xs-topright"></div>
        <!-- ============================ -->

        <div class="about-section__content">

            <!-- About features -->
            <div class="about-section__features">
                <ul>
                    <!-- 1. Innovative Solutions -->
                    <li class="group flex flex-1 flex-col w-full md:items-end md:text-right">
                        <div class=" flex items-center gap-2.5 text-sm
                        intersect:motion-preset-slide-right intersect:motion-ease-spring-bouncier
                        intersect:motion-delay-[300ms]">
                            <!-- Icon: first on mobile, second on md+ -->
                            <span
                                class="order-1 md:order-2 text-bg-soft-neutral size-7.5 flex shrink-0 items-center justify-center rounded-full text-sm font-medium">
                                💡
                            </span>

                            <!-- Text: second on mobile, first on md+ -->
                            <div class="order-2 md:order-1 text-base-content block text-left md:text-right">
                                <h3
                                    class="text-base sm:text-lg lg:text-xl font-semibold text-dark leading-snug sm:leading-normal">
                                    Innovative Solutions</h3>
                                <p class="text-dark/70 text-sm sm:text-base leading-snug sm:leading-normal">
                                    Smart tools streamline hospital <br> operations and patient care.
                                </p>
                            </div>
                        </div>

                        <!-- Connector -->
                        <div
                            class="bg-neutral/20 ms-3.5 md:ml-auto md:me-3.5 h-10 w-px justify-self-start group-last:hidden">
                        </div>
                    </li>


                    <!-- 2. State-of-the-Art Facilities -->
                    <li class="group flex flex-1 flex-col w-full md:items-end md:text-right">
                        <div
                            class="flex items-center gap-2.5 text-sm intersect:motion-preset-slide-right intersect:motion-ease-spring-bouncier intersect:motion-delay-[350ms]">
                            <!-- Icon: first on mobile, second on md+ -->
                            <span
                                class="order-1 md:order-2 text-bg-soft-neutral size-7.5 flex shrink-0 items-center justify-center rounded-full text-sm font-medium">
                                🏥
                            </span>

                            <!-- Text: second on mobile, first on md+ -->
                            <div class="order-2 md:order-1 text-base-content block text-left md:text-right">
                                <h3
                                    class="text-base sm:text-lg lg:text-xl font-semibold text-dark leading-snug sm:leading-normal">
                                    State-of-the-Art Facilities</h3>
                                <p class="text-dark/70 text-sm sm:text-base leading-snug sm:leading-normal">
                                    Advanced equipment ensures <br> precision and comfort.
                                </p>
                            </div>
                        </div>

                        <!-- Connector -->
                        <div
                            class="bg-neutral/20 ms-3.5 md:ml-auto md:me-3.5 h-10 w-px justify-self-start group-last:hidden">
                        </div>
                    </li>


                    <!-- 3. Compassionate Care -->
                    <li class="group flex flex-1 flex-col w-full md:items-end md:text-right">
                        <div
                            class="flex items-center gap-2.5 text-sm intersect:motion-preset-slide-right intersect:motion-ease-spring-bouncier intersect:motion-delay-[400ms]">
                            <!-- Icon: first on mobile, second on md+ -->
                            <span
                                class="order-1 md:order-2 text-bg-soft-neutral size-7.5 flex shrink-0 items-center justify-center rounded-full text-sm font-medium">
                                🤝
                            </span>

                            <!-- Text: second on mobile, first on md+ -->
                            <div class="order-2 md:order-1 text-base-content block text-left md:text-right">
                                <h3
                                    class="text-base sm:text-lg lg:text-xl font-semibold text-dark leading-snug sm:leading-normal">
                                    Compassionate Care</h3>
                                <p class="text-dark/70 text-sm sm:text-base leading-snug sm:leading-normal">
                                    Patient-centric approach <br> for personalized attention.
                                </p>
                            </div>
                        </div>

                        <!-- Connector -->
                        <div
                            class="bg-neutral/20 ms-3.5 md:ml-auto md:me-3.5 h-10 w-px justify-self-start group-last:hidden">
                        </div>
                    </li>


                    <!-- 4. Safe & Secure -->
                    <li class="group flex flex-1 flex-col w-full md:items-end md:text-right">
                        <div
                            class="flex items-center gap-2.5 text-sm intersect:motion-preset-slide-right intersect:motion-ease-spring-bouncier intersect:motion-delay-[450ms]">
                            <!-- Icon: first on mobile, second on md+ -->
                            <span
                                class="order-1 md:order-2 text-bg-soft-neutral size-7.5 flex shrink-0 items-center justify-center rounded-full text-sm font-medium">
                                🔒
                            </span>

                            <!-- Text: second on mobile, first on md+ -->
                            <div class="order-2 md:order-1 text-base-content block text-left md:text-right">
                                <h3
                                    class="text-base sm:text-lg lg:text-xl font-semibold text-dark leading-snug sm:leading-normal">
                                    Safe & Secure</h3>
                                <p class="text-dark/70 text-sm sm:text-base leading-snug sm:leading-normal">
                                    Strict hygiene and safety <br> standards at every step.
                                </p>
                            </div>

                            <!-- Connector -->
                            <div
                                class="bg-neutral/20 ms-3.5 md:ml-auto md:me-3.5 h-10 w-px justify-self-start group-last:hidden">
                            </div>
                        </div>
                    </li>

                </ul>
            </div>

            <!-- About Text -->
            <div class="about-section__text">
                <h2
                    class="intersect:motion-preset-slide-left intersect:motion-ease-spring-bouncier intersect:motion-delay-[100ms]">
                    Why Choose <span class="highlight-half">HeavenKare?</span> </h2>
                <p
                    class="intersect:motion-preset-slide-left intersect:motion-ease-spring-bouncier intersect:motion-delay-[150ms]">
                    At HeavenKare, we combine advanced technology with compassionate care. Our Hospital
                    Management
                    System (HMS) ensures precision and efficiency in every step, from patient records to staff
                    schedules, creating a seamless healthcare experience. </p>
            </div>

        </div>
    </section>
    <!-- ================================ -->

    <!-- Services -->
    <section id="services" class="services-section">
        <div class="services-section__container">

            <!-- Section Heading -->
            <div class="section-heading text-center  intersect:motion-preset-slide-up intersect:motion-ease-spring-bouncier 
                intersect:motion-delay-[0ms]">
                <h2 class="section-heading__title">Our Premium Healthcare Services</h2>
                <p class="section-heading__subtitle">
                    Experience world-class care and advanced medical facilities designed for your well-being.
                </p>
            </div>

            <!-- Services Grid -->
            <div class="services-section__grid">

                <!-- Card 1 -->
                <div class="service-card intersect:motion-preset-slide-right  
            intersect:motion-ease-spring-bouncier
            intersect:motion-delay-[30ms]">
                    <div class="service-card__icon"><i class="fa-solid fa-heart-pulse"></i></div>
                    <h3 class="service-card__title">Cardiology</h3>
                    <p class="service-card__desc">
                        Advanced heart care with minimally invasive procedures and expert diagnostics.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="service-card intersect:motion-preset-slide-right  
            intersect:motion-ease-spring-bouncier
            intersect:motion-delay-[20ms]">
                    <div class="service-card__icon"><i class="fa-solid fa-brain"></i></div>
                    <h3 class="service-card__title">Neurology</h3>
                    <p class="service-card__desc">
                        Personalized neurological treatments and comprehensive recovery programs.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="service-card intersect:motion-preset-slide-right  
            intersect:motion-ease-spring-bouncier
            intersect:motion-delay-[10ms]">
                    <div class="service-card__icon"><i class="fa-solid fa-baby"></i></div>
                    <h3 class="service-card__title">Maternity Care</h3>
                    <p class="service-card__desc">
                        Safe and compassionate maternal services for a joyful birthing experience.
                    </p>
                </div>

                <!-- Card 4 -->
                <div class="service-card intersect:motion-preset-slide-left  
            intersect:motion-ease-spring-bouncier
            intersect:motion-delay-[10ms]">
                    <div class="service-card__icon"><i class="fa-solid fa-x-ray"></i></div>
                    <h3 class="service-card__title">Radiology</h3>
                    <p class="service-card__desc">
                        Precision imaging with state-of-the-art MRI, CT, and ultrasound facilities.
                    </p>
                </div>

                <!-- Card 5 -->
                <div class="service-card intersect:motion-preset-slide-left  
            intersect:motion-ease-spring-bouncier
            intersect:motion-delay-[20ms]">
                    <div class="service-card__icon"><i class="fa-solid fa-user-md"></i></div>
                    <h3 class="service-card__title">24/7 Emergency</h3>
                    <p class="service-card__desc">
                        Immediate critical care by expert professionals, anytime you need it.
                    </p>
                </div>

                <!-- Card 6 -->
                <div class="service-card intersect:motion-preset-slide-left  
            intersect:motion-ease-spring-bouncier
            intersect:motion-delay-[30ms]">
                    <div class="service-card__icon"><i class="fa-solid fa-flask-vial"></i></div>
                    <h3 class="service-card__title">Laboratory</h3>
                    <p class="service-card__desc">
                        Accurate diagnostics with modern automated labs and fast report delivery.
                    </p>
                </div>

            </div>
        </div>
    </section>
    <!-- ================================ -->

    <!-- Login portals -->
    <section id="login" class="login-section">

        <!-- Decorative floating shapes (clean version) -->
        <div class="decor decor--2xl-topright"></div>
        <div class="decor decor--xl-topleft"></div>
        <div class="decor decor--xl-bottomright"></div>
        <div class="decor decor--lg-topright"></div>
        <div class="decor decor--lg-bottomleft"></div>
        <div class="decor decor--md-right"></div>
        <div class="decor decor--sm-topright"></div>
        <div class="decor decor--xs-topleft"></div>
        <div class="decor decor--xs-topright"></div>
        <!-- ============================ -->

        <div class="login-section__container">
            <div class="login-section__header text-center">
                <h2 class="login-section__title">
                    Login <span class="text-gold-primary">Portals</span>
                </h2>
                <p class="login-section__subtitle">
                    Access your portal securely. Choose the appropriate portal to manage your healthcare efficiently.
                </p>
            </div>

            <div class="slidingTab">
                <div class="tab">
                    <span class="tab1">Patient</span>
                    <span class="tab2">Doctor</span>
                    <span class="tab3">admin</span>
                </div>

                <div class="panel_container ">
                    <div class="panels">
                        <!-- patient panel -->
                        <div class="panel">
                            <div class="panel_content">
                                <h2 class="portal-heading">Patient Portal</h2>
                                <p class="portal-desc">
                                    Easily manage your appointments, prescriptions, and test reports from anywhere —
                                    securely
                                    and conveniently.
                                </p>

                                <div class="accord">
                                    <div class="accord-item portal-item">
                                        <div class="accord-heading">
                                            <p class="flex justify-center items-center gap-2">
                                                <span
                                                    class="icon-[tabler--report-medical] text-gold-soft text-lg"></span>
                                                View prescriptions, lab results & medical reports
                                            </p>
                                        </div>
                                        <div class="accord-panel">Easily check and download your past prescriptions,
                                            test results, and doctor
                                            notes
                                            from your personal dashboard.</div>
                                    </div>

                                    <div class="accord-item portal-item">
                                        <div class="accord-heading">
                                            <p class="flex justify-center items-center gap-2">
                                                <span
                                                    class="icon-[tabler--calendar-check] text-gold-soft text-lg"></span>
                                                Book or reschedule doctor appointments anytime
                                            </p>
                                        </div>
                                        <div class="accord-panel"> Schedule new appointments or reschedule existing ones
                                            with a few clicks — no
                                            calls
                                            or paperwork needed.</div>
                                    </div>

                                    <div class="accord-item portal-item">
                                        <div class="accord-heading">
                                            <p class="flex justify-center items-center gap-2">
                                                <span class="icon-[tabler--messages] text-gold-soft text-lg"></span>
                                                Chat with your doctor or healthcare team securely
                                            </p>
                                        </div>
                                        <div class="accord-panel"> Stay connected with your healthcare providers in
                                            real-time through secure in-app
                                            messaging.</div>
                                    </div>
                                </div>

                                <a href="./hms/user-login.php" target="_blank" class="login-section__button portal-btn">
                                    Access Patient Portal
                                    <span class="icon-[tabler--activity-heartbeat]"></span>
                                </a>
                            </div>
                        </div>

                        <!-- doctor panel -->
                        <div class="panel">
                            <div class="panel_content">
                                <h2 class="portal-heading">Doctor Portal</h2>
                                <p class="portal-desc">
                                    Manage your patients, consultations, and schedules in one smart and secure
                                    workspace.
                                </p>

                                <div class="accord">
                                    <div class="accord-item portal-item">
                                        <div class="accord-heading">
                                            <p class="flex justify-center items-center gap-2">
                                                <span
                                                    class="icon-[tabler--clipboard-heart] text-gold-soft text-lg"></span>
                                                View patient histories, prescriptions & diagnostics
                                            </p>
                                        </div>
                                        <div class="accord-panel">Access and review detailed patient records,
                                            prescriptions, and diagnostic
                                            results in
                                            one dashboard.</div>
                                    </div>

                                    <div class="accord-item portal-item">
                                        <div class="accord-heading">
                                            <p class="flex justify-center items-center gap-2">
                                                <span
                                                    class="icon-[tabler--calendar-time] text-gold-soft text-lg"></span>
                                                Track and organize daily appointments efficiently
                                            </p>
                                        </div>
                                        <div class="accord-panel">Manage your consultation schedule, track
                                            cancellations, and plan ahead
                                            seamlessly.</div>
                                    </div>

                                    <div class="accord-item portal-item">
                                        <div class="accord-heading">
                                            <p class="flex justify-center items-center gap-2">
                                                <span class="icon-[tabler--users-group] text-gold-soft text-lg"></span>
                                                Collaborate seamlessly with nurses and other staff
                                            </p>
                                        </div>
                                        <div class="accord-panel"> Coordinate care and share notes securely with medical
                                            teams in real time.</div>
                                    </div>
                                </div>

                                <a href="./hms/doctor/index.php" target="_blank"
                                    class="login-section__button portal-btn">
                                    Access Doctor Portal
                                    <span class="icon-[tabler--activity-heartbeat]"></span>
                                </a>
                            </div>
                        </div>

                        <!-- admin panel -->
                        <div class="panel">
                            <div class="panel_content">
                                <h2 class="portal-heading">Admin Portal</h2>
                                <p class="portal-desc">
                                    Gain full control over hospital management — monitor departments, staff, and
                                    data
                                    analytics.
                                </p>

                                <div class="accord">
                                    <div class="accord-item portal-item">
                                        <div class="accord-heading">
                                            <p class="flex justify-center items-center gap-2">
                                                <span
                                                    class="icon-[tabler--building-hospital] text-gold-soft text-lg"></span>
                                                Oversee hospital departments and facilities
                                            </p>
                                        </div>
                                        <div class="accord-panel"> View departmental stats, manage facilities, and
                                            ensure smooth operational
                                            workflows.</div>
                                    </div>

                                    <div class="accord-item portal-item">
                                        <div class="accord-heading">
                                            <p class="flex justify-center items-center gap-2">
                                                <span class="icon-[tabler--key] text-gold-soft text-lg"></span>
                                                Manage staff roles, permissions, and accounts
                                            </p>
                                        </div>
                                        <div class="accord-panel">Add, remove, or modify user roles with full permission
                                            control and audit
                                            tracking.</div>
                                    </div>

                                    <div class="accord-item portal-item">
                                        <div class="accord-heading">
                                            <p class="flex justify-center items-center gap-2">
                                                <span class="icon-[tabler--chart-dots-3] text-gold-soft text-lg"></span>
                                                Access detailed reports & performance analytics
                                            </p>
                                        </div>
                                        <div class="accord-panel"> View real-time analytics for hospital operations,
                                            finances, and resource
                                            management.</div>
                                    </div>
                                </div>

                                <a href="./hms/admin/index.php" target="_blank"
                                    class="login-section__button portal-btn">
                                    Access Admin Portal
                                    <span class="icon-[tabler--activity-heartbeat]"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================ -->

    <!-- Our Gallery -->
    <section id="gallery" class="gallery-section">

        <!-- Decorative floating shapes (clean version) -->
        <div class="decor decor--2xl-topright"></div>
        <div class="decor decor--xl-topleft"></div>
        <div class="decor decor--xl-bottomright"></div>
        <div class="decor decor--lg-topright"></div>
        <div class="decor decor--lg-bottomleft"></div>
        <div class="decor decor--md-center"></div>
        <div class="decor decor--md-right"></div>
        <div class="decor decor--sm-topright"></div>
        <div class="decor decor--sm-bottomright"></div>
        <div class="decor decor--xs-topleft"></div>
        <div class="decor decor--xs-topright"></div>
        <!-- ============================ -->

        <div class="gallery-section__container">

            <!-- Section Header -->
            <div class="gallery-header text-center">
                <h2 class="gallery-title">
                    Our <span class="text-gold-primary">Gallery</span>
                </h2>
                <p class="gallery-subtitle">
                    Explore our modern facilities and patient care moments.
                </p>
            </div>


            <div class="gallery-grid">
                <div class="gallery-large intersect:motion-preset-slide-right  
            intersect:motion-ease-spring-bouncier
            intersect:motion-delay-[200ms]">
                    <img src="./assets/gallery/gallery_03.jpg" alt="gallery-img-03" />
                </div>

                <div class="gallery-grid-2x2 intersect:motion-preset-slide-right  
            intersect:motion-ease-spring-bouncier
            intersect:motion-delay-[100ms]">
                    <img src="./assets/gallery/gallery_01.jpg" alt="gallery-img-01" />
                    <img src="./assets/gallery/gallery_02.jpg" alt="gallery-img-02" />
                    <img src="./assets/gallery/gallery_06.jpg" alt="gallery-img-06" />
                    <img src="./assets/gallery/gallery_08.jpg" alt="gallery-img-08" />
                </div>

                <div class="gallery-grid-2x2 intersect:motion-preset-slide-left  
            intersect:motion-ease-spring-bouncier
            intersect:motion-delay-[100ms]">
                    <img src="./assets/gallery/gallery_01.jpg" alt="gallery-img-04" />
                    <img src="./assets/gallery/gallery_13.jpg" alt="gallery-img-13" />
                    <img src="./assets/gallery/gallery_01.jpg" alt="gallery-img-01" />
                    <img src="./assets/gallery/gallery_05.jpg" alt="gallery-img-05" />
                </div>

                <div class="gallery-large intersect:motion-preset-slide-left  
            intersect:motion-ease-spring-bouncier
            intersect:motion-delay-[200ms]">
                    <img src="./assets/gallery/gallery_14.jpeg" alt="gallery-img-014" />
                </div>
            </div>
        </div>
    </section>
    <!-- ================================ -->

    <!-- Contact us -->
    <section id="contact" class="contact-section">
        <div class="contact-section__container">
            <!-- LEFT SIDE -->
            <div class="contact-section__info">
                <div class="contact-section__header">
                    <h2
                        class="contact-section__title intersect:motion-preset-slide-right intersect:motion-ease-spring-bouncier intersect:motion-delay-[100ms]">
                        Get in <span class="highlight-gold">Touch</span>
                    </h2>
                    <p
                        class="contact-section__subtitle intersect:motion-preset-slide-right intersect:motion-ease-spring-bouncier intersect:motion-delay-[200ms]">
                        Need help or have questions? We're always here for you — 24/7 patient support and guidance.
                    </p>
                </div>

                <div class="contact-section__details">
                    <div
                        class="contact-section__detail-item intersect:motion-preset-slide-right intersect:motion-ease-spring-bouncier intersect:motion-delay-[300ms]">
                        <div class="contact-section__icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <h4>Visit Us</h4>
                            <p>
                                123 Serenity Avenue, Dhanmondi<br>
                                Dhaka, Bangladesh
                            </p>
                        </div>
                    </div>

                    <div
                        class="contact-section__detail-item intersect:motion-preset-slide-right intersect:motion-ease-spring-bouncier intersect:motion-delay-[400ms]">
                        <div class="contact-section__icon">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <h4>Call Us</h4>
                            <p>+880 1234-567890<br>
                                Open 24 Hours</p>
                        </div>
                    </div>

                    <div
                        class="contact-section__detail-item intersect:motion-preset-slide-right intersect:motion-ease-spring-bouncier intersect:motion-delay-[500ms]">
                        <div class="contact-section__icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <h4>Email</h4>
                            <p>support@heavenKare.care</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE FORM -->
            <div
                class="contact-section__form intersect:motion-preset-slide-left intersect:motion-ease-spring-bouncier intersect:motion-delay-[550ms]">
                <h3 class="contact-section__form-title">Send Us a Message</h3>
                <form class="contact-section__form-fields">
                    <div class="contact-section__field">
                        <label>Full Name</label>
                        <input type="text" placeholder="Enter your name" />
                    </div>
                    <div class="contact-section__field">
                        <label>Email Address</label>
                        <input type="email" placeholder="Enter your email" />
                    </div>
                    <div class="contact-section__field">
                        <label>Message</label>
                        <textarea rows="4" placeholder="Write your message..."></textarea>
                    </div>
                    <button type="submit" class="contact-section__button">Submit Message</button>
                </form>
            </div>
        </div>
    </section>
    <!-- ================================ -->

    <!-- Footer -->
    <footer class="footer-section">
        <div class="footer-section__container">
            <!-- Top Footer: Brand + Services + Contact + Newsletter -->
            <div class="footer-section__top">
                <!-- Brand & Description -->
                <div class="footer-section__brand">
                    <h3 class="footer-section__brand-title">
                        HeavenKare <span class="footer-section__brand-subtitle">HMS</span>
                    </h3>
                    <p class="footer-section__brand-desc">
                        Delivering world-class healthcare solutions with compassion,
                        modern tools, and reliability. Your health, our priority.
                    </p>
                    <div class="footer-section__socials">
                        <a href="#"><span class="icon-[tabler--brand-facebook]"></span></a>
                        <a href="#"><span class="icon-[tabler--brand-twitter]"></span></a>
                        <a href="#"><span class="icon-[tabler--brand-linkedin]"></span></a>
                    </div>
                </div>

                <!-- Services / Quick Links -->
                <div class="footer-section__links">
                    <h4 class="footer-section__title">Our Services</h4>
                    <ul class="footer-section__links-list">
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#services">Outpatient Services</a></li>
                        <li><a href="#logins">Inpatient Services</a></li>
                        <li><a href="#gallery">Diagnostics</a></li>
                        <li><a href="#contact_us">Emergency</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-section__contact space-y-3">
                    <h4 class="footer-section__title">Contact Us</h4>
                    <p>123 Serenity Avenue, Dhanmondi
                        Dhaka, Bangladesh </p>
                    <p>Phone: +880 1712 345678</p>
                    <p>Email: info@heavenKare.care</p>
                    <p>Open 24/7 (Monday to Sunday)</p>
                </div>

                <!-- Newsletter / Stay Updated -->
                <div class="footer-section__newsletter">
                    <h4 class="footer-section__title">Stay Updated</h4>
                    <p class="footer-section__newsletter-desc">
                        Subscribe to our newsletter for latest updates on services, health
                        tips, and hospital announcements.
                    </p>
                    <form class="footer-section__newsletter-form">
                        <input type="email" placeholder="Enter your email" />
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="footer-section__bottom">
            <div class="footer-section__bottom-container">
                <p>&copy; 2025 HeavenCare Hospital. All Rights Reserved.</p>
                <div class="footer-section__bottom-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms & Conditions</a>
                    <a href="#">Support</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- ================================ -->

    <!-- Navbar and mobile menu markup here... -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuItems = mobileMenu.querySelectorAll('a'); // select all links inside the menu

        // Toggle menu on menu button click
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('open');
        });

        // Close menu when any menu item is clicked
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
            });
        });
    });
    </script>



    <!-- script files or cdn's -->
    <script defer src="https://unpkg.com/tailwindcss-intersect@2.x.x/dist/observer.min.js"></script>

    <script src="./dist/jquery.min.js"></script>
    <script src="./dist/jquery.accord-min.js"></script>
    <script src="./dist/sliding_panels.js"></script>
    <script src="./dist/glide.min.js"></script>

    <!-- <script src="./dist/main.js"></script> -->
    <script src="./dist/script.js"></script>
    <script src="./dist/loader.js"></script>

</body>

</html>