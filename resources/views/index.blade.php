<!DOCTYPE HTML>
<html>

<head>
    <title>Parkless</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets/css/noscript.css') }}" />
    </noscript>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="is-preload">

    <!-- Wrapper -->
    <div id="content">
        <div id="wrapper">

            <form id="loginuser" method="get" action="#">
                <a href="{{ route('parklessLogin') }}" id="login-button" class="button">Login</a>
            </form>
            <header id="header">
                <div class="logo">
                    <a href="{{ route('map') }}"><span class="fa-solid fa-map-location-dot fa-2x"></span></a>
                </div>
                <div class="content">
                    <div class="inner">
                        <h1>Parkless</h1>
                        <p>No Fees, No Fuss, Just Park!</p>
                    </div>
                </div>

                <nav>
                    <ul>
                        <li><a href="#intro">About Us</a></li>
                        <li><a href="#work">Features</a></li>
                        <li><a href="#about">Donate</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </nav>
            </header>

            <!-- Main -->
            <div id="main">

                <!-- About Us -->
                <article id="intro">
                    <h2 class="major">About Us</h2>
                    <span class="image main"><img src="{{ asset('images/Agus lapar bu.jpg') }}" alt="" /></span>
                    <p>Parkless is a web app that is meant to combat illegal parking attendants at supermarket or other
                        business place that has a parking area. This is a widespread issue in Indonesia, ruining small
                        business places and annoys customer. Even when business places clearly states in their sign that
                        their parking is free, this doesn't stop illegal parking attendants. They would still "force"
                        visitors to pay for their parking fee by making them feel bad when they don't. Worse, they
                        sometime hide the sign or vandalize it so customers doesn't know about the free parking.</p>
                    <p>This isn't about giving two thousand rupiah per visit, but the feeling of being forced to pay for
                        something with no benefit. The illegal parking attendants wouldn't even take responsibility if
                        anything happened to the vehicles. This people are parasite to business and is basically an
                        extortion. Our goal is to combat this issue, by helping customers knows which place has illegal
                        parking attendants and which place that doesn't.</p>
                </article>

                <!-- Features -->
                <article id="work">
                    <h2 class="major">Features</h2>
                    <span class="image main"><img src="{{ asset('images/Parkless.jpg') }}" alt="" /></span>
                    <p>Real-Time Illegal Parking Attendant Status <br> This feature allows users to instantly know the
                        status of illegal parking attendants in real time. Users can report when an attendant is
                        charging an unauthorized fee, and others will be able to view this information instantly to
                        avoid such encounters.</p>
                    <p>User Review System with Upvote/Downvote <br> Users can leave reviews about parking locations,
                        specifically regarding the presence of illegal parking attendants. The reviews can be upvoted or
                        downvoted by other users to help others find reliable and transparent parking areas.</p>
                    <p>Know Your Illegal Parking Fee <br> This feature helps users understand the illegal parking fees
                        being charged at specific shopping locations. Users can report these fees and see the average or
                        most common amounts charged by attendants at those locations.</p>
                </article>

                <!-- Donate Easter Egg -->
                <article id="about">
                    <h2 class="major">Donate</h2>
                    <span class="image main"><img src="{{ asset('images/gilmore.jpg') }}" alt="" /></span>
                    <p>If you want to support us, you can donate us extra points on <a
                            href="https://lms.binus.ac.id/lms/dashboard">Nilai UAS</a>.</p>
                </article>

                <!-- Contact -->
                <article id="contact">
                    <h2 class="major">Contact</h2>
                    <form method="post" action="#">
                        @csrf
                        <div class="fields">
                            <div class="field half">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" />
                            </div>
                            <div class="field half">
                                <label for="email">Email</label>
                                <input type="text" name="email" id="email" />
                            </div>
                            <div class="field">
                                <label for="message">Message</label>
                                <textarea name="message" id="message" rows="4"></textarea>
                            </div>
                        </div>
                        <ul class="actions">
                            <li><button type="submit" class="button primary">Send Message</button></li>
                            <li><input type="reset" value="Reset" /></li>
                        </ul>
                    </form>
                    <ul class="icons">
                        <li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a>
                        </li>
                        <li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a>
                        </li>
                        <li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a>
                        </li>
                        <li><a href="#" class="icon brands fa-github"><span class="label">GitHub</span></a></li>
                    </ul>
                </article>
            </div>

            <!-- Footer -->
            <footer id="footer">
                <p class="copyright">&copy; Parkless PKM-KC OOP By Group 4</a></p>
            </footer>

        </div>
    </div>

    <!-- BG -->
    <div id="bg"></div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/browser.min.js') }}"></script>
    <script src="{{ asset('assets/js/breakpoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/util.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

</body>

</html>
