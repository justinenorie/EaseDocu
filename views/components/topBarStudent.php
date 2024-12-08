
<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <style>
        body {
            margin: 0;
        }

        :root {
            --text-color: #1F2937;
            --text-light: #FFFFFF;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        .top-bar {
            background-color: var(--PRIMARY);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            box-shadow: 0 0 10px var(--DARKBG);
            z-index: 1000;
        }

        .top-bar h1 {
            margin: 0;
            font-size: var(--HEADER);
            font-family: var(--POPPINS);
            color: var(--TEXTLIGHT);
        }

        .title {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .logo {
            width: 40px;
            height: 40px;
            margin-right: 12px;
        }

        .title h1 {
            color: var(--text-light);
            font-size: 1.5rem;
        }

        .profile {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .profile-icon {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .account-info {
            color: var(--text-light);
        }

        .account-info h2 {
            font-family: var(--WORK-SANS);
            font-size: 1rem;
            margin: 0;
        }

        .account-info p {
            font-family: var(--WORK-SANS);
            font-size: 0.8rem;
            opacity: 0.8;
            margin: 0;
        }

        .icon {
            /* background-color: green; */
            width: 6vh;
            height: 6vh;
            margin-right: 10px;
        }

        .logo {
            width: 40px;
            height: 40px;
            margin-right: 12px;
        }

        a {
            text-decoration: none;
        }


        .modal {
            position: absolute;
            top: 80px;
            right: 20px;
            background-color: var(--text-light);
            width: 200px;
            border-radius: 10px;
            box-shadow: 0 4px 20px var(--shadow-color);
            overflow: hidden;
            transform: translateY(-10px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
        }

        .modal.show {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            padding: 15px;
        }

        .modal-item {
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
        }

        .modal-item:hover {
            background-color: var(--background-color);
        }

        .modal-item svg {
            margin-right: 10px;
            width: 20px;
            height: 20px;
        }
        
        @media screen and (max-width: 550px) {
            .top-bar {
                padding: 20px 35px;
            }

            .profile {
                padding-right: 0;
            }
        }

        @media screen and (max-width: 498px) {
            .title h1 {
                display: none;
            }

            .top-bar {
                padding: 20px 30px;
            }

            .account p {
                display: none;
            }

            .account h2 {
                margin: 0;
            }

            .account {
                justify-content: center;
            }

            .profile {
                align-items: center;
            }
        }
        .modal-text{
            font-size: 16px;
            font-family: Arial;
            font-weight: 500;
            margin: 0;
            color: rgb(50, 50, 50);
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <div class="title">
            <img class="logo" src="../../public/images/icons/logo-white.png" alt="EaseDocu Logo">
            <h1>EaseDocu</h1>
        </div>

        <div class="profile" id="profile-trigger">
            <img class="profile-icon" src="../../public/images/icons/profile.png" alt="User Icon">
            <div class="account-info">
                <h2 id="username"><?php echo htmlspecialchars($studentData['name'] ?? 'Unknown User'); ?></h2>
                <p id="user-id"><?php echo htmlspecialchars($studentData['studentID'] ?? 'Unknown ID'); ?></p>
            </div>
        </div>

        <div id="account-modal" class="modal">
            <div class="modal-content">
                <div class="modal-item" id="show-profile">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="modal-text">Show Profile</p>
                </div>
                <div class="modal-item" id="logout-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <p class="modal-text">Logout</p>
                </div>
            </div>
        </div>

        </div>
    </div>

    <div id="modal" class="modal" style="display: none;">
        <p style="cursor: pointer;color:white;" >Show Profile</p>
        <a style="cursor: pointer;color:white;" href="logout.php" id="logout-btn">Logout</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const profileTrigger = document.getElementById('profile-trigger');
            const accountModal = document.getElementById('account-modal');
            const logoutBtn = document.getElementById('logout-btn');

            // Toggle modal visibility
            profileTrigger.addEventListener('click', (event) => {
                event.stopPropagation();
                accountModal.classList.toggle('show');
            });

            // Hide modal when clicking outside
            document.addEventListener('click', (event) => {
                if (!accountModal.contains(event.target) && !profileTrigger.contains(event.target)) {
                    accountModal.classList.remove('show');
                }
            });

            // Hide modal on Escape key
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    accountModal.classList.remove('show');
                }
            });

            // Logout functionality (you'll need to implement server-side logout)
            logoutBtn.addEventListener('click', () => {
                window.location.href = 'logout.php';
            });
        });
    </script>
</body>

</html>